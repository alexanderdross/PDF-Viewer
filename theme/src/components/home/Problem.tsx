import { X, AlertTriangle, Search, Link2, ExternalLink, Share2, Eye, Smartphone, Shield } from "lucide-react";

const problems = [
  {
    icon: Link2,
    title: "URL Exposes File Location",
    description: "Direct PDF links like /uploads/doc.pdf look unprofessional and expose your file structure to everyone.",
  },
  {
    icon: Search,
    title: "No SEO Value",
    description: "Search engines treat PDFs as files, not pages. No title tags, meta descriptions, or structured data means no rankings.",
  },
  {
    icon: Share2,
    title: "No Social Sharing Preview",
    description: "When someone shares your PDF link on social media, it shows nothing—no image, no description, no engagement.",
  },
  {
    icon: Eye,
    title: "No Control or Analytics",
    description: "You can't track views, control print/download options, or know which documents are actually being read.",
  },
  {
    icon: ExternalLink,
    title: "Users Leave Your Site",
    description: "PDFs open in the browser's default viewer, taking visitors away from your brand and website experience.",
  },
  {
    icon: Smartphone,
    title: "Inconsistent Mobile Experience",
    description: "Mobile viewing varies wildly by device and browser. Some visitors can't read your documents at all.",
  },
];

export function Problem() {
  return (
    <section className="py-20 lg:py-32 bg-card" aria-labelledby="problem-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-16">
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-destructive/10 text-destructive text-sm font-medium mb-6">
            <X className="w-4 h-4" aria-hidden="true" />
            <span>The Problem</span>
          </div>
          <h2 id="problem-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Your PDFs Are Working Against You
          </h2>
          <p className="text-lg text-muted-foreground">
            Right now, uploading PDFs to WordPress means losing control over how 
            customers find and experience your important documents.
          </p>
        </header>

        <ul className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 list-none" role="list" aria-label="Common PDF problems">
          {problems.map((problem, index) => (
            <li
              key={problem.title}
              className="relative p-6 rounded-2xl bg-background border border-destructive/20 shadow-soft animate-fade-in"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <div className="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center mb-4">
                <problem.icon className="w-5 h-5 text-destructive" aria-hidden="true" />
              </div>
              <h3 className="text-lg font-semibold mb-2">{problem.title}</h3>
              <p className="text-sm text-muted-foreground">{problem.description}</p>
            </li>
          ))}
        </ul>

        {/* Example */}
        <div className="mt-16 max-w-3xl mx-auto">
          <div className="p-6 rounded-xl bg-destructive/5 border border-destructive/20">
            <p className="text-sm font-medium text-destructive mb-2">❌ What your customers see now:</p>
            <a 
              href="https://pdfviewermodule.com/wp-content/uploads/2025/03/example-1.pdf"
              target="_blank"
              rel="noopener noreferrer"
              className="text-sm text-muted-foreground break-all hover:text-destructive hover:underline transition-colors"
              title="View example of standard WordPress PDF URL (opens in new tab)"
              aria-label="Example standard WordPress PDF upload URL - opens in new tab"
            >
              <code>yourdomain.com/wp-content/uploads/2025/03/example-document.pdf</code>
            </a>
            <p className="text-xs text-muted-foreground mt-3">
              A messy link that doesn't tell anyone what the document is about
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}
