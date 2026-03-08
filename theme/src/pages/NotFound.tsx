import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { FileQuestion, Home, ArrowLeft } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";

const NotFound = () => {

  return (
    <Layout>
      <SEOHead
        title="Page Not Found – PDF Embed & SEO Optimize"
        description="The page you're looking for doesn't exist. Return to the homepage to learn about our free WordPress PDF plugin."
        noindex={true}
      />
      
      <section className="py-24 lg:py-32" aria-labelledby="notfound-heading">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-2xl mx-auto text-center">
            <div className="w-24 h-24 mx-auto mb-8 gradient-hero rounded-2xl flex items-center justify-center shadow-glow animate-float" aria-hidden="true">
              <FileQuestion className="w-12 h-12 text-primary-foreground" />
            </div>
            
            <h1 id="notfound-heading" className="text-6xl font-bold mb-4 text-gradient">404</h1>
            <h2 className="text-2xl font-semibold mb-4">Page Not Found</h2>
            <p className="text-muted-foreground text-lg mb-8">
              Oops! The page you're looking for doesn't exist or has been moved.
            </p>
            
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Button size="lg" className="gradient-hero shadow-glow" asChild>
                <Link to="/" className="gap-2" title="Return to the homepage" aria-label="Go back to the homepage">
                  <Home className="w-5 h-5" aria-hidden="true" />
                  Go Home
                </Link>
              </Button>
              <Button size="lg" variant="outline" asChild>
                <Link to="/documentation/" className="gap-2" title="Read the plugin documentation" aria-label="View plugin documentation">
                  <ArrowLeft className="w-5 h-5" aria-hidden="true" />
                  Documentation
                </Link>
              </Button>
            </div>
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default NotFound;
