import { useEffect, useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

// Generate URL-friendly slug from question text
const generateSlug = (text: string) =>
  text.toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .substring(0, 50);

const faqs = [
  {
    question: "What's included in the Pro plan?",
    answer: "The Pro plan ($49/year) includes all essential Pro features for 1 site: Analytics Dashboard, Password Protection, Reading Progress, XML Sitemap, Categories & Tags, CSV/JSON Export, and priority email support with 1 year of updates.",
  },
  {
    question: "What is the difference between the plans?",
    answer: "Pro ($49/year, 1 site) includes analytics, password protection, reading progress, XML sitemap, categories/tags, and CSV/JSON export. Pro+ ($199/year, unlimited sites) adds role-based access, bulk import, full REST API, and white-label options. Enterprise (from $2,500/year) adds dedicated support, SLA, SSO, and compliance documentation.",
  },
  {
    question: "Can I upgrade between plans?",
    answer: "Yes! You can upgrade at any time. We'll prorate your existing license and only charge the difference. Contact our support team and we'll handle the upgrade within 24 hours.",
  },
  {
    question: "What happens when my license expires?",
    answer: "Your Pro features continue working indefinitely—we don't disable anything. However, you won't receive updates or priority support. You can renew at any time to restore access to updates and support.",
  },
  {
    question: "Do you offer refunds?",
    answer: "Absolutely. We offer a 30-day money-back guarantee, no questions asked. If Pro doesn't meet your needs, contact support for a full refund within 30 days of purchase.",
  },
  {
    question: "Can I use Pro on staging/development sites?",
    answer: "Yes! Staging, development, and localhost sites don't count against your license limit. Only live production sites require activation. This makes it easy to test before going live.",
  },
  {
    question: "Is the Lifetime license really one-time payment?",
    answer: "Yes. The Lifetime Unlimited license ($799 one-time) is a perpetual license for all Pro+ features on unlimited sites. This includes all future feature updates — no recurring fees, ever. The price may increase as the product grows, so current pricing is locked for early buyers.",
  },
  {
    question: "What payment methods do you accept?",
    answer: "We accept all major credit cards (Visa, MasterCard, American Express, Discover) and PayPal. All payments are securely processed through Stripe.",
  },
  {
    question: "How do I receive Pro after purchase?",
    answer: "Immediately after purchase, you'll receive an email with your license key and download link. You can also access these from your account dashboard at any time.",
  },
  {
    question: "Do you offer Enterprise plans for regulated industries?",
    answer: "Yes. We offer dedicated Enterprise plans for Pharmaceutical, Life Sciences, Healthcare, Finance, and Legal organizations. These include SLA agreements, compliance documentation, and dedicated support.",
    link: { text: "See Enterprise plans →", href: "/enterprise/" },
  },
  {
    question: "Can you sign a Data Processing Agreement (DPA)?",
    answer: "Yes, for Enterprise plan customers. Contact us through our contact form with your DPA requirements.",
    link: { text: "Contact us →", href: "/contact/" },
  },
  {
    question: "Do you offer discounts for non-profits or education?",
    answer: "Yes — 40% off for registered non-profits and educational institutions. Contact support with proof of status.",
  },
  {
    question: "Does Pro work with WordPress, Drupal, and React/Next.js?",
    answer: "Yes! PDF Embed & SEO Optimize Pro is available for WordPress (5.8+), Drupal (10/11), and React 18+/Next.js 13+. One license covers all platforms. The React premium package is available as @pdf-embed-seo/react-premium on npm.",
  },
  {
    question: "What kind of support is included?",
    answer: "Pro includes priority email support. Pro+ includes priority email + chat support. Enterprise includes a dedicated account manager. All licenses include 1 year of updates.",
  },
  {
    question: "Who owns the intellectual property (IP)?",
    answer: "All intellectual property rights, including but not limited to the source code, design, documentation, trademarks, and trade secrets of PDF Embed & SEO Optimize remain the exclusive property of Dross:Media. This applies to both the Free and Pro versions. Your license grants you the right to use the software — it does not transfer ownership. Any modifications, derivative works, or customizations you create based on our code do not affect Dross:Media's underlying IP ownership. Redistribution of modified versions must retain original copyright notices and may not misrepresent the origin of the software.",
  },
];

export function ProFAQ() {
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

  useEffect(() => {
    // Inject FAQPage schema
    const schema = {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      mainEntity: faqs.map((faq) => ({
        "@type": "Question",
        name: faq.question,
        acceptedAnswer: {
          "@type": "Answer",
          text: faq.answer,
        },
      })),
    };

    const script = document.createElement("script");
    script.type = "application/ld+json";
    script.id = "pro-faq-schema";
    script.textContent = JSON.stringify(schema);

    const existing = document.getElementById("pro-faq-schema");
    if (existing) existing.remove();
    document.head.appendChild(script);

    return () => {
      const element = document.getElementById("pro-faq-schema");
      if (element) element.remove();
    };
  }, []);

  return (
    <section className="py-20 lg:py-32 bg-card" aria-labelledby="pro-faq-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-12">
          <h2 id="pro-faq-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Frequently Asked Questions
          </h2>
          <p className="text-lg text-muted-foreground">
            Everything you need to know about licensing, features, and upgrades
          </p>
        </header>

        <div className="max-w-3xl mx-auto">
          <Accordion
            type="single"
            collapsible
            className="space-y-4"
            value={openItem}
            onValueChange={handleValueChange}
          >
            {faqs.map((faq, index) => (
              <AccordionItem
                key={faq.question}
                id={faqSlugs[index]}
                value={`item-${index}`}
                className="bg-background border border-border rounded-xl px-6 data-[state=open]:border-primary/30 transition-colors"
                itemScope
                itemProp="mainEntity"
                itemType="https://schema.org/Question"
              >
                <AccordionTrigger
                  className="text-left font-medium text-foreground hover:text-primary py-4"
                  itemProp="name"
                >
                  {faq.question}
                </AccordionTrigger>
                <AccordionContent
                  className="text-muted-foreground pb-4"
                  itemScope
                  itemProp="acceptedAnswer"
                  itemType="https://schema.org/Answer"
                >
                  <span itemProp="text">{faq.answer}</span>
                  {"link" in faq && faq.link && (
                    <Link to={faq.link.href} className="block mt-2 text-primary hover:underline font-medium">
                      {faq.link.text}
                    </Link>
                  )}
                </AccordionContent>
              </AccordionItem>
            ))}
          </Accordion>
        </div>
      </div>
    </section>
  );
}
