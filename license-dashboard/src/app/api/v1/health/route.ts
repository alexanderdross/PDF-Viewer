import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import type { HealthResponse } from '@/types';

export async function GET() {
  let dbStatus: 'connected' | 'disconnected' = 'disconnected';

  try {
    await prisma.$queryRaw`SELECT 1`;
    dbStatus = 'connected';
  } catch {
    dbStatus = 'disconnected';
  }

  const response: HealthResponse = {
    status: dbStatus === 'connected' ? 'ok' : 'error',
    version: '0.1.0',
    database: dbStatus,
    timestamp: new Date().toISOString(),
  };

  return NextResponse.json(response, {
    status: dbStatus === 'connected' ? 200 : 503,
  });
}
