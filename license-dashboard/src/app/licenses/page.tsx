import { prisma } from '@/lib/prisma';
import { getAuthenticatedUser } from '@/lib/auth';
import { redirect } from 'next/navigation';
import { maskLicenseKey, getDaysRemaining } from '@/lib/license';

export default async function LicensesPage() {
  const user = await getAuthenticatedUser();
  if (!user) redirect('/login');

  const licenses = await prisma.license.findMany({
    orderBy: { createdAt: 'desc' },
    include: {
      installations: {
        where: { isActive: true, isLocal: false },
        select: { id: true },
      },
    },
  });

  return (
    <div className="p-8">
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-bold">Lizenzen</h1>
        <a
          href="/licenses/new"
          className="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700"
        >
          Neue Lizenz
        </a>
      </div>

      <div className="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Lizenz-Key</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Typ</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Plan</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Status</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Sites</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Verbleibend</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">E-Mail</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Erstellt</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {licenses.map((license) => {
              const daysRemaining = getDaysRemaining(license.expiresAt);
              const activeSites = license.installations.length;

              return (
                <tr key={license.id} className="hover:bg-gray-50">
                  <td className="px-4 py-3">
                    <a href={`/licenses/${license.id}`} className="text-blue-600 hover:underline font-mono text-xs">
                      {maskLicenseKey(license.licenseKey)}
                    </a>
                  </td>
                  <td className="px-4 py-3">
                    <span className={`inline-block px-2 py-0.5 rounded text-xs font-medium ${
                      license.type === 'pro_plus' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'
                    }`}>
                      {license.type === 'pro_plus' ? 'Pro+' : 'Premium'}
                    </span>
                  </td>
                  <td className="px-4 py-3 capitalize">{license.plan}</td>
                  <td className="px-4 py-3">
                    <StatusBadge status={license.status} />
                  </td>
                  <td className="px-4 py-3">
                    {activeSites}/{license.siteLimit === 0 ? '\u221e' : license.siteLimit}
                  </td>
                  <td className="px-4 py-3">
                    {daysRemaining === null ? 'Lifetime' : (
                      <span className={daysRemaining <= 14 ? 'text-amber-600 font-medium' : ''}>
                        {daysRemaining} Tage
                      </span>
                    )}
                  </td>
                  <td className="px-4 py-3 text-gray-600">{license.customerEmail}</td>
                  <td className="px-4 py-3 text-gray-500">
                    {new Date(license.createdAt).toLocaleDateString('de-DE')}
                  </td>
                </tr>
              );
            })}
            {licenses.length === 0 && (
              <tr>
                <td colSpan={8} className="px-4 py-8 text-center text-gray-500">
                  Keine Lizenzen vorhanden.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

function StatusBadge({ status }: { status: string }) {
  const styles: Record<string, string> = {
    active: 'bg-green-100 text-green-700',
    expired: 'bg-red-100 text-red-700',
    grace_period: 'bg-amber-100 text-amber-700',
    revoked: 'bg-gray-100 text-gray-700',
    inactive: 'bg-gray-100 text-gray-500',
  };

  return (
    <span className={`inline-block px-2 py-0.5 rounded text-xs font-medium ${styles[status] || styles.inactive}`}>
      {status.replace('_', ' ')}
    </span>
  );
}
