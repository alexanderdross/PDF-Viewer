import { useEffect } from "react";
import { Upload, Settings, Rocket } from "lucide-react";

const steps = [
  {
    step: "01",
    icon: Upload,
    title: "Install the Plugin",
    description: "Add it to your WordPress site in just a few clicks — it's completely free and takes less than a minute.",
  },
  {
    step: "02",
    icon: Settings,
    title: "Upload Your Documents",
    description: "Add your PDFs and give them titles and descriptions. No technical knowledge needed.",
  },
  {
    step: "03",
    icon: Rocket,
    title: "Start Sharing",
    description: "Your documents are now ready to be found on Google and shared on social media with professional previews.",
  },
];

// Generate HowTo schema for GEO/AEO optimization
const generateHowToSchema = () => ({
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "How to Set Up PDF Embed & SEO Optimize WordPress Plugin",
  "description": "Learn how to install and configure the PDF Embed & SEO Optimize plugin to make your WordPress PDFs SEO-friendly in 3 easy steps.",
  "totalTime": "PT5M",
  "step": steps.map((step, index) => ({
    "@type": "HowToStep",
    "position": index + 1,
    "name": step.title,
    "text": step.description
  }))
});

export function HowItWorks() {
  // Inject HowTo schema into the page head for GEO/AEO
  useEffect(() => {
    const existingScript = document.getElementById('howto-schema');
    if (existingScript) {
      existingScript.remove();
    }
    
    const script = document.createElement('script');
    script.id = 'howto-schema';
    script.type = 'application/ld+json';
    script.textContent = JSON.stringify(generateHowToSchema());
    document.head.appendChild(script);
    
    return () => {
      const scriptToRemove = document.getElementById('howto-schema');
      if (scriptToRemove) {
        scriptToRemove.remove();
      }
    };
  }, []);

  return (
    <section 
      className="py-20 lg:py-32 bg-card" 
      aria-labelledby="how-it-works-heading"
      itemScope
      itemType="https://schema.org/HowTo"
    >
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-16">
          <h2 id="how-it-works-heading" className="text-3xl md:text-4xl font-bold mb-6" itemProp="name">
            Up and Running in 3 Easy Steps
          </h2>
          <p className="text-lg text-muted-foreground" itemProp="description">
            No technical skills required. If you can use WordPress, you can use this plugin.
          </p>
        </header>

        <div className="max-w-4xl mx-auto">
          <div className="relative">
            {/* Connection Line - decorative */}
            <div className="hidden md:block absolute top-24 left-1/2 -translate-x-1/2 w-2/3 h-0.5 bg-gradient-to-r from-primary via-accent to-primary" aria-hidden="true" />

            <ol className="grid md:grid-cols-3 gap-8 list-none" role="list">
              {steps.map((item, index) => (
                <li
                  key={item.step}
                  className="relative text-center animate-fade-in"
                  style={{ animationDelay: `${index * 0.2}s` }}
                  itemScope
                  itemProp="step"
                  itemType="https://schema.org/HowToStep"
                >
                  <meta itemProp="position" content={String(index + 1)} />
                  <div className="relative z-10 w-20 h-20 mx-auto mb-6 gradient-hero rounded-2xl flex items-center justify-center shadow-glow">
                    <item.icon className="w-8 h-8 text-primary-foreground" aria-hidden="true" />
                  </div>
                  <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-2 text-6xl font-bold text-muted/30" aria-hidden="true">
                    {item.step}
                  </div>
                  <h3 className="text-xl font-semibold mb-3" itemProp="name">{item.title}</h3>
                  <p className="text-muted-foreground" itemProp="text">{item.description}</p>
                </li>
              ))}
            </ol>
          </div>
        </div>
      </div>
    </section>
  );
}
