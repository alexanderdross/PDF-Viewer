import { lazy, Suspense } from "react";
import { Toaster } from "@/components/ui/toaster";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { PageLoadingFallback } from "@/components/ui/PageLoadingFallback";

// Eager load the homepage for best LCP
import Index from "./pages/Index";

// Lazy load all other routes for code splitting
const Documentation = lazy(() => import("./pages/Documentation"));
const Examples = lazy(() => import("./pages/Examples"));
const Pro = lazy(() => import("./pages/Pro"));
const Changelog = lazy(() => import("./pages/Changelog"));
const Cart = lazy(() => import("./pages/Cart"));
const WordpressPdfViewer = lazy(() => import("./pages/WordpressPdfViewer"));
const DrupalPdfViewer = lazy(() => import("./pages/DrupalPdfViewer"));
const NextjsPdfViewer = lazy(() => import("./pages/NextjsPdfViewer"));
const PdfGrid = lazy(() => import("./pages/PdfGrid"));
const Enterprise = lazy(() => import("./pages/Enterprise"));
const Contact = lazy(() => import("./pages/Contact"));
const NotFound = lazy(() => import("./pages/NotFound"));

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
      <Toaster />
      <BrowserRouter>
        <Suspense fallback={<PageLoadingFallback />}>
          <Routes>
            <Route path="/" element={<Index />} />
            <Route path="/documentation/" element={<Documentation />} />
            <Route path="/examples/" element={<Examples />} />
            <Route path="/pro/" element={<Pro />} />
            <Route path="/changelog/" element={<Changelog />} />
            <Route path="/cart/" element={<Cart />} />
            <Route path="/wordpress-pdf-viewer/" element={<WordpressPdfViewer />} />
            <Route path="/drupal-pdf-viewer/" element={<DrupalPdfViewer />} />
            <Route path="/nextjs-pdf-viewer/" element={<NextjsPdfViewer />} />
            <Route path="/pdf-grid/" element={<PdfGrid />} />
            <Route path="/enterprise/" element={<Enterprise />} />
            <Route path="/contact/" element={<Contact />} />
            {/* ADD ALL CUSTOM ROUTES ABOVE THE CATCH-ALL "*" ROUTE */}
            <Route path="*" element={<NotFound />} />
          </Routes>
        </Suspense>
      </BrowserRouter>
  </QueryClientProvider>
);

export default App;
