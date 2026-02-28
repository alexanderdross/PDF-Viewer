import { Reader } from '@maxmind/geoip2-node';
import path from 'path';

let reader: Reader | null = null;

async function getReader(): Promise<Reader | null> {
  if (reader) return reader;

  const dbPath = process.env.GEOIP_DB_PATH || path.join(process.cwd(), 'data', 'GeoLite2-City.mmdb');

  try {
    reader = await Reader.open(dbPath);
    return reader;
  } catch {
    console.warn(`[GeoIP] Could not open database at ${dbPath}. Geo-IP lookups will be unavailable.`);
    return null;
  }
}

export interface GeoLookupResult {
  countryCode: string;
  countryName: string;
  region: string | null;
  city: string | null;
  latitude: number | null;
  longitude: number | null;
  continent: string | null;
  timezone: string | null;
}

export async function lookupIp(ip: string): Promise<GeoLookupResult | null> {
  const r = await getReader();
  if (!r) return null;

  try {
    const response = r.city(ip);

    return {
      countryCode: response.country?.isoCode || 'XX',
      countryName: response.country?.names?.en || 'Unknown',
      region: response.subdivisions?.[0]?.names?.en || null,
      city: response.city?.names?.en || null,
      latitude: response.location?.latitude || null,
      longitude: response.location?.longitude || null,
      continent: response.continent?.names?.en || null,
      timezone: response.location?.timeZone || null,
    };
  } catch {
    return null;
  }
}

export function anonymizeIp(ip: string): string {
  if (ip.includes(':')) {
    // IPv6: zero out last 80 bits
    const parts = ip.split(':');
    if (parts.length >= 4) {
      return parts.slice(0, 4).join(':') + '::';
    }
    return ip;
  }

  // IPv4: zero out last octet
  const parts = ip.split('.');
  if (parts.length === 4) {
    parts[3] = '0';
    return parts.join('.');
  }
  return ip;
}
