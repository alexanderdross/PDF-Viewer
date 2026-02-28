import { prisma } from '@/lib/prisma';
import { getAuthenticatedUser } from '@/lib/auth';
import { redirect, notFound } from 'next/navigation';
import { maskLicenseKey, getDaysRemaining } from '@/lib/license';

export default async function LicenseDetailPage({ params }: { params: Promise<{ id: string }> }) {
  const user = await getAuthenticatedUser();
  if (!user) redirect('/login');

  const { id } = await params;

  const license = await prisma.license.findUnique({
    where: { id },
    include: {
      installations: {
        orderBy: { activatedAt: 'desc' },
        include: { geoData: true },
      },
      auditLogs: {
        orderBy: { createdAt: 'desc' },
        take: 20,
      },
    },
  });

  if (!license) notFound();

  const daysRemaining = getDaysRemaining(license.expiresAt);

  return (
    <div className="p-8 max-w-5xl">
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <div>
          <a href="/licenses" className="text-sm text-blue-600 hover:underline mb-1 block">
            &larr; Zurueck zur Liste
          </a>
          <h1 className="text-2xl font-bold">Lizenz-Details</h1>
        </div>
      </div>

      {/* License Info */}
      <div className="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
          <div>
            <p className="text-gray-500 mb-1">Lizenz-Key</p>
            <p className="font-mono">{maskLicenseKey(license.licenseKey)}</p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Typ</p>
            <p className="font-medium">{license.type === 'pro_plus' ? 'Pro+ Enterprise' : 'Premium'}</p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Plan</p>
            <p className="capitalize">{license.plan}</p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Status</p>
            <p className="font-medium capitalize">{license.status.replace('_', ' ')}</p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Site-Limit</p>
            <p>{license.siteLimit === 0 ? 'Unlimited' : license.siteLimit}</p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Verbleibende Tage</p>
            <p className={daysRemaining !== null && daysRemaining <= 14 ? 'text-amber-600 font-medium' : ''}>
              {daysRemaining === null ? 'Lifetime' : `${daysRemaining} Tage`}
            </p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Ablaufdatum</p>
            <p>{license.expiresAt ? new Date(license.expiresAt).toLocaleDateString('de-DE') : 'Nie'}</p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Erstellt</p>
            <p>{new Date(license.createdAt).toLocaleDateString('de-DE')}</p>
          </div>
          <div>
            <p className="text-gray-500 mb-1">Kunden-E-Mail</p>
            <p>{license.customerEmail}</p>
          </div>
          {license.stripeCustomerId && (
            <div>
              <p className="text-gray-500 mb-1">Stripe Customer</p>
              <p className="font-mono text-xs">{license.stripeCustomerId}</p>
            </div>
          )}
        </div>
        {license.notes && (
          <div className="mt-4 pt-4 border-t border-gray-100">
            <p className="text-gray-500 text-sm mb-1">Notizen</p>
            <p className="text-sm">{license.notes}</p>
          </div>
        )}
      </div>

      {/* Installations */}
      <div className="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h2 className="text-lg font-semibold mb-4">
          Installationen ({license.installations.filter((i) => i.isActive).length} aktiv)
        </h2>
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Plattform</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Root-Domain</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Plugin-Version</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Land</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Aktiviert</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Letzter Check</th>
              <th className="text-left px-3 py-2 font-medium text-gray-600">Status</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {license.installations.map((inst) => (
              <tr key={inst.id} className={inst.isActive ? '' : 'opacity-50'}>
                <td className="px-3 py-2 capitalize">{inst.platform}</td>
                <td className="px-3 py-2 font-mono text-xs">
                  {inst.siteUrl}
                  {inst.isLocal && <span className="ml-1 text-gray-400">(local)</span>}
                </td>
                <td className="px-3 py-2">{inst.pluginVersion}</td>
                <td className="px-3 py-2">{inst.geoData?.countryCode || '-'}</td>
                <td className="px-3 py-2 text-gray-500">
                  {new Date(inst.activatedAt).toLocaleDateString('de-DE')}
                </td>
                <td className="px-3 py-2 text-gray-500">
                  {new Date(inst.lastCheckedAt).toLocaleDateString('de-DE')}
                </td>
                <td className="px-3 py-2">
                  {inst.isActive ? (
                    <span className="text-green-600 text-xs font-medium">Aktiv</span>
                  ) : (
                    <span className="text-gray-400 text-xs">Inaktiv</span>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Audit Log */}
      <div className="bg-white rounded-lg border border-gray-200 p-6">
        <h2 className="text-lg font-semibold mb-4">Aktivitaets-Log</h2>
        <div className="space-y-2">
          {license.auditLogs.map((log) => (
            <div key={log.id} className="flex items-center justify-between text-sm py-1">
              <span className="text-gray-700">{log.eventType}</span>
              <span className="text-gray-500">
                {new Date(log.createdAt).toLocaleString('de-DE')}
              </span>
            </div>
          ))}
          {license.auditLogs.length === 0 && (
            <p className="text-sm text-gray-500">Keine Eintraege.</p>
          )}
        </div>
      </div>
    </div>
  );
}
