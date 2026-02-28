import { prisma } from '@/lib/prisma';
import { getAuthenticatedUser } from '@/lib/auth';
import { redirect } from 'next/navigation';
import { maskLicenseKey, getDaysRemaining } from '@/lib/license';

export default async function InstallationsPage() {
  const user = await getAuthenticatedUser();
  if (!user) redirect('/login');

  const installations = await prisma.installation.findMany({
    where: { isActive: true },
    orderBy: { lastCheckedAt: 'desc' },
    include: {
      license: {
        select: {
          licenseKey: true,
          type: true,
          plan: true,
          status: true,
          expiresAt: true,
        },
      },
      geoData: {
        select: {
          countryCode: true,
          countryName: true,
          region: true,
          city: true,
        },
      },
    },
  });

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">Installationen</h1>

      <div className="bg-white rounded-lg border border-gray-200 overflow-x-auto">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Plattform</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Root-Domain</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Aktivierungsdatum</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Verbleibende Tage</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Lizenz-Typ</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Plugin-Version</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Lizenz-Key</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Land / Region</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Letzter Heartbeat</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {installations.map((inst) => {
              const daysRemaining = getDaysRemaining(inst.license.expiresAt);

              return (
                <tr key={inst.id} className="hover:bg-gray-50">
                  <td className="px-4 py-3">
                    <PlatformBadge platform={inst.platform} />
                  </td>
                  <td className="px-4 py-3 font-mono text-xs">
                    {inst.siteUrl}
                    {inst.isLocal && <span className="ml-1 text-gray-400 text-xs">(local)</span>}
                  </td>
                  <td className="px-4 py-3 text-gray-600">
                    {new Date(inst.activatedAt).toLocaleDateString('de-DE')}
                  </td>
                  <td className="px-4 py-3">
                    {daysRemaining === null ? (
                      <span className="text-green-600">Lifetime</span>
                    ) : (
                      <span className={daysRemaining <= 14 ? 'text-amber-600 font-medium' : daysRemaining <= 0 ? 'text-red-600 font-medium' : ''}>
                        {daysRemaining} Tage
                      </span>
                    )}
                  </td>
                  <td className="px-4 py-3">
                    <span className={`inline-block px-2 py-0.5 rounded text-xs font-medium ${
                      inst.license.type === 'pro_plus' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'
                    }`}>
                      {inst.license.type === 'pro_plus' ? 'Pro+' : 'Premium'}
                    </span>
                  </td>
                  <td className="px-4 py-3">{inst.pluginVersion}</td>
                  <td className="px-4 py-3 font-mono text-xs text-gray-500">
                    {maskLicenseKey(inst.license.licenseKey)}
                  </td>
                  <td className="px-4 py-3 text-gray-600">
                    {inst.geoData ? (
                      <>
                        {inst.geoData.countryCode}
                        {inst.geoData.region && ` / ${inst.geoData.region}`}
                        {inst.geoData.city && ` / ${inst.geoData.city}`}
                      </>
                    ) : '-'}
                  </td>
                  <td className="px-4 py-3 text-gray-500">
                    {new Date(inst.lastCheckedAt).toLocaleDateString('de-DE')}
                  </td>
                </tr>
              );
            })}
            {installations.length === 0 && (
              <tr>
                <td colSpan={9} className="px-4 py-8 text-center text-gray-500">
                  Keine aktiven Installationen vorhanden.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

function PlatformBadge({ platform }: { platform: string }) {
  const styles: Record<string, string> = {
    wordpress: 'bg-blue-100 text-blue-700',
    drupal: 'bg-sky-100 text-sky-700',
    react: 'bg-cyan-100 text-cyan-700',
  };

  return (
    <span className={`inline-block px-2 py-0.5 rounded text-xs font-medium capitalize ${styles[platform] || 'bg-gray-100 text-gray-600'}`}>
      {platform}
    </span>
  );
}
