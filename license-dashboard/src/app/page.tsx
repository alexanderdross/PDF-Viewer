import { prisma } from '@/lib/prisma';
import { getAuthenticatedUser } from '@/lib/auth';
import { redirect } from 'next/navigation';

export default async function DashboardHome() {
  const user = await getAuthenticatedUser();
  if (!user) redirect('/login');

  const [
    activeLicenses,
    totalInstallations,
    expiringLicenses,
    platformStats,
    recentActivations,
  ] = await Promise.all([
    prisma.license.count({ where: { status: 'active' } }),
    prisma.installation.count({ where: { isActive: true } }),
    prisma.license.count({
      where: {
        status: 'active',
        expiresAt: {
          lte: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000),
          gte: new Date(),
        },
      },
    }),
    prisma.installation.groupBy({
      by: ['platform'],
      where: { isActive: true },
      _count: true,
    }),
    prisma.auditLog.findMany({
      where: { eventType: { in: ['license.activated', 'license.deactivated', 'license.created'] } },
      orderBy: { createdAt: 'desc' },
      take: 10,
      include: { license: { select: { licenseKey: true, type: true, plan: true } } },
    }),
  ]);

  return (
    <div className="min-h-screen">
      {/* Navigation */}
      <nav className="bg-white border-b border-gray-200 px-6 py-4">
        <div className="max-w-7xl mx-auto flex items-center justify-between">
          <h1 className="text-xl font-bold text-gray-900">PDF License Dashboard</h1>
          <div className="flex items-center gap-4 text-sm text-gray-600">
            <span>{user}</span>
            <a href="/api/admin/auth/logout" className="text-red-600 hover:text-red-800">Logout</a>
          </div>
        </div>
      </nav>

      {/* Sidebar + Content */}
      <div className="max-w-7xl mx-auto flex">
        {/* Sidebar */}
        <aside className="w-56 min-h-screen bg-white border-r border-gray-200 py-6 px-4">
          <nav className="space-y-1">
            {[
              { href: '/', label: 'Dashboard', active: true },
              { href: '/licenses', label: 'Lizenzen' },
              { href: '/installations', label: 'Installationen' },
              { href: '/stats', label: 'Statistiken' },
              { href: '/audit-log', label: 'Audit-Log' },
              { href: '/settings', label: 'Einstellungen' },
            ].map((item) => (
              <a
                key={item.href}
                href={item.href}
                className={`block px-3 py-2 rounded text-sm ${
                  item.active
                    ? 'bg-blue-50 text-blue-700 font-medium'
                    : 'text-gray-700 hover:bg-gray-50'
                }`}
              >
                {item.label}
              </a>
            ))}
          </nav>
        </aside>

        {/* Main Content */}
        <main className="flex-1 p-8">
          {/* KPI Cards */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <KpiCard title="Aktive Lizenzen" value={activeLicenses} />
            <KpiCard title="Aktive Installationen" value={totalInstallations} />
            <KpiCard title="Umsatz (Monat)" value="$0" subtitle="Stripe nicht verbunden" />
            <KpiCard title="Ablaufend (30 Tage)" value={expiringLicenses} alert={expiringLicenses > 0} />
          </div>

          {/* Platform Distribution */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div className="bg-white rounded-lg border border-gray-200 p-6">
              <h2 className="text-lg font-semibold mb-4">Installationen nach Plattform</h2>
              <div className="space-y-3">
                {platformStats.map((stat) => (
                  <div key={stat.platform} className="flex items-center justify-between">
                    <span className="text-sm font-medium capitalize">{stat.platform}</span>
                    <span className="text-sm text-gray-600">{stat._count}</span>
                  </div>
                ))}
                {platformStats.length === 0 && (
                  <p className="text-sm text-gray-500">Keine Installationen vorhanden.</p>
                )}
              </div>
            </div>

            <div className="bg-white rounded-lg border border-gray-200 p-6">
              <h2 className="text-lg font-semibold mb-4">Letzte Aktivitaet</h2>
              <div className="space-y-2">
                {recentActivations.map((log) => (
                  <div key={log.id} className="flex items-center justify-between text-sm">
                    <span className="text-gray-700">
                      {log.eventType.replace('license.', '')} -{' '}
                      {log.license ? log.license.type : 'N/A'}
                    </span>
                    <span className="text-gray-500">
                      {new Date(log.createdAt).toLocaleDateString('de-DE')}
                    </span>
                  </div>
                ))}
                {recentActivations.length === 0 && (
                  <p className="text-sm text-gray-500">Keine Aktivitaeten vorhanden.</p>
                )}
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}

function KpiCard({
  title,
  value,
  subtitle,
  alert,
}: {
  title: string;
  value: number | string;
  subtitle?: string;
  alert?: boolean;
}) {
  return (
    <div className={`bg-white rounded-lg border p-6 ${alert ? 'border-amber-300' : 'border-gray-200'}`}>
      <p className="text-sm text-gray-600 mb-1">{title}</p>
      <p className={`text-3xl font-bold ${alert ? 'text-amber-600' : 'text-gray-900'}`}>{value}</p>
      {subtitle && <p className="text-xs text-gray-400 mt-1">{subtitle}</p>}
    </div>
  );
}
