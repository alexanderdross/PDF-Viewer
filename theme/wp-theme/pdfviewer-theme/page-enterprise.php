<?php
/**
 * Template Name: Enterprise
 * Enterprise landing page for regulated industries
 *
 * @package PDFViewer
 */

get_header();

$trust_badges = array(
    array('icon' => 'lock', 'text' => 'GDPR Compliant'),
    array('icon' => 'file-text', 'text' => 'Audit-Trail Ready'),
    array('icon' => 'flask-conical', 'text' => 'Life Sciences Trusted'),
    array('icon' => 'key', 'text' => 'SSO / Azure AD Integration'),
    array('icon' => 'shield', 'text' => 'Role-Based Access Control'),
);

$pain_points = array(
    array('challenge' => 'Regulatory Compliance', 'problem' => 'No audit trail, no access logs — fails GxP/GDPR audits', 'solution' => 'Full access audit logs, expiring links, user-level tracking'),
    array('challenge' => 'Security & Access', 'problem' => 'PDFs exposed via direct URLs, no authentication layer', 'solution' => 'Secure URLs, password protection, login requirements, CSRF protection'),
    array('challenge' => 'IT Integration', 'problem' => 'No API, no SSO, no role management — shadow IT results', 'solution' => 'Full REST API, role-based access, integrates with your existing IAM'),
    array('challenge' => 'Scalability', 'problem' => 'Breaks under bulk imports or multi-site setups', 'solution' => 'Bulk CSV/ZIP import, unlimited sites, white-label deployment'),
    array('challenge' => 'Vendor Accountability', 'problem' => 'No SLA, no support contract — unacceptable for regulated environments', 'solution' => 'Dedicated SLA, named account manager, priority escalation path'),
);

$compliance_features = array(
    array('icon' => 'file-text', 'title' => 'Audit-Ready Access Logs', 'description' => 'Every document view, download, and access attempt is logged with user identity, timestamp, and IP. Exportable in CSV/JSON for audit submissions and internal reviews.'),
    array('icon' => 'shield', 'title' => 'Role-Based Access Control (RBAC)', 'description' => 'Restrict document visibility and download permissions by user role or group. Prevent unauthorized access to confidential clinical, regulatory, or commercial materials.'),
    array('icon' => 'clock', 'title' => 'Expiring Access Links', 'description' => 'Generate time-limited, single-use document links for external reviewers, auditors, or partners. Links expire automatically — no manual revocation needed.'),
    array('icon' => 'key', 'title' => 'SSO-Ready Architecture', 'description' => 'The REST API and role structure are designed to integrate with your existing Identity Provider (Azure AD, Okta, SAML 2.0). Custom integration scoping available for Enterprise customers.'),
    array('icon' => 'lock', 'title' => 'GDPR-Compliant by Design', 'description' => 'No third-party CDN dependencies for document delivery. Documents never leave your infrastructure. Full data processing transparency for DPA requirements.'),
    array('icon' => 'server', 'title' => 'Validated Deployment Support', 'description' => 'Enterprise customers receive deployment documentation, a configuration checklist, and optional validation support for regulated environments requiring IQ/OQ-style change documentation.'),
);

$use_cases = array(
    array('icon' => 'flask-conical', 'title' => 'Pharmaceutical & Life Sciences', 'description' => 'Securely manage regulatory submissions, SOPs, clinical study reports, and medical information documents. Control who can view, download, or share sensitive materials — with a full audit trail.', 'docs' => 'SOPs, CSRs, SmPC drafts, regulatory dossiers, training materials'),
    array('icon' => 'scale', 'title' => 'Legal & Compliance Teams', 'description' => 'Publish internal policies, contracts, and compliance documentation with enforced access controls. Generate expiring links for external counsel or auditors without exposing direct file URLs.', 'docs' => 'Policies, NDAs, audit reports, regulatory guidance documents'),
    array('icon' => 'landmark', 'title' => 'Finance & Corporate', 'description' => 'Distribute investor materials, annual reports, and internal financial documents with controlled access. Track who opened what and when — critical for disclosure documentation.', 'docs' => 'Annual reports, investor decks, board materials, financial statements'),
    array('icon' => 'graduation-cap', 'title' => 'Training & Knowledge Management', 'description' => 'Manage large libraries of training materials, e-learnings, and product documentation. Resume reading, progress tracking, and categorization keep learners engaged and organized.', 'docs' => 'Training modules, product manuals, onboarding materials'),
);

