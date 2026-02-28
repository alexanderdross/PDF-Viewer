const rateLimitMap = new Map<string, { count: number; resetAt: number }>();

// Clean up expired entries every 5 minutes
setInterval(() => {
  const now = Date.now();
  for (const [key, value] of rateLimitMap.entries()) {
    if (value.resetAt < now) {
      rateLimitMap.delete(key);
    }
  }
}, 5 * 60 * 1000);

interface RateLimitConfig {
  maxRequests: number;
  windowMs: number;
}

interface RateLimitResult {
  allowed: boolean;
  remaining: number;
  resetAt: number;
  retryAfter?: number;
}

const PRESETS: Record<string, RateLimitConfig> = {
  api: { maxRequests: 60, windowMs: 60 * 1000 },           // 60/min per IP
  activate: { maxRequests: 10, windowMs: 60 * 60 * 1000 }, // 10/hour per key
  check: { maxRequests: 1000, windowMs: 24 * 60 * 60 * 1000 }, // 1000/day per key
};

export function checkRateLimit(identifier: string, preset: keyof typeof PRESETS): RateLimitResult {
  const config = PRESETS[preset];
  if (!config) {
    return { allowed: true, remaining: 999, resetAt: 0 };
  }

  const key = `${preset}:${identifier}`;
  const now = Date.now();
  const entry = rateLimitMap.get(key);

  if (!entry || entry.resetAt < now) {
    rateLimitMap.set(key, { count: 1, resetAt: now + config.windowMs });
    return { allowed: true, remaining: config.maxRequests - 1, resetAt: now + config.windowMs };
  }

  entry.count++;

  if (entry.count > config.maxRequests) {
    const retryAfter = Math.ceil((entry.resetAt - now) / 1000);
    return { allowed: false, remaining: 0, resetAt: entry.resetAt, retryAfter };
  }

  return { allowed: true, remaining: config.maxRequests - entry.count, resetAt: entry.resetAt };
}

export function rateLimitResponse(result: RateLimitResult): Response {
  return new Response(
    JSON.stringify({ error: 'rate_limit_exceeded', message: 'Too many requests. Please try again later.' }),
    {
      status: 429,
      headers: {
        'Content-Type': 'application/json',
        'Retry-After': String(result.retryAfter || 60),
        'X-RateLimit-Remaining': String(result.remaining),
        'X-RateLimit-Reset': new Date(result.resetAt).toISOString(),
      },
    },
  );
}
