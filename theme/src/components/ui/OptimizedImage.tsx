import { useState } from "react";
import { cn } from "@/lib/utils";

interface ImageSource {
  srcWebp?: string;
  srcFallback: string;
}

interface OptimizedImageProps {
  src: ImageSource | string;
  alt: string;
  width: number;
  height: number;
  className?: string;
  loading?: "lazy" | "eager";
  decoding?: "async" | "sync" | "auto";
  fetchPriority?: "high" | "low" | "auto";
  sizes?: string;
  srcSet?: string;
  onLoad?: () => void;
  onError?: () => void;
}

/**
 * OptimizedImage component with WebP support, lazy loading, and CLS optimization.
 * 
 * Usage:
 * - For images with WebP version: pass { srcWebp: "path.webp", srcFallback: "path.png" }
 * - For images without WebP: pass just the string path
 * - Above the fold images: set loading="eager" and fetchPriority="high"
 * - Below the fold images: default loading="lazy" is used
 */
export function OptimizedImage({
  src,
  alt,
  width,
  height,
  className,
  loading = "lazy",
  decoding = "async",
  fetchPriority = "auto",
  sizes,
  srcSet,
  onLoad,
  onError,
}: OptimizedImageProps) {
  const [hasError, setHasError] = useState(false);

  const handleError = () => {
    setHasError(true);
    onError?.();
  };

  // Handle both string and object src
  const isObjectSrc = typeof src === "object";
  const webpSrc = isObjectSrc ? src.srcWebp : undefined;
  const fallbackSrc = isObjectSrc ? src.srcFallback : src;

  // Aspect ratio for placeholder
  const aspectRatio = `${width} / ${height}`;

  // If WebP source is provided, use picture element
  if (webpSrc && !hasError) {
    return (
      <picture>
        <source srcSet={webpSrc} type="image/webp" />
        <img
          src={fallbackSrc}
          alt={alt}
          width={width}
          height={height}
          loading={loading}
          decoding={decoding}
          fetchPriority={fetchPriority}
          className={cn("max-w-full h-auto", className)}
          style={{ aspectRatio }}
          sizes={sizes}
          srcSet={srcSet}
          onLoad={onLoad}
          onError={handleError}
        />
      </picture>
    );
  }

  // Standard img tag (no WebP or WebP failed)
  return (
    <img
      src={fallbackSrc}
      alt={alt}
      width={width}
      height={height}
      loading={loading}
      decoding={decoding}
      fetchPriority={fetchPriority}
      className={cn("max-w-full h-auto", className)}
      style={{ aspectRatio }}
      sizes={sizes}
      srcSet={srcSet}
      onLoad={onLoad}
      onError={handleError}
    />
  );
}

/**
 * Logo component optimized for header/footer usage
 * Now serves WebP with PNG fallback for maximum compression
 */
interface LogoImageProps {
  size?: number;
  className?: string;
  loading?: "lazy" | "eager";
}

export function LogoImage({ 
  size = 40, 
  className,
  loading = "eager" 
}: LogoImageProps) {
  return (
    <OptimizedImage
      src={{ srcWebp: "/logo.webp", srcFallback: "/logo.png" }}
      alt="PDF Embed & SEO Optimize logo"
      width={size}
      height={size}
      loading={loading}
      decoding="async"
      fetchPriority={loading === "eager" ? "high" : "auto"}
      className={cn("rounded-lg", className)}
    />
  );
}

/**
 * OG Image component with responsive srcset for social sharing
 * Serves WebP with PNG fallback at multiple sizes
 */
interface OGImageProps {
  className?: string;
}

export function OGImage({ className }: OGImageProps) {
  return (
    <OptimizedImage
      src={{ srcWebp: "/og-image.webp", srcFallback: "/og-image.png" }}
      alt="PDF Embed & SEO Optimize - Free plugin for WordPress and Drupal"
      width={1200}
      height={630}
      loading="lazy"
      decoding="async"
      sizes="(max-width: 600px) 600px, (max-width: 900px) 900px, 1200px"
      srcSet="/og-image.png 600w, /og-image.png 900w, /og-image.png 1200w"
      className={className}
    />
  );
}
