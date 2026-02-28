import jwt from 'jsonwebtoken';
import bcrypt from 'bcryptjs';
import { cookies } from 'next/headers';

const JWT_SECRET = process.env.JWT_SECRET || 'development-secret-change-me';
const TOKEN_EXPIRY = '24h';
const COOKIE_NAME = 'auth_token';

interface JwtPayload {
  email: string;
  iat: number;
  exp: number;
}

export async function verifyCredentials(email: string, password: string): Promise<boolean> {
  const adminEmail = process.env.ADMIN_EMAIL;
  const adminPasswordHash = process.env.ADMIN_PASSWORD_HASH;

  if (!adminEmail || !adminPasswordHash) return false;
  if (email !== adminEmail) return false;

  return bcrypt.compare(password, adminPasswordHash);
}

export function createToken(email: string): string {
  return jwt.sign({ email }, JWT_SECRET, { expiresIn: TOKEN_EXPIRY });
}

export function verifyToken(token: string): JwtPayload | null {
  try {
    return jwt.verify(token, JWT_SECRET) as JwtPayload;
  } catch {
    return null;
  }
}

export async function getAuthenticatedUser(): Promise<string | null> {
  const cookieStore = await cookies();
  const token = cookieStore.get(COOKIE_NAME)?.value;
  if (!token) return null;

  const payload = verifyToken(token);
  return payload?.email || null;
}

export async function hashPassword(password: string): Promise<string> {
  return bcrypt.hash(password, 12);
}
