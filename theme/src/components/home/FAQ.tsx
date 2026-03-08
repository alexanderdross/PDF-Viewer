import { useEffect, useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";
import { HelpCircle } from "lucide-react";

// Generate URL-friendly slug from question text
const generateSlug = (text: string) => 
  text.toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .substring(0, 50);

const faqs = [
  {
    question: "Is this PDF plugin really free?",
    answer: "Yes! PDF Embed & SEO Optimize is completely free and open source for both WordPress and Drupal. You get all features—Mozilla PDF.js viewer, full SEO optimization, JSON-LD schema, social sharing cards, and view statistics—at no cost."
  },
  {
    question: "Is the plugin available for both WordPress and Drupal?",
    answer: "Yes! PDF Embed & SEO Optimize is available as a WordPress plugin and a Drupal module. Both versions include the same core features: Mozilla PDF.js viewer, SEO optimization, JSON-LD schema, and social sharing previews."
  },
  {
    question: "How does this plugin help my PDFs get discovered by AI tools like ChatGPT?",
    answer: "The plugin optimizes your PDFs for Generative Engine Optimization (GEO). Your content is structured with JSON-LD schema, semantic HTML, and rich metadata that AI language models can easily understand, index, and cite—increasing the chances of your PDFs appearing in AI-generated responses."
  },
  {
    question: "What is Generative Engine Optimization (GEO)?",
    answer: "GEO is an advanced SEO strategy focused on making your content discoverable by AI and Large Language Models (LLMs). It goes beyond traditional SEO by ensuring your PDFs have clear metadata, structured data, and semantic markup that AI models like ChatGPT, Perplexity, and Google AI can process."
  },
  {
    question: "Will my PDFs actually appear in ChatGPT or Perplexity responses?",
    answer: "When your PDFs are GEO-optimized with our plugin, they're more likely to be discovered and cited by AI tools. If a user asks about a topic covered in your PDF, the AI can quote or summarize the relevant information and cite your document as a source."
  },
  {
    question: "Will this plugin slow down my website?",
    answer: "Not at all. PDFs load via AJAX only when visitors view them, keeping your pages fast. The bundled PDF.js viewer is optimized for performance, and thumbnails are auto-generated and cached in your media library."
  },
  {
    question: "Does this PDF plugin work with my theme?",
    answer: "Yes! The plugin works with any WordPress theme or Drupal theme and includes light/dark theme options for the viewer. On WordPress, it's compatible with page builders like Elementor, Divi, and Gutenberg. On Drupal, it works with Layout Builder and Paragraphs."
  },
  {
    question: "How does PDF SEO optimization work?",
    answer: "Each PDF becomes a proper webpage with its own URL, title tag, meta description, and JSON-LD DigitalDocument schema. On WordPress, the plugin integrates with Yoast SEO. Both platforms automatically add your PDFs to an XML sitemap."
  },
  {
    question: "Can I control PDF downloads?",
    answer: "Yes! You have per-PDF toggle controls for both print and download options. The plugin also hides direct file URLs via AJAX loading, making it harder for people to share or scrape your files."
  },
  {
    question: "How do PDF social sharing previews work?",
    answer: "The plugin automatically generates OpenGraph and Twitter Card meta tags for your PDFs, using a thumbnail from your PDF's first page. When someone shares your PDF link, it displays a proper preview image and description."
  },
  {
    question: "What's the PDF Archive page?",
    answer: "The plugin automatically creates a page at /pdf/ that lists all your PDF documents with CollectionPage schema. On WordPress, you can also use the [pdf_viewer_sitemap] shortcode to display this list anywhere on your site."
  },
  {
    question: "Does the PDF viewer work on mobile phones?",
    answer: "Absolutely. The Mozilla PDF.js viewer provides consistent rendering across all devices and browsers, unlike native PDF handling which varies wildly between mobile devices."
  },
  {
    question: "What are the system requirements?",
    answer: "For WordPress: version 5.0+ and PHP 7.4+. For Drupal: version 9+ or 10+ and PHP 8.0+. Both versions are actively maintained and receive regular updates."
  },
  {
    question: "How do I install the Drupal module?",
    answer: "You can install via Composer with 'composer require drupal/pdf_embed_seo_optimize' or download directly from Drupal.org. Then enable the module in Extend and configure settings at Configuration > Media > PDF Embed."
  }
];

// Generate FAQ Page structured data for GEO/AEO
const generateFAQSchema = () => ({
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": faqs.map(faq => ({
    "@type": "Question",
    "name": faq.question,
    "acceptedAnswer": {
      "@type": "Answer",
      "text": faq.answer
    }
  }))
});

