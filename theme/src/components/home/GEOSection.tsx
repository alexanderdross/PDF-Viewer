import { Bot, MessageSquare, Search, Quote, FileText, Sparkles } from "lucide-react";

const examples = [
  {
    icon: Quote,
    title: "Direct Citations",
    aiResponse: '"According to the 2024 Annual Report from YourCompany..."',
    description: "AI tools cite your PDF as an authoritative source when answering user questions.",
  },
  {
    icon: MessageSquare,
    title: "Content Summarization",
    aiResponse: '"The whitepaper highlights three key findings: improved efficiency, cost reduction, and..."',
    description: "LLMs accurately summarize your PDF's key points in conversational responses.",
  },
  {
    icon: Search,
    title: "Thematic Discovery",
    aiResponse: '"For more details, see the comprehensive guide available at yourdomain.com/pdf/..."',
    description: "Your PDFs appear as recommended resources when users explore related topics.",
  },
];

const geoFeatures = [
  {
    icon: FileText,
    title: "Semantic Markup",
    description: "Clear HTML structure and schema.org vocabulary help AI understand document context and relationships.",
  },
  {
    icon: Sparkles,
    title: "JSON-LD Schema",
    description: "DigitalDocument structured data provides machine-readable metadata about title, author, and content.",
  },
  {
    icon: Bot,
    title: "Optimized Metadata",
    description: "Keywords, descriptions, and taxonomies embedded in a format AI models can easily parse and index.",
  },
];

export function GEOSection() {
  return (
    <section className="py-20 lg:py-32 bg-card" aria-labelledby="geo-heading">
      <div className="container mx-auto px-4 lg:px-8">
        {/* Header */}
        <header className="max-w-3xl mx-auto text-center mb-16">
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-medium mb-6">
            <Bot className="w-4 h-4" aria-hidden="true" />
            <span>Generative Engine Optimization</span>
          </div>
          <h2 id="geo-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Make Your PDFs AI-Ready
          </h2>
          <p className="text-lg text-muted-foreground">
            Go beyond traditional SEO. GEO ensures your PDF content is discovered, understood, 
            and accurately cited by AI tools like ChatGPT, Perplexity, and Google AI Overview.
          </p>
        </header>

        {/* How GEO Works */}
        <div className="max-w-5xl mx-auto mb-16">
          <p className="text-xl font-semibold text-center mb-8">How GEO Works</p>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {geoFeatures.map((feature, index) => (
              <article
                key={feature.title}
                className="p-6 rounded-2xl bg-background border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                  <feature.icon className="w-6 h-6 text-primary" aria-hidden="true" />
                </div>
                <h3 className="text-lg font-semibold text-foreground mb-2">{feature.title}</h3>
                <p className="text-muted-foreground text-sm leading-relaxed">{feature.description}</p>
              </article>
            ))}
          </div>
        </div>

        {/* Examples in AI Responses */}
        <div className="max-w-5xl mx-auto">
          <p className="text-xl font-semibold text-center mb-8">How Your PDFs Appear in AI Responses</p>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {examples.map((example, index) => (
              <article
                key={example.title}
                className="group p-6 rounded-2xl bg-background border border-border hover:border-primary/30 hover:shadow-soft transition-all duration-300 animate-fade-in"
                style={{ animationDelay: `${index * 0.15}s` }}
              >
                <div className="w-10 h-10 rounded-lg gradient-hero flex items-center justify-center mb-4 group-hover:opacity-90 transition-colors">
                  <example.icon className="w-5 h-5 text-primary-foreground" aria-hidden="true" />
                </div>
                <h3 className="text-lg font-semibold text-foreground mb-3">{example.title}</h3>
                <blockquote className="text-sm italic text-foreground bg-muted rounded-lg p-3 mb-4 border-l-2 border-primary">
                  {example.aiResponse}
                </blockquote>
                <p className="text-muted-foreground text-sm leading-relaxed">{example.description}</p>
              </article>
            ))}
          </div>
        </div>

        {/* Bottom note */}
        <p className="text-center text-muted-foreground mt-12 max-w-2xl mx-auto">
          With GEO optimization, your valuable PDF content stays at the forefront of information discovery—even as AI technologies continue to advance.
        </p>
      </div>
    </section>
  );
}
