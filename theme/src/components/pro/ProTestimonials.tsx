import { Star } from "lucide-react";

const testimonials = [
  {
    name: "Sarah Mitchell",
    role: "Marketing Director",
    company: "TechFlow Agency",
    avatar: "SM",
    rating: 5,
    quote: "The analytics alone are worth the upgrade. We finally know which PDFs our clients actually read. The email gate feature has boosted our lead capture by 40%.",
  },
  {
    name: "James Rodriguez",
    role: "WordPress Developer",
    company: "DevCraft Studios",
    avatar: "JR",
    rating: 5,
    quote: "The REST API and extended hooks made it easy to build custom integrations. Best PDF plugin I've worked with in 10 years of WordPress development.",
  },
  {
    name: "Dr. Thomas Brenner",
    role: "IT Lead",
    company: "European Pharmaceutical Company",
    avatar: "TB",
    rating: 5,
    quote: "We needed audit-ready document access controls without rebuilding our entire intranet. The REST API and expiring links covered our use case perfectly.",
  },
  {
    name: "Emily Chen",
    role: "Content Manager",
    company: "EduLearn Platform",
    avatar: "EC",
    rating: 5,
    quote: "Bulk import saved us hours when migrating 500+ course materials. Password protection keeps our premium content secure. Priority support is incredibly responsive.",
  },
  {
    name: "Anna Lindqvist",
    role: "Digital Workplace Manager",
    company: "Healthcare Organization",
    avatar: "AL",
    rating: 5,
    quote: "Role-based access and the analytics export were the two features that got this past our compliance review. Implementation took less than a day.",
  },
  {
    name: "Lisa Patel",
    role: "HR Manager",
    company: "GlobalCorp Inc.",
    avatar: "LP",
    rating: 5,
    quote: "User role restrictions let us share sensitive documents with specific departments only. The reading progress feature helps us track training completion.",
  },
];

function StarRating({ rating }: { rating: number }) {
  return (
    <div className="flex gap-0.5" role="img" aria-label={`${rating} out of 5 stars`}>
      {[...Array(5)].map((_, i) => (
        <Star
          key={i}
          className={`w-4 h-4 ${i < rating ? "text-amber-400 fill-amber-400" : "text-muted-foreground"}`}
          aria-hidden="true"
        />
      ))}
    </div>
  );
}

export function ProTestimonials() {
  return (
    <section className="py-20 lg:py-32 bg-background" aria-labelledby="testimonials-heading">
      <div className="container mx-auto px-4 lg:px-8">
        <header className="max-w-3xl mx-auto text-center mb-12">
          <h2 id="testimonials-heading" className="text-3xl md:text-4xl font-bold mb-6">
            Trusted Across Industries
          </h2>
          <p className="text-lg text-muted-foreground">
            Join thousands of satisfied users who upgraded to Pro
          </p>
        </header>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
          {testimonials.map((testimonial, index) => (
            <article
              key={testimonial.name}
              className="p-6 rounded-2xl bg-card border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <div className="flex items-center gap-4 mb-4">
                <div className="w-12 h-12 rounded-full gradient-hero flex items-center justify-center text-primary-foreground font-semibold">
                  {testimonial.avatar}
                </div>
                <div>
                  <div className="font-semibold text-foreground">{testimonial.name}</div>
                  <div className="text-sm text-muted-foreground">{testimonial.role}</div>
                  <div className="text-xs text-muted-foreground">{testimonial.company}</div>
                </div>
              </div>

              <StarRating rating={testimonial.rating} />

              <blockquote className="mt-4 text-muted-foreground leading-relaxed">
                "{testimonial.quote}"
              </blockquote>
            </article>
          ))}
        </div>

        {/* Summary Stats */}
        <div className="mt-12 flex flex-wrap items-center justify-center gap-8 md:gap-16">
          <div className="text-center">
            <div className="text-3xl font-bold text-foreground">2,000+</div>
            <div className="text-sm text-muted-foreground">Active Pro Users</div>
          </div>
          <div className="text-center">
            <div className="text-3xl font-bold text-foreground">4.9/5</div>
            <div className="text-sm text-muted-foreground">Average Rating</div>
          </div>
          <div className="text-center">
            <div className="text-3xl font-bold text-foreground">3 Platforms</div>
            <div className="text-sm text-muted-foreground">WordPress, Drupal & React</div>
          </div>
          <div className="text-center">
            <div className="text-3xl font-bold text-foreground">30-day</div>
            <div className="text-sm text-muted-foreground">Money-Back Guarantee</div>
          </div>
        </div>
      </div>
    </section>
  );
}
