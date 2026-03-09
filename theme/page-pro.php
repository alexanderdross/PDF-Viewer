<?php
/**
 * Template Name: Pro
 * Pro/Pricing page — 4-tier comparison (Free, Pro, Pro+, Enterprise)
 *
 * @package PDFViewer
 */

get_header();

$plans = array(
    array(
        'name'        => 'Pro',
        'price'       => '$49',
        'period'      => '/year',
        'description' => 'All essential Pro features for a single site',
        'features'    => array(
            '1 site license',
            'Analytics Dashboard',
            'Password Protection',
            'Reading Progress Tracking',
            'XML Sitemap (/pdf/sitemap.xml)',
            'Categories &amp; Tags',
            'CSV/JSON Export',
            '1 year of updates',
            'Priority email support',
        ),
        'popular'     => true,
        'cta'         => 'Get Pro',
    ),
    array(
        'name'        => 'Pro+',
        'price'       => '$199',
        'period'      => '/year',
        'description' => 'Unlimited sites for agencies and teams',
        'features'    => array(
            'Unlimited site licenses',
            'Everything in Pro, plus:',
            'Role-Based Access Control',
            'Bulk Import (CSV/ZIP)',
            'Full REST API',
            'White-Label Options',
            'Priority + Chat support',
        ),
        'popular'     => false,
        'cta'         => 'Get Pro+',
    ),
);

$lifetime = array(
    'name'        => 'Lifetime',
    'price'       => '$799',
    'period'      => 'one-time',
    'description' => 'Pay once, own it forever. Best value for long-term users and agencies who want to eliminate recurring costs.',
    'features'    => array(
        'All current and future Pro features',
        'Unlimited site licenses',
        'Lifetime updates — no renewals, ever',
        'Priority support included for life',
    ),
);

$enterprise_features = array(
    'Unlimited sites and users',
    'Lifetime updates',
    'Dedicated account manager',
    'SLA agreement (99.5%+ uptime)',
    'SSO integration support (Azure AD, Okta)',
    'Compliance documentation package',
    'Expiring access links &amp; audit logs',
    'Custom onboarding &amp; training',
    'Data Processing Agreement (DPA) available',
    'Custom development (scoped)',
);

$faqs = array(
    array(
        'question' => 'What\'s included in the Pro plan?',
        'answer'   => 'The Pro plan ($49/year) includes all essential Pro features for 1 site: Analytics Dashboard, Password Protection, Reading Progress, XML Sitemap, Categories &amp; Tags, CSV/JSON Export, and priority email support with 1 year of updates.',
    ),
    array(
        'question' => 'What is the difference between the plans?',
        'answer'   => 'Pro ($49/year, 1 site) includes analytics, password protection, reading progress, XML sitemap, categories/tags, and CSV/JSON export. Pro+ ($199/year, unlimited sites) adds role-based access, bulk import, full REST API, and white-label options. Enterprise (from $2,500/year) adds dedicated support, SLA, SSO, and compliance documentation.',
    ),
    array(
        'question' => 'Can I upgrade between plans?',
        'answer'   => 'Yes! You can upgrade at any time. We\'ll prorate your existing license and only charge the difference. Contact our support team and we\'ll handle the upgrade within 24 hours.',
    ),
    array(
        'question' => 'What happens when my license expires?',
        'answer'   => 'Your Pro features continue working indefinitely—we don\'t disable anything. However, you won\'t receive updates or priority support. You can renew at any time to restore access to updates and support.',
    ),
    array(
        'question' => 'Do you offer refunds?',
        'answer'   => 'Absolutely. We offer a 30-day money-back guarantee, no questions asked. If Pro doesn\'t meet your needs, contact support for a full refund within 30 days of purchase.',
    ),
    array(
        'question' => 'Can I use Pro on staging/development sites?',
        'answer'   => 'Yes! Staging, development, and localhost sites don\'t count against your license limit. Only live production sites require activation. This makes it easy to test before going live.',
    ),
    array(
        'question' => 'Is the Lifetime license really one-time payment?',
        'answer'   => 'Yes. The Lifetime Unlimited license ($799 one-time) is a perpetual license for all Pro+ features on unlimited sites. This includes all future feature updates — no recurring fees, ever. The price may increase as the product grows, so current pricing is locked for early buyers.',
    ),
    array(
        'question' => 'What payment methods do you accept?',
        'answer'   => 'We accept all major credit cards (Visa, MasterCard, American Express, Discover) and PayPal. All payments are securely processed through Stripe.',
    ),
    array(
        'question' => 'How do I receive Pro after purchase?',
        'answer'   => 'Immediately after purchase, you\'ll receive an email with your license key and download link. You can also access these from your account dashboard at any time.',
    ),
    array(
        'question' => 'Do you offer Enterprise plans for regulated industries?',
        'answer'   => 'Yes. We offer dedicated Enterprise plans for Pharmaceutical, Life Sciences, Healthcare, Finance, and Legal organizations. These include SLA agreements, compliance documentation, and dedicated support.',
        'link'     => array( 'text' => 'See Enterprise plans →', 'href' => '/enterprise/' ),
    ),
    array(
        'question' => 'Can you sign a Data Processing Agreement (DPA)?',
        'answer'   => 'Yes, for Enterprise plan customers. Contact us through our contact form with your DPA requirements.',
        'link'     => array( 'text' => 'Contact us →', 'href' => 'https://dross.net/contact/?topic=pdfviewer' ),
    ),
    array(
        'question' => 'Do you offer discounts for non-profits or education?',
        'answer'   => 'Yes — 40% off for registered non-profits and educational institutions. Contact support with proof of status.',
    ),
    array(
        'question' => 'Does Pro work with WordPress, Drupal, and React/Next.js?',
        'answer'   => 'Yes! PDF Embed & SEO Optimize Pro is available for WordPress (5.8+), Drupal (10/11), and React 18+/Next.js 13+. One license covers all platforms. The React premium package is available as <code>@pdf-embed-seo/react-premium</code> on npm.',
    ),
    array(
        'question' => 'What kind of support is included?',
        'answer'   => 'Pro includes priority email support. Pro+ includes priority email + chat support. Enterprise includes a dedicated account manager. All licenses include 1 year of updates.',
    ),
    array(
        'question' => 'Who owns the intellectual property (IP)?',
        'answer'   => 'All intellectual property rights, including but not limited to the source code, design, documentation, trademarks, and trade secrets of PDF Embed &amp; SEO Optimize remain the exclusive property of <a href="https://drossmedia.de/?ref=pdfviewer" target="_blank" rel="noopener" class="text-primary hover:underline">Dross:Media</a>. This applies to both the Free and Pro versions. Your license grants you the right to use the software — it does not transfer ownership. Any modifications, derivative works, or customizations you create based on our code do not affect <a href="https://drossmedia.de/?ref=pdfviewer" target="_blank" rel="noopener" class="text-primary hover:underline">Dross:Media</a>\'s underlying IP ownership. Redistribution of modified versions must retain original copyright notices and may not misrepresent the origin of the software.',
    ),
);

