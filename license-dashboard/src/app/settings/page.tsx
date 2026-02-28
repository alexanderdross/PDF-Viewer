import { prisma } from '@/lib/prisma';
import { getAuthenticatedUser } from '@/lib/auth';
import { redirect } from 'next/navigation';

export default async function SettingsPage() {
  const user = await getAuthenticatedUser();
  if (!user) redirect('/login');

  const productMappings = await prisma.stripeProductMap.findMany({
    orderBy: { createdAt: 'desc' },
  });

  return (
    <div className="p-8 max-w-4xl">
      <h1 className="text-2xl font-bold mb-6">Einstellungen</h1>

      {/* Stripe Product Mapping */}
      <div className="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h2 className="text-lg font-semibold mb-4">Stripe Produkt-Mapping</h2>
        <p className="text-sm text-gray-600 mb-4">
          Ordne Stripe-Produkte den Lizenz-Typen und Plaenen zu. Bei einem erfolgreichen Checkout wird
          automatisch eine Lizenz mit den hier konfigurierten Parametern erstellt.
        </p>

        <table className="w-full text-sm mb-4">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Stripe Product ID</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Lizenz-Typ</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Plan</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Sites</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Dauer (Tage)</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {productMappings.map((mapping) => (
              <tr key={mapping.id}>
                <td className="px-3 py-2 font-mono text-xs">{mapping.stripeProductId}</td>
                <td className="px-3 py-2 capitalize">{mapping.licenseType === 'pro_plus' ? 'Pro+' : 'Premium'}</td>
                <td className="px-3 py-2 capitalize">{mapping.plan}</td>
                <td className="px-3 py-2">{mapping.siteLimit === 0 ? 'Unlimited' : mapping.siteLimit}</td>
                <td className="px-3 py-2">{mapping.durationDays === 0 ? 'Lifetime' : mapping.durationDays}</td>
              </tr>
            ))}
            {productMappings.length === 0 && (
              <tr>
                <td colSpan={5} className="px-3 py-4 text-center text-gray-500">
                  Keine Mappings konfiguriert. Fuege Stripe-Produkte hinzu, sobald die Stripe-Integration aktiv ist.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Environment Info */}
      <div className="bg-white rounded-lg border border-gray-200 p-6">
        <h2 className="text-lg font-semibold mb-4">System-Information</h2>
        <div className="space-y-2 text-sm">
          <div className="flex justify-between">
            <span className="text-gray-600">Dashboard URL</span>
            <span className="font-mono">{process.env.NEXT_PUBLIC_APP_URL || 'nicht konfiguriert'}</span>
          </div>
          <div className="flex justify-between">
            <span className="text-gray-600">Stripe</span>
            <span>{process.env.STRIPE_SECRET_KEY ? 'Verbunden' : 'Nicht konfiguriert'}</span>
          </div>
          <div className="flex justify-between">
            <span className="text-gray-600">GeoIP Datenbank</span>
            <span>{process.env.GEOIP_DB_PATH || 'Standard-Pfad'}</span>
          </div>
          <div className="flex justify-between">
            <span className="text-gray-600">Admin E-Mail</span>
            <span>{user}</span>
          </div>
        </div>
      </div>
    </div>
  );
}