$enterprise_plans = array(
    array(
        'name' => 'Enterprise Starter',
        'price' => 'from $2,500 / year',
        'audience' => 'Single department or regional team deployments (up to 500 active users)',
        'features' => array('All Agency features', 'Unlimited sites', 'Lifetime updates', 'SLA: Response within 24 business hours', 'Email + Chat support', 'Onboarding session (1h)', 'Compliance documentation package'),
    ),
    array(
        'name' => 'Enterprise Professional',
        'price' => 'from $8,000 / year',
        'audience' => 'Multi-department or global intranet deployments (up to 5,000 active users)',
        'includes' => 'Everything in Enterprise Starter, plus:',
        'features' => array('Dedicated account manager', 'SSO integration support', 'SLA: Response within 8 business hours, 99.5% uptime', 'Quarterly business review', 'Custom onboarding & training workshop'),
    ),
    array(
        'name' => 'Enterprise Corporate',
        'price' => 'pricing on request',
        'audience' => 'Entire organization deployment, multi-instance, white-label, or OEM integration',
        'includes' => 'Everything in Enterprise Professional, plus:',
        'features' => array('Custom development (scoped)', 'Custom SLA terms', 'Full white-label / rebrand', 'OEM licensing for internal platforms', 'Source code escrow option', 'Named technical contact'),
    ),
);

$faqs = array(
    array('q' => 'Do you offer a formal vendor questionnaire / security assessment?', 'a' => 'Yes. Enterprise customers can submit a vendor security questionnaire (VSQ) or information security assessment form. We provide completed documentation within 5 business days.'),
    array('q' => 'Can you sign a Data Processing Agreement (DPA)?', 'a' => 'Yes. A standard DPA is available for all Enterprise plans. Custom DPA terms can be negotiated for Enterprise Professional and Corporate tiers.'),
    array('q' => 'Is the software validated for use in GxP-regulated environments?', 'a' => 'The software is designed with technical controls that support GxP-regulated workflows. Formal IQ/OQ validation documentation is available as an add-on for Enterprise Corporate customers. Validation responsibility remains with your organization.'),
    array('q' => 'Where is document data stored? Does anything go to third-party servers?', 'a' => 'No. PDF Embed & SEO Optimize operates entirely within your own WordPress or Drupal infrastructure. No documents are transmitted to external servers.'),
    array('q' => 'Can you integrate with our SSO provider (Azure AD, Okta)?', 'a' => "The plugin's role-based access system is compatible with WordPress/Drupal SSO plugins. For direct API-level SSO integration, this is scoped as custom development under Enterprise Professional and Corporate plans."),
    array('q' => 'What SLA do you offer?', 'a' => 'Enterprise Starter: 24-business-hour response. Enterprise Professional: 8-business-hour response with 99.5% uptime SLA. Enterprise Corporate: custom SLA terms including critical escalation paths.'),
    array('q' => 'Do you offer multi-year contracts?', 'a' => 'Yes. Multi-year agreements are available for Enterprise Professional and Corporate, typically with a 10–15% discount on annual pricing.'),
    array('q' => 'Has the Drupal module been reviewed by Acquia?', 'a' => 'Yes. The Drupal Enterprise module has been reviewed by <a href="https://www.acquia.com?ref=pdfviewer" target="_blank" rel="noopener" class="text-foreground hover:underline font-semibold">Acquia</a>, the leading Drupal enterprise platform. This review is available for the Enterprise module only.'),
);
?>