export const FAQ = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const [openItem, setOpenItem] = useState<string | undefined>(undefined);

  // Generate slugs for all FAQs
  const faqSlugs = faqs.map(faq => generateSlug(faq.question));

  // Handle hash on mount and hash changes
  useEffect(() => {
    const hash = location.hash.replace('#', '');
    if (hash) {
      const index = faqSlugs.findIndex(slug => slug === hash);
      if (index !== -1) {
        setOpenItem(`item-${index}`);
        // Scroll to the FAQ section
        setTimeout(() => {
          document.getElementById(hash)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
      }
    }
  }, [location.hash, faqSlugs]);

  // Update URL when accordion value changes
  const handleValueChange = (value: string | undefined) => {
    setOpenItem(value);
    if (value) {
      const index = parseInt(value.replace('item-', ''));
      const slug = faqSlugs[index];
      navigate(`${location.pathname}#${slug}`, { replace: true });
    } else {
      navigate(location.pathname, { replace: true });
    }
  };

  // Inject FAQ schema into the page head for SEO/GEO/AEO
  useEffect(() => {
    const existingScript = document.getElementById('faq-schema');
    if (existingScript) {
      existingScript.remove();
    }
    
    const script = document.createElement('script');
    script.id = 'faq-schema';
    script.type = 'application/ld+json';
    script.textContent = JSON.stringify(generateFAQSchema());
    document.head.appendChild(script);
    
    return () => {
      const scriptToRemove = document.getElementById('faq-schema');
      if (scriptToRemove) {
        scriptToRemove.remove();
      }
    };
  }, []);

  return (
    <section 
      id="faq" 
      className="py-16 lg:py-24 bg-card" 
      aria-labelledby="faq-heading"
      itemScope 
      itemType="https://schema.org/FAQPage"
    >
      <div className="container mx-auto px-4 lg:px-8">
        <div className="max-w-3xl mx-auto">
          {/* Header */}
          <header className="text-center mb-12 animate-fade-in">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
              <HelpCircle className="w-4 h-4" aria-hidden="true" />
              <span>Common Questions</span>
            </div>
            <h2 id="faq-heading" className="text-3xl md:text-4xl font-bold mb-4">
              Frequently Asked Questions
            </h2>
            <p className="text-lg text-muted-foreground">
              Everything you need to know before getting started
            </p>
          </header>

          {/* Accordion with semantic markup */}
          <Accordion 
            type="single" 
            collapsible 
            className="w-full space-y-3"
            value={openItem}
            onValueChange={handleValueChange}
          >
            {faqs.map((faq, index) => (
              <AccordionItem 
                key={index} 
                id={faqSlugs[index]}
                value={`item-${index}`}
                className="bg-background border border-border rounded-xl px-6 data-[state=open]:shadow-soft"
                itemScope
                itemProp="mainEntity"
                itemType="https://schema.org/Question"
              >
                <AccordionTrigger 
                  className="text-left font-semibold hover:no-underline py-5"
                  title={`Click to expand: ${faq.question}`}
                  aria-label={`Question: ${faq.question}. Click to expand answer.`}
                >
                  <span itemProp="name">{faq.question}</span>
                </AccordionTrigger>
                <AccordionContent 
                  className="text-muted-foreground pb-5"
                  itemScope
                  itemProp="acceptedAnswer"
                  itemType="https://schema.org/Answer"
                >
                  <span itemProp="text">{faq.answer}</span>
                </AccordionContent>
              </AccordionItem>
            ))}
          </Accordion>

          {/* Contact CTA */}
          <div className="mt-12 text-center animate-fade-in">
            <p className="text-muted-foreground">
              Still have questions?{" "}
              <a 
                href="https://wordpress.org/support/plugin/pdf-embed-seo-optimize/"
                target="_blank" 
                rel="noopener noreferrer"
                className="text-primary hover:underline font-medium"
                aria-label="Visit our support forum on WordPress.org (opens in new tab)"
              >
                Visit our support forum
              </a>
            </p>
          </div>
        </div>
      </div>
    </section>
  );
};
