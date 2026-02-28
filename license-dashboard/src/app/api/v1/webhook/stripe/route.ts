import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { verifyWebhookSignature } from '@/lib/stripe';
import { generateLicenseKey } from '@/lib/license';
import type { ApiError } from '@/types';

export async function POST(request: NextRequest) {
  const signature = request.headers.get('stripe-signature');
  if (!signature) {
    return NextResponse.json<ApiError>(
      { error: 'missing_signature', message: 'Missing stripe-signature header.' },
      { status: 400 },
    );
  }

  const body = await request.text();

  let event;
  try {
    event = await verifyWebhookSignature(body, signature);
  } catch (err) {
    const message = err instanceof Error ? err.message : 'Invalid signature';
    return NextResponse.json<ApiError>(
      { error: 'invalid_signature', message },
      { status: 400 },
    );
  }

  // Idempotency check
  const existing = await prisma.stripeEvent.findUnique({
    where: { stripeEventId: event.id },
  });

  if (existing) {
    return NextResponse.json({ received: true, message: 'Event already processed.' });
  }

  // Store the event
  const stripeEvent = await prisma.stripeEvent.create({
    data: {
      stripeEventId: event.id,
      eventType: event.type,
      payload: event.data as object,
    },
  });

  try {
    switch (event.type) {
      case 'checkout.session.completed': {
        await handleCheckoutCompleted(event.data.object, stripeEvent.id);
        break;
      }
      case 'invoice.paid': {
        await handleInvoicePaid(event.data.object, stripeEvent.id);
        break;
      }
      case 'customer.subscription.deleted': {
        await handleSubscriptionDeleted(event.data.object, stripeEvent.id);
        break;
      }
      case 'invoice.payment_failed': {
        await handlePaymentFailed(event.data.object, stripeEvent.id);
        break;
      }
    }

    await prisma.stripeEvent.update({
      where: { id: stripeEvent.id },
      data: { processed: true },
    });
  } catch (err) {
    console.error(`[Stripe Webhook] Error processing ${event.type}:`, err);
    // Don't fail the webhook — Stripe will retry
  }

  return NextResponse.json({ received: true });
}

async function handleCheckoutCompleted(session: Record<string, unknown>, eventId: string) {
  const customerId = session.customer as string;
  const customerEmail = (session.customer_details as Record<string, unknown>)?.email as string;
  const subscriptionId = session.subscription as string;

  // Look up product mapping from metadata or line items
  const metadata = session.metadata as Record<string, string> | undefined;
  const productId = metadata?.product_id;

  if (!productId) {
    console.warn('[Stripe Webhook] No product_id in session metadata, skipping license creation.');
    return;
  }

  const productMap = await prisma.stripeProductMap.findFirst({
    where: { stripeProductId: productId },
  });

  if (!productMap) {
    console.warn(`[Stripe Webhook] No product mapping found for ${productId}`);
    return;
  }

  // Generate license key
  const keyType = productMap.licenseType === 'pro_plus' ? 'pro_plus' : 'premium';
  const licenseKey = generateLicenseKey(keyType);

  const expiresAt = productMap.durationDays > 0
    ? new Date(Date.now() + productMap.durationDays * 24 * 60 * 60 * 1000)
    : null;

  const license = await prisma.license.create({
    data: {
      licenseKey,
      type: productMap.licenseType,
      plan: productMap.plan,
      status: 'inactive',
      siteLimit: productMap.siteLimit,
      customerEmail: customerEmail || '',
      stripeCustomerId: customerId || null,
      stripeSubscriptionId: subscriptionId || null,
      expiresAt,
    },
  });

  // Link event to license
  await prisma.stripeEvent.update({
    where: { id: eventId },
    data: { licenseId: license.id },
  });

  // Audit log
  await prisma.auditLog.create({
    data: {
      licenseId: license.id,
      eventType: 'license.created',
      details: {
        source: 'stripe',
        stripe_customer_id: customerId,
        plan: productMap.plan,
        type: productMap.licenseType,
      },
    },
  });

  // TODO: Send license key email to customerEmail
  console.log(`[Stripe Webhook] License created: ${licenseKey} for ${customerEmail}`);
}

async function handleInvoicePaid(invoice: Record<string, unknown>, eventId: string) {
  const subscriptionId = invoice.subscription as string;
  if (!subscriptionId) return;

  // Skip the first invoice (already handled by checkout.session.completed)
  const billingReason = invoice.billing_reason as string;
  if (billingReason === 'subscription_create') return;

  const license = await prisma.license.findFirst({
    where: { stripeSubscriptionId: subscriptionId },
  });

  if (!license) return;

  // Extend license by 365 days
  const currentExpiry = license.expiresAt || new Date();
  const baseDate = currentExpiry > new Date() ? currentExpiry : new Date();
  const newExpiry = new Date(baseDate.getTime() + 365 * 24 * 60 * 60 * 1000);

  await prisma.license.update({
    where: { id: license.id },
    data: { expiresAt: newExpiry, status: 'active' },
  });

  await prisma.stripeEvent.update({
    where: { id: eventId },
    data: { licenseId: license.id },
  });

  await prisma.auditLog.create({
    data: {
      licenseId: license.id,
      eventType: 'license.renewed',
      details: { new_expiry: newExpiry.toISOString(), source: 'stripe' },
    },
  });
}

async function handleSubscriptionDeleted(subscription: Record<string, unknown>, eventId: string) {
  const subscriptionId = subscription.id as string;

  const license = await prisma.license.findFirst({
    where: { stripeSubscriptionId: subscriptionId },
  });

  if (!license) return;

  // Don't immediately revoke — let it expire naturally
  await prisma.stripeEvent.update({
    where: { id: eventId },
    data: { licenseId: license.id },
  });

  await prisma.auditLog.create({
    data: {
      licenseId: license.id,
      eventType: 'subscription.cancelled',
      details: { stripe_subscription_id: subscriptionId },
    },
  });
}

async function handlePaymentFailed(invoice: Record<string, unknown>, eventId: string) {
  const subscriptionId = invoice.subscription as string;
  if (!subscriptionId) return;

  const license = await prisma.license.findFirst({
    where: { stripeSubscriptionId: subscriptionId },
  });

  if (!license) return;

  await prisma.stripeEvent.update({
    where: { id: eventId },
    data: { licenseId: license.id },
  });

  await prisma.auditLog.create({
    data: {
      licenseId: license.id,
      eventType: 'payment.failed',
      details: {
        stripe_subscription_id: subscriptionId,
        amount: invoice.amount_due,
      },
    },
  });
}
