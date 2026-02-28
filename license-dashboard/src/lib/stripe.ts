import Stripe from 'stripe';

let stripeInstance: Stripe | null = null;

export function getStripe(): Stripe {
  if (stripeInstance) return stripeInstance;

  const secretKey = process.env.STRIPE_SECRET_KEY;
  if (!secretKey) {
    throw new Error('STRIPE_SECRET_KEY environment variable is not set');
  }

  stripeInstance = new Stripe(secretKey, {
    apiVersion: '2025-01-27.acacia',
    typescript: true,
  });

  return stripeInstance;
}

export function getWebhookSecret(): string {
  const secret = process.env.STRIPE_WEBHOOK_SECRET;
  if (!secret) {
    throw new Error('STRIPE_WEBHOOK_SECRET environment variable is not set');
  }
  return secret;
}

export async function verifyWebhookSignature(
  body: string | Buffer,
  signature: string,
): Promise<Stripe.Event> {
  const stripe = getStripe();
  const secret = getWebhookSecret();
  return stripe.webhooks.constructEvent(body, signature, secret);
}
