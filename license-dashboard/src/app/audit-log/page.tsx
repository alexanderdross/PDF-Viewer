import { prisma } from '@/lib/prisma';
import { getAuthenticatedUser } from '@/lib/auth';
import { redirect } from 'next/navigation';
import { maskLicenseKey } from '@/lib/license';

export default async function AuditLogPage() {
  const user = await getAuthenticatedUser();
  if (!user) redirect('/login');

  const logs = await prisma.auditLog.findMany({
    orderBy: { createdAt: 'desc' },
    take: 100,
    include: {
      license: {
        select: { licenseKey: true, type: true },
      },
    },
  });

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">Audit-Log</h1>

      <div className="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Zeitpunkt</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Event</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Lizenz</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Details</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">IP</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {logs.map((log) => (
              <tr key={log.id} className="hover:bg-gray-50">
                <td className="px-4 py-3 text-gray-500 whitespace-nowrap">
                  {new Date(log.createdAt).toLocaleString('de-DE')}
                </td>
                <td className="px-4 py-3">
                  <EventBadge type={log.eventType} />
                </td>
                <td className="px-4 py-3 font-mono text-xs text-gray-500">
                  {log.license ? maskLicenseKey(log.license.licenseKey) : '-'}
                </td>
                <td className="px-4 py-3 text-gray-600 text-xs max-w-xs truncate">
                  {JSON.stringify(log.details)}
                </td>
                <td className="px-4 py-3 text-gray-400 text-xs">
                  {log.ipAddress || '-'}
                </td>
              </tr>
            ))}
            {logs.length === 0 && (
              <tr>
                <td colSpan={5} className="px-4 py-8 text-center text-gray-500">
                  Keine Audit-Log-Eintraege.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

function EventBadge({ type }: { type: string }) {
  const styles: Record<string, string> = {
    'license.created': 'bg-green-100 text-green-700',
    'license.activated': 'bg-blue-100 text-blue-700',
    'license.deactivated': 'bg-amber-100 text-amber-700',
    'license.renewed': 'bg-green-100 text-green-700',
    'license.expired': 'bg-red-100 text-red-700',
    'license.revoked': 'bg-gray-100 text-gray-700',
    'subscription.cancelled': 'bg-red-100 text-red-700',
    'payment.failed': 'bg-red-100 text-red-700',
    'stripe.payment': 'bg-green-100 text-green-700',
  };

  return (
    <span className={`inline-block px-2 py-0.5 rounded text-xs font-medium ${styles[type] || 'bg-gray-100 text-gray-600'}`}>
      {type}
    </span>
  );
}
