import { prisma } from '@/lib/prisma';
import { getAuthenticatedUser } from '@/lib/auth';
import { redirect } from 'next/navigation';

export default async function StatsPage() {
  const user = await getAuthenticatedUser();
  if (!user) redirect('/login');

  const [
    geoDistribution,
    platformDistribution,
    versionDistribution,
    licensesByType,
    licensesByPlan,
    totalActive,
    totalExpired,
    totalRevoked,
  ] = await Promise.all([
    prisma.geoData.groupBy({
      by: ['countryCode', 'countryName'],
      _count: true,
      orderBy: { _count: { countryCode: 'desc' } },
      take: 20,
    }),
    prisma.installation.groupBy({
      by: ['platform'],
      where: { isActive: true },
      _count: true,
    }),
    prisma.installation.groupBy({
      by: ['pluginVersion'],
      where: { isActive: true },
      _count: true,
      orderBy: { _count: { pluginVersion: 'desc' } },
    }),
    prisma.license.groupBy({
      by: ['type'],
      _count: true,
    }),
    prisma.license.groupBy({
      by: ['plan'],
      where: { status: 'active' },
      _count: true,
    }),
    prisma.license.count({ where: { status: 'active' } }),
    prisma.license.count({ where: { status: 'expired' } }),
    prisma.license.count({ where: { status: 'revoked' } }),
  ]);

  return (
    <div className="p-8">
      <h1 className="text-2xl font-bold mb-6">Statistiken</h1>

      {/* License Status Overview */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <StatCard title="Aktive Lizenzen" value={totalActive} color="green" />
        <StatCard title="Abgelaufene Lizenzen" value={totalExpired} color="red" />
        <StatCard title="Gesperrte Lizenzen" value={totalRevoked} color="gray" />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {/* Geo Distribution */}
        <div className="bg-white rounded-lg border border-gray-200 p-6">
          <h2 className="text-lg font-semibold mb-4">Geo-Verteilung (Top 20)</h2>
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b border-gray-100">
                <th className="text-left py-2 font-medium text-gray-600">Land</th>
                <th className="text-right py-2 font-medium text-gray-600">Installationen</th>
              </tr>
            </thead>
            <tbody>
              {geoDistribution.map((geo) => (
                <tr key={geo.countryCode} className="border-b border-gray-50">
                  <td className="py-2">
                    <span className="mr-2">{geo.countryCode}</span>
                    <span className="text-gray-600">{geo.countryName}</span>
                  </td>
                  <td className="py-2 text-right font-medium">{geo._count}</td>
                </tr>
              ))}
              {geoDistribution.length === 0 && (
                <tr><td colSpan={2} className="py-4 text-center text-gray-500">Keine Daten</td></tr>
              )}
            </tbody>
          </table>
        </div>

        {/* Platform Distribution */}
        <div className="bg-white rounded-lg border border-gray-200 p-6">
          <h2 className="text-lg font-semibold mb-4">Plattform-Verteilung</h2>
          <div className="space-y-4">
            {platformDistribution.map((p) => {
              const total = platformDistribution.reduce((sum, i) => sum + i._count, 0);
              const pct = total > 0 ? Math.round((p._count / total) * 100) : 0;

              return (
                <div key={p.platform}>
                  <div className="flex justify-between text-sm mb-1">
                    <span className="capitalize font-medium">{p.platform}</span>
                    <span className="text-gray-600">{p._count} ({pct}%)</span>
                  </div>
                  <div className="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div
                      className="h-full bg-blue-500 rounded-full"
                      style={{ width: `${pct}%` }}
                    />
                  </div>
                </div>
              );
            })}
            {platformDistribution.length === 0 && (
              <p className="text-sm text-gray-500">Keine Daten</p>
            )}
          </div>

          <h2 className="text-lg font-semibold mb-4 mt-8">Lizenzen nach Typ</h2>
          <div className="space-y-2">
            {licensesByType.map((t) => (
              <div key={t.type} className="flex justify-between text-sm">
                <span>{t.type === 'pro_plus' ? 'Pro+ Enterprise' : 'Premium'}</span>
                <span className="font-medium">{t._count}</span>
              </div>
            ))}
          </div>

          <h2 className="text-lg font-semibold mb-4 mt-8">Lizenzen nach Plan</h2>
          <div className="space-y-2">
            {licensesByPlan.map((p) => (
              <div key={p.plan} className="flex justify-between text-sm">
                <span className="capitalize">{p.plan}</span>
                <span className="font-medium">{p._count}</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Version Distribution */}
      <div className="bg-white rounded-lg border border-gray-200 p-6">
        <h2 className="text-lg font-semibold mb-4">Plugin-Versions-Verteilung</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {versionDistribution.map((v) => (
            <div key={v.pluginVersion} className="bg-gray-50 rounded p-3 text-center">
              <p className="text-lg font-bold">{v.pluginVersion}</p>
              <p className="text-sm text-gray-600">{v._count} Installation{v._count !== 1 ? 'en' : ''}</p>
            </div>
          ))}
          {versionDistribution.length === 0 && (
            <p className="text-sm text-gray-500 col-span-4 text-center py-4">Keine Daten</p>
          )}
        </div>
      </div>
    </div>
  );
}

function StatCard({ title, value, color }: { title: string; value: number; color: string }) {
  const colorMap: Record<string, string> = {
    green: 'text-green-600',
    red: 'text-red-600',
    gray: 'text-gray-600',
  };

  return (
    <div className="bg-white rounded-lg border border-gray-200 p-6">
      <p className="text-sm text-gray-600 mb-1">{title}</p>
      <p className={`text-3xl font-bold ${colorMap[color] || 'text-gray-900'}`}>{value}</p>
    </div>
  );
}