$testimonials = array(
    array(
        'name'    => 'Sarah Mitchell',
        'role'    => 'Marketing Director',
        'company' => 'TechFlow Agency',
        'avatar'  => 'SM',
        'quote'   => 'The analytics alone are worth the upgrade. We finally know which PDFs our clients actually read. The email gate feature has boosted our lead capture by 40%.',
    ),
    array(
        'name'    => 'James Rodriguez',
        'role'    => 'WordPress Developer',
        'company' => 'DevCraft Studios',
        'avatar'  => 'JR',
        'quote'   => 'The REST API and extended hooks made it easy to build custom integrations. Best PDF plugin I\'ve worked with in 10 years of WordPress development.',
    ),
    array(
        'name'    => 'Dr. Thomas Brenner',
        'role'    => 'IT Lead',
        'company' => 'European Pharmaceutical Company',
        'avatar'  => 'TB',
        'quote'   => 'We needed audit-ready document access controls without rebuilding our entire intranet. The REST API and expiring links covered our use case perfectly.',
    ),
    array(
        'name'    => 'Emily Chen',
        'role'    => 'Content Manager',
        'company' => 'EduLearn Platform',
        'avatar'  => 'EC',
        'quote'   => 'Bulk import saved us hours when migrating 500+ course materials. Password protection keeps our premium content secure. Priority support is incredibly responsive.',
    ),
    array(
        'name'    => 'Anna Lindqvist',
        'role'    => 'Digital Workplace Manager',
        'company' => 'Healthcare Organization',
        'avatar'  => 'AL',
        'quote'   => 'Role-based access and the analytics export were the two features that got this past our compliance review. Implementation took less than a day.',
    ),
    array(
        'name'    => 'Lisa Patel',
        'role'    => 'HR Manager',
        'company' => 'GlobalCorp Inc.',
        'avatar'  => 'LP',
        'quote'   => 'User role restrictions let us share sensitive documents with specific departments only. The reading progress feature helps us track training completion.',
    ),
);

// Comparison data — 4 columns (Free, Pro, Pro+, Enterprise)
$tier_columns = array(
    array( 'key' => 'free',       'label' => 'Free',       'class' => 'text-foreground' ),
    array( 'key' => 'pro',        'label' => 'Pro',        'class' => 'text-primary' ),
    array( 'key' => 'proPlus',    'label' => 'Pro+',       'class' => 'text-foreground' ),
    array( 'key' => 'enterprise', 'label' => 'Enterprise', 'class' => 'text-accent' ),
);

/**
 * Helper: feature available from a given tier upward.
 */
function pdfviewer_from_tier( $tier ) {
    $levels = array( 'free' => 0, 'pro' => 1, 'proPlus' => 2, 'enterprise' => 3 );
    $level  = $levels[ $tier ];
    return array(
        'free'       => $level <= 0,
        'pro'        => $level <= 1,
        'proPlus'    => $level <= 2,
        'enterprise' => $level <= 3,
    );
}

