<!-- 404 Error Page -->
<section class="section" style="padding: 6rem 0; text-align: center;">
  <div class="container container-narrow">
    <div style="width: 80px; height: 80px; background: var(--teal-light); color: var(--primary-teal); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
      <i data-lucide="help-circle" style="width: 44px; height: 44px;"></i>
    </div>
    <h1 style="font-size: 3rem; margin-bottom: 0.5rem; color: var(--deep-navy);">404 - Page Not Found</h1>
    <p style="font-size: 1.15rem; color: var(--text-muted); max-width: 500px; margin: 0 auto 2rem auto;">
      The page or dental publication you requested could not be located or may have been moved.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
      <a href="<?= $url('/') ?>" class="btn btn-primary btn-lg">
        <i data-lucide="home" style="width: 18px; height: 18px;"></i> Return to Homepage
      </a>
      <a href="<?= $url('/services') ?>" class="btn btn-secondary btn-lg">
        <i data-lucide="activity" style="width: 18px; height: 18px;"></i> Explore Treatments
      </a>
    </div>
  </div>
</section>
