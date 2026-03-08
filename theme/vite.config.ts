import { defineConfig } from "vite";
import react from "@vitejs/plugin-react-swc";
import path from "path";
import { componentTagger } from "lovable-tagger";
import { VitePWA } from "vite-plugin-pwa";

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => ({
  server: {
    host: "::",
    port: 8080,
    hmr: {
      overlay: false,
    },
  },
  plugins: [
    react(),
    mode === "development" && componentTagger(),
    VitePWA({
      registerType: "autoUpdate",
      includeAssets: ["favicon.ico", "favicon.png", "og-image.png"],
      manifest: {
        name: "PDF Embed & SEO Optimize",
        short_name: "PDF Embed",
        description: "Free plugin for SEO-friendly PDF embedding with GEO optimization for WordPress and Drupal",
        theme_color: "#6366f1",
        background_color: "#0a0a0b",
        display: "standalone",
        orientation: "portrait",
        scope: "/",
        start_url: "/",
        icons: [
          {
            src: "/pwa-192x192.png",
            sizes: "192x192",
            type: "image/png",
          },
          {
            src: "/pwa-512x512.png",
            sizes: "512x512",
            type: "image/png",
          },
          {
            src: "/pwa-512x512.png",
            sizes: "512x512",
            type: "image/png",
            purpose: "maskable",
          },
        ],
      },
      workbox: {
        // Cache all static assets including self-hosted fonts
        globPatterns: ["**/*.{js,css,html,ico,png,svg,woff,woff2,ttf,eot,xml,txt}"],

        // Don't serve the SPA shell for SEO-critical static files
        // (otherwise the app loads and React Router shows a 404 page)
        navigateFallbackDenylist: [
          /^\/sitemap\.xml$/,
          /^\/pdf\/sitemap\.xml$/,
          /^\/robots\.txt$/,
        ],

        // Ensure fonts are precached for offline use
        maximumFileSizeToCacheInBytes: 3 * 1024 * 1024, // 3MB for font files
      },
    }),
  ].filter(Boolean),
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },
}));