$comparison_data = array(
    array(
        'category' => 'Viewer & Display',
        'features' => array(
            array_merge( array( 'name' => 'Mozilla PDF.js Viewer (v4.0)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Light & Dark Themes' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Responsive Design' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Print Control (per PDF)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Download Control (per PDF)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Configurable Viewer Height' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Gutenberg Block (WP)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'PDF Viewer Block (Drupal)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Shortcodes (WP)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Text Search in Viewer' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Bookmark Navigation' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Reading Progress (Resume)' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Mobile Swipe Gestures' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Presentation Mode' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Annotations & Highlighting' ), pdfviewer_from_tier( 'proPlus' ) ),
        ),
    ),
    array(
        'category' => 'Content Management',
        'features' => array(
            array_merge( array( 'name' => 'PDF Document Post Type / Entity' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Title, Description, Slug' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'File Upload & Management' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Featured Image / Thumbnail' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Auto-Generate Thumbnails' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Published/Draft Status' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Owner/Author Tracking' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Admin List with Sortable Columns' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Quick Edit Support (WP)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Multi-language Support' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Categories Taxonomy' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Tags Taxonomy' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Bulk Edit Actions' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Bulk Import (CSV/ZIP)' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Duplicate PDF Document' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Import/Export Settings' ), pdfviewer_from_tier( 'proPlus' ) ),
        ),
    ),
    array(
        'category' => 'SEO & URLs',
        'features' => array(
            array_merge( array( 'name' => 'Clean URL Structure (/pdf/slug/)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Auto Path/Slug Generation' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Schema.org DigitalDocument' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Schema.org CollectionPage (Archive)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Schema.org BreadcrumbList' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Yoast SEO Integration (WP)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'OpenGraph Meta Tags' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Twitter Card Support' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'GEO/AEO/LLM Schema Optimization' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'SpeakableSpecification (Voice Assistants)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'potentialAction Schema (ReadAction, etc.)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Standalone Social Meta (without Yoast)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'XML Sitemap (/pdf/sitemap.xml)' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Sitemap XSL Stylesheet' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Search Engine Ping' ), pdfviewer_from_tier( 'pro' ) ),
        ),
    ),
    array(
        'category' => 'AI & Voice Search Optimization',
        'features' => array(
            array_merge( array( 'name' => 'Basic Speakable Schema (WebPage)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'potentialAction (ReadAction, DownloadAction)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'accessMode & accessibilityFeature' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'AI Summary / TL;DR Field' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Key Points & Takeaways' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'FAQ Schema (FAQPage for Rich Results)' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Table of Contents Schema (hasPart)' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Reading Time Estimate (timeRequired)' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Difficulty Level (educationalLevel)' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Document Type Classification' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Target Audience Schema' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Custom Speakable Content' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Related Documents (isRelatedTo)' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Prerequisites (coursePrerequisites)' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Learning Outcomes (teaches)' ), pdfviewer_from_tier( 'proPlus' ) ),
        ),
    ),
    array(
        'category' => 'Archive & Listing',
        'features' => array(
            array_merge( array( 'name' => 'Archive Page (/pdf/)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Pagination Support' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Grid/List Display Modes' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Sorting Options' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Search Filtering' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Visible Breadcrumb Navigation' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Category Filter' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Tag Filter' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Archive Page Redirect' ), pdfviewer_from_tier( 'proPlus' ) ),
        ),
    ),
    array(
        'category' => 'Statistics & Analytics',
        'features' => array(
            array_merge( array( 'name' => 'Basic View Counter' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'View Count Display' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Analytics Dashboard' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Detailed View Tracking' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Download Tracking' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'IP Address Logging' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'User Agent Tracking' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Referrer Tracking' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Time Spent Tracking' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Popular Documents Report' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Recent Views Log' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Analytics Export (CSV/JSON)' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Time Period Filters' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Time-based Charts' ), pdfviewer_from_tier( 'pro' ) ),
        ),
    ),
    array(
        'category' => 'Security & Access',
        'features' => array(
            array_merge( array( 'name' => 'Nonce/CSRF Protection' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Permission System' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Capability/Access Checks' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Secure PDF URL (no direct links)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Input Sanitization' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Output Escaping' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Password Protection' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Secure Password Hashing (bcrypt)' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Session-Based Access' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Configurable Session Duration' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Brute-Force Protection' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Login Requirement Option' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Role Restrictions' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Expiring Access Links' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Time-Limited URLs' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Max Uses per Link' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Dynamic Watermarks' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'IP Anonymization (GDPR)' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'Custom Branding / White Label' ), pdfviewer_from_tier( 'proPlus' ) ),
        ),
    ),
    array(
        'category' => 'REST API',
        'features' => array(
            array_merge( array( 'name' => 'GET /documents (list)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'GET /documents/{id} (single)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'GET /documents/{id}/data (secure URL)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'POST /documents/{id}/view (track)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'GET /settings' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'GET /analytics' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'GET /analytics/documents' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'GET /analytics/export' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'GET/POST /documents/{id}/progress' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'POST /documents/{id}/verify-password' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'POST /documents/{id}/download' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'POST /documents/{id}/expiring-link' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'GET /documents/{id}/expiring-link/{token}' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'GET /categories, /tags' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'POST /bulk/import' ), pdfviewer_from_tier( 'proPlus' ) ),
            array_merge( array( 'name' => 'GET /bulk/import/status' ), pdfviewer_from_tier( 'proPlus' ) ),
        ),
    ),
    array(
        'category' => 'Reading Experience',
        'features' => array(
            array_merge( array( 'name' => 'Page Navigation' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Zoom Controls' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Full Screen Mode' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Reading Progress Tracking' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Resume Reading Feature' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Page Position Save' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Scroll Position Save' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Zoom Level Save' ), pdfviewer_from_tier( 'pro' ) ),
        ),
    ),
    array(
        'category' => 'Integrations',
        'features' => array(
            array_merge( array( 'name' => 'Yoast SEO (WP)' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Google Analytics 4' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'Mailchimp (Email Gate)' ), pdfviewer_from_tier( 'pro' ) ),
            array_merge( array( 'name' => 'WooCommerce (Sell PDFs)' ), pdfviewer_from_tier( 'proPlus' ) ),
        ),
    ),
    array(
        'category' => 'Developer Features',
        'features' => array(
            array( 'name' => 'WordPress Hooks', 'free' => 'Basic', 'pro' => 'Extended', 'proPlus' => 'Extended', 'enterprise' => 'Extended' ),
            array_merge( array( 'name' => 'Drupal Alter Hooks' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Template Overrides' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Coding Standards Compliant' ), pdfviewer_from_tier( 'free' ) ),
            array_merge( array( 'name' => 'Translation Ready' ), pdfviewer_from_tier( 'free' ) ),
        ),
    ),
    array(
        'category' => 'Support',
        'features' => array(
            array_merge( array( 'name' => 'Community Support' ), pdfviewer_from_tier( 'free' ) ),
            array( 'name' => 'Email Support', 'free' => false, 'pro' => true, 'proPlus' => true, 'enterprise' => true ),
            array( 'name' => 'Priority Email Support', 'free' => false, 'pro' => true, 'proPlus' => true, 'enterprise' => true ),
            array( 'name' => 'Priority + Chat Support', 'free' => false, 'pro' => false, 'proPlus' => true, 'enterprise' => true ),
            array_merge( array( 'name' => 'Dedicated Account Manager' ), pdfviewer_from_tier( 'enterprise' ) ),
        ),
    ),
    array(
        'category' => 'Enterprise',
        'features' => array(
            array_merge( array( 'name' => 'SLA Agreement (99.5%+ uptime)' ), pdfviewer_from_tier( 'enterprise' ) ),
            array_merge( array( 'name' => 'SSO Integration Support' ), pdfviewer_from_tier( 'enterprise' ) ),
            array_merge( array( 'name' => 'Compliance Documentation Package' ), pdfviewer_from_tier( 'enterprise' ) ),
            array_merge( array( 'name' => 'Data Processing Agreement (DPA)' ), pdfviewer_from_tier( 'enterprise' ) ),
            array_merge( array( 'name' => 'Custom Development (scoped)' ), pdfviewer_from_tier( 'enterprise' ) ),
            array_merge( array( 'name' => 'Lifetime Updates' ), pdfviewer_from_tier( 'enterprise' ) ),
            array_merge( array( 'name' => 'Priority Escalation Path' ), pdfviewer_from_tier( 'enterprise' ) ),
        ),
    ),
);
?>

<!-- Hero -->
<section class="relative pt-8 pb-8 md:pt-10 md:pb-10 lg:pt-12 lg:pb-10 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-primary/5 via-background to-background" aria-hidden="true"></div>
    <div class="hidden md:block absolute top-20 left-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="hidden md:block absolute bottom-20 right-1/4 w-80 h-80 bg-accent/10 rounded-full blur-3xl" aria-hidden="true"></div>

    <div class="container relative mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 text-primary text-sm font-medium mb-8 animate-fade-in">
                <?php echo pdfviewer_icon('zap', 16); ?>
                WordPress, Drupal & React / Next.js
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground leading-tight mb-6 animate-fade-in" style="animation-delay: 0.1s">
                <span class="text-gradient"><?php esc_html_e('PDF Embed & SEO Optimize', 'pdfviewer'); ?></span> Pro
            </h1>

            <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-8 animate-fade-in" style="animation-delay: 0.2s">
                <?php esc_html_e('From personal sites to regulated enterprise environments — choose the plan that fits your scale. All plans include a 30-day money-back guarantee.', 'pdfviewer'); ?>
            </p>

            <div class="flex flex-wrap justify-center gap-4 mb-10 animate-fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-card shadow-soft">
                    <?php echo pdfviewer_icon('eye', 16, 'text-primary'); ?>
                    <span class="text-sm font-medium text-foreground"><?php esc_html_e('Analytics Dashboard', 'pdfviewer'); ?></span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-card shadow-soft">
                    <?php echo pdfviewer_icon('lock', 16, 'text-primary'); ?>
                    <span class="text-sm font-medium text-foreground"><?php esc_html_e('Password Protection', 'pdfviewer'); ?></span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-card shadow-soft">
                    <?php echo pdfviewer_icon('zap', 16, 'text-primary'); ?>
                    <span class="text-sm font-medium text-foreground"><?php esc_html_e('Reading Progress', 'pdfviewer'); ?></span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in" style="animation-delay: 0.4s">
                <a href="#pricing" class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2" title="<?php esc_attr_e('View pricing plans', 'pdfviewer'); ?>">
                    <?php esc_html_e('See All Plans', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                </a>
                <a href="#compare" class="btn btn-outline btn-lg" title="<?php esc_attr_e('Compare features across all tiers', 'pdfviewer'); ?>">
                    <?php esc_html_e('Compare Features', 'pdfviewer'); ?>
                </a>
                <a href="/enterprise/" class="btn btn-ghost btn-lg text-accent" title="<?php esc_attr_e('Enterprise plans from $2,500/year', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('View Enterprise plans for regulated industries', 'pdfviewer'); ?>">
                    <?php esc_html_e('Enterprise Options', 'pdfviewer'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Pricing -->
<section id="pricing" class="py-8 lg:py-10 bg-background scroll-mt-24" aria-labelledby="pricing-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="pricing-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Simple, Transparent Pricing', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('Choose the plan that fits your needs. All plans include a 30-day money-back guarantee.', 'pdfviewer'); ?>
            </p>
        </header>

        <!-- Annual Plans Header -->
        <div class="max-w-3xl mx-auto text-center mb-8">
            <h3 class="text-2xl font-bold text-foreground mb-2"><?php esc_html_e('Annual Plans', 'pdfviewer'); ?></h3>
            <p class="text-muted-foreground"><?php esc_html_e('All premium features — choose your scale', 'pdfviewer'); ?></p>
        </div>

        <!-- Annual Plans -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto mb-12">
            <?php foreach ($plans as $index => $plan) : ?>
                <article class="relative p-6 rounded-2xl border transition-all duration-300 animate-fade-in flex flex-col <?php echo $plan['popular'] ? 'bg-primary/10 border-primary shadow-glow' : 'bg-card border-border hover:border-primary/30'; ?>" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                    <?php if ($plan['popular']) : ?>
                        <div class="absolute -top-3 left-0 right-0 flex justify-center">
                            <span class="px-3 py-1 rounded-full bg-primary text-primary-foreground text-xs font-semibold flex items-center gap-1">
                                <?php echo pdfviewer_icon('sparkles', 12); ?>
                                <?php esc_html_e('Most Popular', 'pdfviewer'); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mb-6">
                        <h4 class="text-xl font-semibold text-foreground mb-1"><?php echo esc_html($plan['name']); ?></h4>
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-4xl font-bold text-foreground"><?php echo esc_html($plan['price']); ?></span>
                            <span class="text-muted-foreground"><?php echo esc_html($plan['period']); ?></span>
                        </div>
                        <p class="text-sm text-muted-foreground mt-2"><?php echo esc_html($plan['description']); ?></p>
                    </div>

                    <ul class="space-y-3 mb-6 flex-1">
                        <?php foreach ($plan['features'] as $feature) : ?>
                            <li class="flex items-center gap-2 text-sm">
                                <?php if ( strpos($feature, 'Everything in') === 0 ) : ?>
                                    <span class="text-xs font-semibold text-primary uppercase tracking-wide"><?php echo $feature; ?></span>
                                <?php else : ?>
                                    <?php echo pdfviewer_icon('check', 16, 'text-primary shrink-0'); ?>
                                    <span class="text-foreground"><?php echo esc_html($feature); ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <button class="btn <?php echo $plan['popular'] ? 'btn-primary gradient-hero shadow-glow' : 'btn-outline'; ?> w-full mt-auto" title="<?php echo esc_attr( sprintf( 'Purchase %s plan for %s per year', $plan['name'], $plan['price'] ) ); ?>">
                        <?php echo esc_html($plan['cta']); ?>
                    </button>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Lifetime Plan -->
        <div class="max-w-3xl mx-auto mb-20">
            <article class="p-8 rounded-2xl bg-gradient-to-br from-primary/15 via-accent/10 to-background border border-primary/30 shadow-soft animate-fade-in" style="animation-delay: 0.4s">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl gradient-hero flex items-center justify-center shadow-glow shrink-0">
                        <?php echo pdfviewer_icon('zap', 32, 'text-primary-foreground'); ?>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <h4 class="text-2xl font-bold text-foreground mb-1"><?php echo esc_html($lifetime['name']); ?></h4>
                        <div class="flex items-baseline justify-center md:justify-start gap-2 mb-2">
                            <span class="text-3xl font-bold text-primary"><?php echo esc_html($lifetime['price']); ?></span>
                            <span class="text-muted-foreground"><?php echo esc_html($lifetime['period']); ?></span>
                        </div>
                        <p class="text-muted-foreground"><?php echo esc_html($lifetime['description']); ?></p>
                    </div>

                    <button class="btn btn-primary btn-lg gradient-hero shadow-glow shrink-0" title="<?php esc_attr_e('Get lifetime access for a one-time payment of $799', 'pdfviewer'); ?>">
                        <?php esc_html_e('Get Lifetime Access', 'pdfviewer'); ?>
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-primary/20">
                    <?php foreach ($lifetime['features'] as $feature) : ?>
                        <div class="flex items-center gap-2 text-sm">
                            <?php echo pdfviewer_icon('check', 16, 'text-primary shrink-0'); ?>
                            <span class="text-foreground"><?php echo esc_html($feature); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="text-xs text-muted-foreground mt-4 text-center italic">
                    <?php esc_html_e('Limited availability — price subject to change as features grow.', 'pdfviewer'); ?>
                </p>
            </article>
        </div>

        <!-- Enterprise Section -->
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-foreground mb-2"><?php esc_html_e('Enterprise', 'pdfviewer'); ?></h3>
                <p class="text-muted-foreground"><?php esc_html_e('For Pharma, Life Sciences, Healthcare & large organizations', 'pdfviewer'); ?></p>
            </div>

            <article class="p-8 rounded-2xl bg-gradient-to-br from-accent/10 via-background to-primary/5 border border-accent/30 shadow-soft animate-fade-in">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-accent/20 flex items-center justify-center shrink-0">
                        <?php echo pdfviewer_icon('building-2', 24, 'text-accent'); ?>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-foreground"><?php esc_html_e('Everything in Pro+, plus:', 'pdfviewer'); ?></h4>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-2xl font-bold text-accent">from $2,500</span>
                            <span class="text-sm text-muted-foreground">/year</span>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-muted-foreground mb-5">
                    <?php esc_html_e('Custom pricing based on deployment size, integration requirements, and SLA needs.', 'pdfviewer'); ?>
                </p>

                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-6">
                    <?php foreach ($enterprise_features as $feature) : ?>
                        <li class="flex items-center gap-2 text-sm">
                            <?php echo pdfviewer_icon('check', 16, 'text-accent shrink-0'); ?>
                            <span class="text-foreground"><?php echo $feature; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <a href="/enterprise/" class="btn w-full bg-accent hover:bg-accent/90 text-accent-foreground gap-2"
                   title="<?php esc_attr_e('Request Enterprise quote — plans from $2,500/year', 'pdfviewer'); ?>"
                   aria-label="<?php esc_attr_e('Request Enterprise quote — plans from $2,500/year', 'pdfviewer'); ?>">
                    <?php esc_html_e('Request Enterprise Quote', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 16); ?>
                </a>

                <p class="text-xs text-muted-foreground mt-4 text-center">
                    <?php esc_html_e('Trusted by teams in pharmaceutical, healthcare, and finance.', 'pdfviewer'); ?>
                    <a href="/enterprise/" class="text-accent hover:underline"
                       title="<?php esc_attr_e('Learn more about Enterprise PDF management', 'pdfviewer'); ?>"
                       aria-label="<?php esc_attr_e('Learn more about our Enterprise offering', 'pdfviewer'); ?>"><?php esc_html_e('Learn more about our Enterprise offering →', 'pdfviewer'); ?></a>
                </p>
            </article>
        </div>
    </div>
</section>

<!-- Comparison Table -->
<section id="compare" class="py-8 lg:py-10 bg-card scroll-mt-24" aria-labelledby="comparison-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="comparison-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Feature Comparison', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php
                $total_features = 0;
                foreach ( $comparison_data as $cat ) {
                    $total_features += count( $cat['features'] );
                }
                printf(
                    esc_html__('Complete feature comparison across %d+ features and all plan tiers', 'pdfviewer'),
                    $total_features
                );
                ?>
            </p>
        </header>

        <div class="max-w-5xl mx-auto overflow-hidden rounded-2xl border border-border shadow-soft">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[480px]" role="table" aria-label="<?php esc_attr_e('Complete feature comparison across Free, Pro, Pro+ and Enterprise plans', 'pdfviewer'); ?>">
                    <thead>
                        <tr class="bg-muted">
                            <th class="text-left py-3 sm:py-4 px-3 sm:px-4 font-semibold text-foreground text-xs sm:text-sm sticky left-0 bg-muted z-10 min-w-[160px] sm:min-w-[220px]" scope="col">
                                <?php esc_html_e('Feature', 'pdfviewer'); ?>
                            </th>
                            <?php foreach ($tier_columns as $tier) : ?>
                                <th class="text-center py-3 sm:py-4 px-1.5 sm:px-3 font-semibold <?php echo esc_attr($tier['class']); ?> text-[10px] sm:text-sm w-14 sm:w-20" scope="col">
                                    <?php echo esc_html($tier['label']); ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comparison_data as $category) : ?>
                            <tr class="bg-muted">
                                <td colspan="5" class="py-2 sm:py-3 px-3 sm:px-4 font-semibold text-foreground text-xs sm:text-sm uppercase tracking-wider">
                                    <?php echo esc_html($category['category']); ?>
                                </td>
                            </tr>
                            <?php foreach ($category['features'] as $feat_index => $feature) : ?>
                                <tr class="<?php echo $feat_index % 2 === 0 ? 'bg-background' : 'bg-muted/30'; ?>">
                                    <td class="py-2 sm:py-3 px-3 sm:px-4 text-foreground text-xs sm:text-sm sticky left-0 bg-inherit z-10">
                                        <?php echo esc_html($feature['name']); ?>
                                    </td>
                                    <?php foreach ($tier_columns as $tier) :
                                        $value = $feature[$tier['key']];
                                        $bg_class = '';
                                        if ($tier['key'] === 'enterprise') $bg_class = 'bg-accent/5';
                                        elseif ($tier['key'] === 'pro') $bg_class = 'bg-primary/5';
                                    ?>
                                        <td class="py-2 sm:py-3 px-1.5 sm:px-3 text-center <?php echo esc_attr($bg_class); ?>">
                                            <?php if ( is_string($value) ) : ?>
                                                <span class="text-foreground font-medium text-xs"><?php echo esc_html($value); ?></span>
                                            <?php elseif ( $value === true ) : ?>
                                                <?php echo pdfviewer_icon('check', 20, 'text-primary mx-auto'); ?>
                                            <?php else : ?>
                                                <?php echo pdfviewer_icon('x-circle', 20, 'text-destructive mx-auto'); ?>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-8 lg:py-10 bg-background" aria-labelledby="testimonials-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="testimonials-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Trusted Across Industries', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('Join thousands of satisfied users who upgraded to Pro', 'pdfviewer'); ?>
            </p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <?php foreach ($testimonials as $index => $testimonial) : ?>
                <article class="p-6 rounded-2xl bg-card border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: <?php echo esc_attr($index * 0.1); ?>s">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full gradient-hero flex items-center justify-center text-primary-foreground font-semibold">
                            <?php echo esc_html($testimonial['avatar']); ?>
                        </div>
                        <div>
                            <div class="font-semibold text-foreground"><?php echo esc_html($testimonial['name']); ?></div>
                            <div class="text-sm text-muted-foreground"><?php echo esc_html($testimonial['role']); ?></div>
                            <div class="text-xs text-muted-foreground"><?php echo esc_html($testimonial['company']); ?></div>
                        </div>
                    </div>

                    <div class="flex gap-0.5 mb-4" role="img" aria-label="<?php esc_attr_e('5 out of 5 stars', 'pdfviewer'); ?>">
                        <?php for ($i = 0; $i < 5; $i++) : ?>
                            <?php echo pdfviewer_icon('star-filled', 16, 'text-amber-400'); ?>
                        <?php endfor; ?>
                    </div>

                    <blockquote class="text-muted-foreground leading-relaxed">
                        "<?php echo esc_html($testimonial['quote']); ?>"
                    </blockquote>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Summary Stats -->
        <div class="mt-12 flex flex-wrap items-center justify-center gap-8 md:gap-16">
            <div class="text-center">
                <div class="text-3xl font-bold text-foreground">2,000+</div>
                <div class="text-sm text-muted-foreground"><?php esc_html_e('Active Pro Users', 'pdfviewer'); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-foreground">4.9/5</div>
                <div class="text-sm text-muted-foreground"><?php esc_html_e('Average Rating', 'pdfviewer'); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-foreground">3 Platforms</div>
                <div class="text-sm text-muted-foreground"><?php esc_html_e('WordPress, Drupal & React', 'pdfviewer'); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-foreground">30-day</div>
                <div class="text-sm text-muted-foreground"><?php esc_html_e('Money-Back Guarantee', 'pdfviewer'); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-8 lg:py-10 bg-card" aria-labelledby="pro-faq-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="pro-faq-heading" class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Frequently Asked Questions', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground">
                <?php esc_html_e('Everything you need to know about licensing, features, and upgrades', 'pdfviewer'); ?>
            </p>
        </header>

        <div class="max-w-3xl mx-auto space-y-4" role="list" aria-label="<?php esc_attr_e('Frequently asked questions about Pro licensing and features', 'pdfviewer'); ?>">
            <?php foreach ($faqs as $index => $faq) :
                $faq_id = 'pro-faq-' . sanitize_title(substr($faq['question'], 0, 50));
            ?>
                <details
                    id="<?php echo esc_attr($faq_id); ?>"
                    class="group bg-background border border-border rounded-xl px-6 py-4 transition-colors open:shadow-soft open:border-primary/30"
                    role="listitem"
                >
                    <summary
                        class="flex items-center justify-between cursor-pointer text-left font-semibold text-foreground hover:text-primary list-none"
                        aria-label="<?php echo esc_attr($faq['question'] . ' - Click to expand answer'); ?>"
                        title="<?php echo esc_attr($faq['question']); ?>"
                    >
                        <?php echo esc_html($faq['question']); ?>
                        <span class="ml-4 shrink-0 transition-transform group-open:rotate-180" aria-hidden="true">
                            <?php echo pdfviewer_icon('chevron-down', 20); ?>
                        </span>
                    </summary>
                    <div class="mt-4 text-muted-foreground" role="region" aria-label="<?php echo esc_attr('Answer to: ' . $faq['question']); ?>">
                        <?php echo wp_kses($faq['answer'], array(
                            'a'    => array( 'href' => array(), 'target' => array(), 'rel' => array(), 'class' => array() ),
                            'code' => array(),
                        )); ?>
                        <?php if ( ! empty( $faq['link'] ) ) : ?>
                            <a href="<?php echo esc_url($faq['link']['href']); ?>" class="block mt-2 text-primary hover:underline font-medium">
                                <?php echo esc_html($faq['link']['text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-8 lg:py-10 bg-background">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                <?php esc_html_e('Ready to Unlock Pro Features?', 'pdfviewer'); ?>
            </h2>
            <p class="text-lg text-muted-foreground mb-8">
                <?php esc_html_e('Join thousands of users who have upgraded to Pro. 30-day money-back guarantee.', 'pdfviewer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#pricing" class="btn btn-primary btn-lg gradient-hero shadow-glow gap-2">
                    <?php echo pdfviewer_icon('zap', 20); ?>
                    <?php esc_html_e('Get Pro Now', 'pdfviewer'); ?>
                </a>
                <div class="relative inline-block" data-dropdown="try-free">
                    <button type="button"
                            data-dropdown-trigger
                            class="btn btn-outline btn-lg gap-2"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-controls="try-free-menu"
                            aria-label="<?php esc_attr_e('Select platform to download free version', 'pdfviewer'); ?>"
                            title="<?php esc_attr_e('Choose your platform: WordPress, Drupal, or React', 'pdfviewer'); ?>">
                        <?php echo pdfviewer_icon('download', 20); ?>
                        <?php esc_html_e('Try Free First', 'pdfviewer'); ?>
                        <?php echo pdfviewer_icon('chevron-down', 16); ?>
                    </button>
                    <div id="try-free-menu"
                         data-dropdown-menu
                         class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-56 bg-card rounded-xl border border-border shadow-lg z-50"
                         role="menu"
                         aria-label="<?php esc_attr_e('Platform download options', 'pdfviewer'); ?>">
                        <nav aria-label="<?php esc_attr_e('Free version download links', 'pdfviewer'); ?>">
                            <ul class="py-2" role="list">
                                <li role="none">
                                    <a href="/wordpress-pdf-viewer/#download-wordpress"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors rounded-lg mx-2"
                                       role="menuitem"
                                       aria-label="<?php esc_attr_e('Download free PDF Embed plugin for WordPress', 'pdfviewer'); ?>"
                                       title="<?php esc_attr_e('Get the free WordPress plugin from wordpress.org', 'pdfviewer'); ?>">
                                        <?php pdfviewer_wordpress_icon(20); ?>
                                        <span class="flex-1"><?php esc_html_e('WordPress', 'pdfviewer'); ?></span>
                                        <?php echo pdfviewer_icon('arrow-right', 14, 'text-muted-foreground'); ?>
                                    </a>
                                </li>
                                <li role="none">
                                    <a href="/drupal-pdf-viewer/#download-drupal"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors rounded-lg mx-2"
                                       role="menuitem"
                                       aria-label="<?php esc_attr_e('Download free PDF Embed module for Drupal', 'pdfviewer'); ?>"
                                       title="<?php esc_attr_e('Get the free Drupal module from drupal.org', 'pdfviewer'); ?>">
                                        <?php pdfviewer_drupal_icon(20); ?>
                                        <span class="flex-1"><?php esc_html_e('Drupal', 'pdfviewer'); ?></span>
                                        <?php echo pdfviewer_icon('arrow-right', 14, 'text-muted-foreground'); ?>
                                    </a>
                                </li>
                                <li role="none">
                                    <a href="/nextjs-pdf-viewer/#download-react"
                                       class="flex items-center gap-3 px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors rounded-lg mx-2"
                                       role="menuitem"
                                       aria-label="<?php esc_attr_e('Download free PDF component for React and Next.js', 'pdfviewer'); ?>"
                                       title="<?php esc_attr_e('Get the free React / Next.js component via npm', 'pdfviewer'); ?>">
                                        <?php pdfviewer_react_icon(20); ?>
                                        <span class="flex-1"><?php esc_html_e('React', 'pdfviewer'); ?></span>
                                        <?php echo pdfviewer_icon('arrow-right', 14, 'text-muted-foreground'); ?>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
