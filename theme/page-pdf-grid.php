<?php
/**
 * Template Name: PDF Grid
 * PDF archive grid view - matches /pdf/ design without widget area
 *
 * @package PDFViewer
 */

get_header();

$pdfs = array(
    array(
        'title'     => 'In-Page PDF with Download & Print',
        'url'       => home_url('/pdf/example-1/'),
        'thumbnail' => home_url('/wp-content/uploads/2026/01/pdf-thumbnail-851-1769686624-212x300.jpg'),
        'date'      => '29. January 2026',
    ),
    array(
        'title'     => 'In-Page PDF without Download/Print',
        'url'       => home_url('/pdf/example-2/'),
        'thumbnail' => home_url('/wp-content/uploads/2026/01/pdf-thumbnail-855-1769686773-212x300.jpg'),
        'date'      => '29. January 2026',
    ),
    array(
        'title'     => 'Standalone PDF with Download & Print',
        'url'       => home_url('/pdf/example-3/'),
        'thumbnail' => home_url('/wp-content/uploads/2026/01/pdf-thumbnail-865-1769701722-212x300.jpg'),
        'date'      => '29. January 2026',
    ),
    array(
        'title'     => 'Standalone PDF without Download/Print',
        'url'       => home_url('/pdf/example-4/'),
        'thumbnail' => home_url('/wp-content/uploads/2026/01/pdf-thumbnail-868-1769701794-212x300.jpg'),
        'date'      => '29. January 2026',
    ),
    array(
        'title'     => 'In-Page PDF with Password Protection',
        'url'       => home_url('/pdf/example-5/'),
        'thumbnail' => home_url('/wp-content/uploads/2026/01/pdf-thumbnail-871-1769701873-212x300.jpg'),
        'date'      => '29. January 2026',
    ),
    array(
        'title'     => 'Standalone PDF with Password Protection',
        'url'       => home_url('/pdf/example-6/'),
        'thumbnail' => home_url('/wp-content/uploads/2026/01/pdf-thumbnail-874-1769701971-212x300.jpg'),
        'date'      => '29. January 2026',
    ),
);
?>

<main id="main-content" class="pdf-grid-page">
    <!-- Header Section -->
    <header class="pdf-grid-header">
        <h1 class="pdf-grid-title"><?php esc_html_e('PDF Documents', 'pdfviewer'); ?></h1>
        <p class="pdf-grid-subtitle"><?php esc_html_e('Browse all available PDF documents.', 'pdfviewer'); ?></p>
    </header>

    <!-- PDF Grid -->
    <section class="pdf-grid-container" aria-label="<?php esc_attr_e('PDF Documents Grid', 'pdfviewer'); ?>">
        <div class="pdf-grid">
            <?php foreach ($pdfs as $pdf) : ?>
                <article class="pdf-card">
                    <a href="<?php echo esc_url($pdf['url']); ?>" class="pdf-card-thumbnail-link">
                        <img
                            src="<?php echo esc_url($pdf['thumbnail']); ?>"
                            alt="<?php echo esc_attr($pdf['title']); ?>"
                            class="pdf-card-thumbnail"
                            loading="lazy"
                        />
                    </a>
                    <div class="pdf-card-content">
                        <h2 class="pdf-card-title">
                            <a href="<?php echo esc_url($pdf['url']); ?>">
                                <?php echo esc_html($pdf['title']); ?>
                            </a>
                        </h2>
                        <time class="pdf-card-date" datetime="2026-01-29">
                            <?php echo esc_html($pdf['date']); ?>
                        </time>
                        <a href="<?php echo esc_url($pdf['url']); ?>" class="pdf-card-button">
                            <?php esc_html_e('View PDF', 'pdfviewer'); ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
/* PDF Grid Page Styles - matches /pdf/ archive design without sidebar */
.pdf-grid-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem 4rem;
}

.pdf-grid-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.pdf-grid-title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.25rem;
    font-weight: 700;
    color: #1e3a5f;
    margin: 0 0 0.5rem;
}

.pdf-grid-subtitle {
    font-size: 1.125rem;
    color: #475569; /* slate-600 - WCAG AA compliant */
    margin: 0;
}

.pdf-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.pdf-card {
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: box-shadow 0.2s ease;
}

.pdf-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.pdf-card-thumbnail-link {
    display: block;
}

.pdf-card-thumbnail {
    width: 100%;
    height: auto;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    display: block;
}

.pdf-card-content {
    padding: 1rem 1.25rem 1.25rem;
}

.pdf-card-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.125rem;
    font-weight: 600;
    line-height: 1.4;
    margin: 0 0 0.25rem;
    color: #1e3a5f;
}

.pdf-card-title a {
    color: inherit;
    text-decoration: none;
}

.pdf-card-title a:hover {
    color: #2563eb;
}

.pdf-card-date {
    display: block;
    font-size: 0.875rem;
    color: #64748b; /* slate-500 - WCAG AA compliant */
    margin-bottom: 1rem;
}

.pdf-card-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #1a5a8a;
    color: #fff;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pdf-card-button:hover {
    background-color: #164e7a;
    color: #fff;
}

.pdf-card-button:focus {
    outline: 2px solid #1a5a8a;
    outline-offset: 2px;
}

.pdf-card-button:active {
    background-color: #0f3d5c;
}

/* Responsive */
@media (max-width: 1024px) {
    .pdf-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .pdf-grid-page {
        padding: 1.5rem 1rem 3rem;
    }

    .pdf-grid-title {
        font-size: 1.75rem;
    }

    .pdf-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php get_footer(); ?>