<!-- Hero -->
<section class="relative pt-8 pb-20 md:pt-12 lg:pt-16 lg:pb-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-accent/5 via-background to-background" aria-hidden="true"></div>
    <div class="container relative mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent/10 border border-accent/20 text-accent text-sm font-medium mb-8 animate-fade-in">
                <?php echo pdfviewer_icon('building-2', 16); ?>
                <?php esc_html_e('Enterprise', 'pdfviewer'); ?>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground leading-tight mb-6 animate-fade-in" style="animation-delay: 0.1s">
                <?php esc_html_e('PDF Management Built for', 'pdfviewer'); ?> <span class="text-gradient"><?php esc_html_e('Regulated Industries', 'pdfviewer'); ?></span>
            </h1>

            <p class="text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto mb-8 animate-fade-in" style="animation-delay: 0.2s">
                <?php esc_html_e('Secure, auditable, and compliance-ready PDF delivery for Pharmaceutical, Life Sciences, and Healthcare organizations. GDPR-compliant, audit-trail enabled, and IT-team friendly — on WordPress, Drupal, and React.', 'pdfviewer'); ?>
            </p>

            <div class="flex flex-wrap justify-center gap-3 mb-10 animate-fade-in" style="animation-delay: 0.3s">
                <?php foreach ($trust_badges as $badge) : ?>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-card border border-border text-sm">
                        <?php echo pdfviewer_icon($badge['icon'], 16, 'text-accent'); ?>
                        <span class="font-medium text-foreground"><?php echo esc_html($badge['text']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in" style="animation-delay: 0.4s">
                <a href="https://dross.net/contact/?topic=pdfviewer"
                   target="_blank" rel="noopener"
                   class="btn btn-lg bg-accent hover:bg-accent/90 text-accent-foreground shadow-glow gap-2"
                   title="<?php esc_attr_e('Request a personalized Enterprise demo and consultation', 'pdfviewer'); ?>"
                   aria-label="<?php esc_attr_e('Request Enterprise demo — schedule a consultation with our team', 'pdfviewer'); ?>">
                    <?php esc_html_e('Request Enterprise Demo', 'pdfviewer'); ?>
                    <?php echo pdfviewer_icon('arrow-right', 20); ?>
                </a>
                <button class="btn btn-outline btn-lg"
                        title="<?php esc_attr_e('Download the Enterprise compliance overview as PDF', 'pdfviewer'); ?>"
                        aria-label="<?php esc_attr_e('Download Compliance Overview document (PDF)', 'pdfviewer'); ?>">
                    <?php esc_html_e('Download Compliance Overview (PDF)', 'pdfviewer'); ?>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Problem Statement -->
<section class="py-20 lg:py-32 bg-card" aria-labelledby="problems-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="problems-heading" class="text-3xl md:text-4xl font-bold mb-6"><?php esc_html_e('Why Standard PDF Plugins Fall Short in Enterprise', 'pdfviewer'); ?></h2>
        </header>
        <div class="max-w-6xl mx-auto overflow-hidden rounded-2xl border border-border shadow-soft">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]" role="table">
                    <thead>
                        <tr class="bg-muted">
                            <th class="text-left py-3 sm:py-4 px-3 sm:px-6 font-semibold text-foreground text-xs sm:text-sm" scope="col"><?php esc_html_e('Challenge', 'pdfviewer'); ?></th>
                            <th class="text-left py-3 sm:py-4 px-3 sm:px-6 font-semibold text-destructive text-xs sm:text-sm" scope="col"><?php esc_html_e('What Goes Wrong', 'pdfviewer'); ?></th>
                            <th class="text-left py-3 sm:py-4 px-3 sm:px-6 font-semibold text-primary text-xs sm:text-sm" scope="col"><?php esc_html_e('Our Solution', 'pdfviewer'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pain_points as $i => $point) : ?>
                            <tr class="<?php echo $i % 2 === 0 ? 'bg-background' : 'bg-muted/30'; ?>">
                                <td class="py-3 sm:py-4 px-3 sm:px-6 font-semibold text-foreground text-xs sm:text-sm"><?php echo esc_html($point['challenge']); ?></td>
                                <td class="py-3 sm:py-4 px-3 sm:px-6 text-muted-foreground text-xs sm:text-sm"><?php echo esc_html($point['problem']); ?></td>
                                <td class="py-3 sm:py-4 px-3 sm:px-6 text-foreground text-xs sm:text-sm"><?php echo esc_html($point['solution']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Compliance -->
<section class="py-20 lg:py-32 bg-background" aria-labelledby="compliance-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-6">
            <h2 id="compliance-heading" class="text-3xl md:text-4xl font-bold mb-6"><?php esc_html_e('Designed with Compliance in Mind', 'pdfviewer'); ?></h2>
            <p class="text-lg text-muted-foreground"><?php esc_html_e('Organizations in pharmaceutical, medtech, and healthcare operate under strict documentation and data governance requirements. PDF Embed & SEO Optimize Pro provides the technical controls needed to support your compliance posture.', 'pdfviewer'); ?></p>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto mt-12">
            <?php foreach ($compliance_features as $i => $feat) : ?>
                <article class="p-6 rounded-2xl bg-card border border-border hover:border-accent/30 transition-all duration-300 animate-fade-in" style="animation-delay: <?php echo esc_attr($i * 0.08); ?>s">
                    <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                        <?php echo pdfviewer_icon($feat['icon'], 24, 'text-accent'); ?>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground mb-2"><?php echo esc_html($feat['title']); ?></h3>
                    <p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html($feat['description']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="max-w-3xl mx-auto mt-8 text-xs text-muted-foreground text-center italic"><?php esc_html_e('Note: PDF Embed & SEO Optimize is a software tool that supports your compliance processes. It does not certify compliance with 21 CFR Part 11, EU Annex 11, or other regulations. Compliance responsibility remains with your organization.', 'pdfviewer'); ?></p>
    </div>
</section>

<!-- Use Cases -->
<section class="py-20 lg:py-32 bg-card" aria-labelledby="usecases-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="usecases-heading" class="text-3xl md:text-4xl font-bold mb-6"><?php esc_html_e('Trusted by Teams Managing Critical Documents', 'pdfviewer'); ?></h2>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            <?php foreach ($use_cases as $i => $uc) : ?>
                <article class="p-6 rounded-2xl bg-background border border-border hover:border-primary/30 transition-all duration-300 animate-fade-in" style="animation-delay: <?php echo esc_attr($i * 0.1); ?>s">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <?php echo pdfviewer_icon($uc['icon'], 20, 'text-primary'); ?>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground"><?php echo esc_html($uc['title']); ?></h3>
                    </div>
                    <p class="text-sm text-muted-foreground leading-relaxed mb-3"><?php echo esc_html($uc['description']); ?></p>
                    <p class="text-xs text-muted-foreground italic"><?php echo esc_html('Common document types: ' . $uc['docs']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Enterprise Pricing -->
<section id="enterprise-pricing" class="py-20 lg:py-32 bg-background" aria-labelledby="ent-pricing-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-4">
            <h2 id="ent-pricing-heading" class="text-3xl md:text-4xl font-bold mb-6"><?php esc_html_e('Enterprise Plans — Built Around Your Scale', 'pdfviewer'); ?></h2>
            <p class="text-lg text-muted-foreground"><?php esc_html_e('All Enterprise plans include all Agency features, lifetime updates, and dedicated support. Pricing is annual unless otherwise agreed.', 'pdfviewer'); ?></p>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto mt-12">
            <?php foreach ($enterprise_plans as $i => $plan) : ?>
                <article class="p-6 rounded-2xl bg-card border border-border hover:border-accent/30 transition-all duration-300 animate-fade-in flex flex-col" style="animation-delay: <?php echo esc_attr($i * 0.1); ?>s">
                    <h3 class="text-xl font-bold text-foreground mb-1"><?php echo esc_html($plan['name']); ?></h3>
                    <div class="text-2xl font-bold text-accent mb-2"><?php echo esc_html($plan['price']); ?></div>
                    <p class="text-sm text-muted-foreground mb-4"><?php echo esc_html('Best for: ' . $plan['audience']); ?></p>
                    <?php if (!empty($plan['includes'])) : ?>
                        <p class="text-xs font-semibold text-accent uppercase tracking-wide mb-2"><?php echo esc_html($plan['includes']); ?></p>
                    <?php endif; ?>
                    <ul class="space-y-2 mb-6 flex-1 text-left pl-0 list-none">
                        <?php foreach ($plan['features'] as $feat) : ?>
                            <li class="flex items-start gap-2 text-sm">
                                <?php echo pdfviewer_icon('check', 16, 'text-accent shrink-0 mt-0.5'); ?>
                                <span class="text-foreground"><?php echo esc_html($feat); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php
                    $cta_text = $plan['name'] === 'Enterprise Corporate' ? 'Schedule a Discovery Call' : 'Request a Quote';
                    $cta_title = $cta_text . ' for the ' . $plan['name'] . ' plan';
                    $cta_label = $cta_text . ' — ' . $plan['name'] . ' (' . $plan['price'] . ')';
                    ?>
                    <a href="https://dross.net/contact/?topic=pdfviewer"
                       target="_blank" rel="noopener"
                       class="btn w-full mt-auto bg-accent hover:bg-accent/90 text-accent-foreground gap-2"
                       title="<?php echo esc_attr($cta_title); ?>"
                       aria-label="<?php echo esc_attr($cta_label); ?>">
                        <?php echo esc_html($cta_text); ?>
                        <?php echo pdfviewer_icon('arrow-right', 16); ?>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="max-w-2xl mx-auto mt-8 text-sm text-muted-foreground text-center">
            <?php esc_html_e('Not sure which plan fits?', 'pdfviewer'); ?>
            <a href="https://dross.net/contact/?topic=pdfviewer" target="_blank" rel="noopener" class="text-accent hover:underline" title="<?php esc_attr_e('Contact our Enterprise team for a tailored proposal', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('Contact us to discuss your Enterprise requirements', 'pdfviewer'); ?>"><?php esc_html_e('Contact us', 'pdfviewer'); ?></a>
            <?php esc_html_e('with a brief description of your use case and team size and we\'ll respond within one business day.', 'pdfviewer'); ?>
        </p>
    </div>
</section>

<!-- Social Proof -->
<section class="py-20 lg:py-32 bg-card" aria-labelledby="trust-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="trust-heading" class="text-3xl md:text-4xl font-bold mb-6"><?php esc_html_e('Trusted Across Regulated Environments', 'pdfviewer'); ?></h2>
        </header>
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16 mb-12">
            <div class="text-center"><div class="text-3xl font-bold text-foreground">2,000+</div><div class="text-sm text-muted-foreground"><?php esc_html_e('Active Pro Users', 'pdfviewer'); ?></div></div>
            <div class="text-center"><div class="text-3xl font-bold text-foreground">3 Platforms</div><div class="text-sm text-muted-foreground"><?php esc_html_e('WordPress, Drupal & React', 'pdfviewer'); ?></div></div>
            <div class="text-center"><div class="text-3xl font-bold text-foreground">GDPR</div><div class="text-sm text-muted-foreground"><?php esc_html_e('Compliant Infrastructure', 'pdfviewer'); ?></div></div>
            <div class="text-center"><div class="text-3xl font-bold text-foreground">30-day</div><div class="text-sm text-muted-foreground"><?php esc_html_e('Money-Back Guarantee', 'pdfviewer'); ?></div></div>
            <a href="https://www.acquia.com?ref=pdfviewer" target="_blank" rel="noopener" class="text-center hover:opacity-80 transition-opacity" title="<?php esc_attr_e('Visit Acquia — the leading Drupal enterprise platform', 'pdfviewer'); ?>"><div class="text-3xl font-bold text-foreground">Acquia</div><div class="text-sm text-muted-foreground"><?php esc_html_e('Reviewed Drupal Module', 'pdfviewer'); ?></div></a>
        </div>
        <div class="max-w-3xl mx-auto">
            <blockquote class="p-6 rounded-2xl bg-background border border-border">
                <p class="text-muted-foreground italic mb-3">"<?php esc_html_e('We needed a PDF solution that fit our compliance requirements without a six-figure budget. This delivered.', 'pdfviewer'); ?>"</p>
                <cite class="text-sm font-medium text-foreground not-italic">— <?php esc_html_e('IT Lead, European Pharmaceutical Company', 'pdfviewer'); ?></cite>
            </blockquote>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-20 lg:py-32 bg-background" aria-labelledby="ent-faq-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <header class="max-w-3xl mx-auto text-center mb-12">
            <h2 id="ent-faq-heading" class="text-3xl md:text-4xl font-bold mb-6"><?php esc_html_e('Enterprise FAQ', 'pdfviewer'); ?></h2>
        </header>
        <div class="max-w-3xl mx-auto space-y-4" role="list" aria-label="<?php esc_attr_e('Frequently asked questions about Enterprise licensing and compliance', 'pdfviewer'); ?>"
             itemscope itemtype="https://schema.org/FAQPage">
            <?php foreach ($faqs as $i => $faq) :
                $faq_id = sanitize_title(mb_substr($faq['q'], 0, 50));
            ?>
                <details
                    id="<?php echo esc_attr($faq_id); ?>"
                    class="group bg-card border border-border rounded-xl px-6 py-4 transition-colors open:shadow-soft open:border-primary/30"
                    role="listitem"
                    itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"
                >
                    <summary
                        class="flex items-center justify-between cursor-pointer text-left font-semibold text-foreground hover:text-primary list-none"
                        aria-label="<?php echo esc_attr($faq['q'] . ' - Click to expand answer'); ?>"
                        title="<?php echo esc_attr($faq['q']); ?>"
                        itemprop="name"
                    >
                        <?php echo esc_html($faq['q']); ?>
                        <span class="ml-4 shrink-0 transition-transform group-open:rotate-180" aria-hidden="true">
                            <?php echo pdfviewer_icon('chevron-down', 20); ?>
                        </span>
                    </summary>
                    <div class="mt-4 text-muted-foreground" role="region" aria-label="<?php echo esc_attr('Answer to: ' . $faq['q']); ?>"
                         itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <span itemprop="text"><?php echo wp_kses_post($faq['a']); ?></span>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
(function() {
    // Open FAQ item matching URL hash on page load
    var hash = window.location.hash.replace('#', '');
    if (hash) {
        var el = document.getElementById(hash);
        if (el && el.tagName === 'DETAILS') {
            el.open = true;
            setTimeout(function() { el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
        }
    }
    // Update URL hash when FAQ items are toggled
    document.querySelectorAll('[id] details, details[id]').forEach(function(details) {
        if (!details.id) return;
        details.addEventListener('toggle', function() {
            if (details.open) {
                history.replaceState(null, '', '#' + details.id);
            } else {
                history.replaceState(null, '', window.location.pathname);
            }
        });
    });
})();
</script>

<!-- Contact CTA -->
<section id="contact" class="py-20 lg:py-32 bg-background" aria-labelledby="contact-heading">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 id="contact-heading" class="text-3xl md:text-4xl font-bold mb-6"><?php esc_html_e("Let's Talk About Your Requirements", 'pdfviewer'); ?></h2>
            <p class="text-lg text-muted-foreground mb-10">
                <?php esc_html_e('Every enterprise deployment is different. Get in touch and a member of our team will reach out within one business day to understand your use case, answer compliance questions, and put together a tailored proposal.', 'pdfviewer'); ?>
            </p>
            <a href="https://dross.net/contact/?topic=pdfviewer" target="_blank" rel="noopener" title="<?php esc_attr_e('Contact us for Enterprise consultation', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('Contact us — discuss your Enterprise PDF management requirements', 'pdfviewer'); ?>" class="btn btn-lg bg-accent hover:bg-accent/90 text-accent-foreground gap-2 inline-flex">
                <?php esc_html_e('Contact Us', 'pdfviewer'); ?>
                <?php echo pdfviewer_icon('arrow-right', 20); ?>
            </a>
            <p class="mt-6 text-sm text-muted-foreground">
                <?php esc_html_e('Or', 'pdfviewer'); ?>
                <a href="https://dross.net/contact/?topic=pdfviewer" target="_blank" rel="noopener" class="text-accent hover:underline" title="<?php esc_attr_e('Send us a message through our Enterprise contact form', 'pdfviewer'); ?>" aria-label="<?php esc_attr_e('Send us a message via the contact form', 'pdfviewer'); ?>"><?php esc_html_e('send us a message', 'pdfviewer'); ?></a>
                <?php esc_html_e('directly through our contact form.', 'pdfviewer'); ?>
            </p>
        </div>
    </div>
</section>

<?php get_footer(); ?>
