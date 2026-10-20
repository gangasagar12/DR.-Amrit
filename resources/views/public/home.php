<?php
/**
 * Dynamic Homepage View
 * All sections render live data from the database.
 * 
 * @var array $doctor
 * @var array $services
 * @var array $qualifications
 * @var array $gallery
 * @var array $blogs
 * @var array $researches
 * @var array $videos
 * @var array $videoCategories
 * @var array $clinics
 * @var array $settings
 */
?>

<!-- ====================================================================
     2. Home / Hero Section
     ==================================================================== -->
<section class="hero-section" id="home">
  <div class="container hero-container">
    <div class="hero-content">
      <span class="badge-designation"><?= $e($doctor['professional_title'] ?? 'General Dentist') ?></span>
      <h1 class="hero-name"><?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></h1>
      
      <?php if (!empty($doctor['hero_heading'])): ?>
        <h2 style="font-size:1.4rem;font-weight:700;color:var(--deep-navy,#0f172a);margin-bottom:0.75rem;">
          <?= $e($doctor['hero_heading']) ?>
        </h2>
      <?php endif; ?>

      <p class="hero-lead">
        <?= $e(!empty($doctor['hero_lead']) ? $doctor['hero_lead'] : ($doctor['biography'] ?? 'Dedicated to providing patient-centered dental care with a focus on oral health, preventive dentistry, and comfortable treatment experiences.')) ?>
      </p>

      <?php if (!empty($doctor['hero_approach'])): ?>
        <p class="hero-approach">
          <?= $e($doctor['hero_approach']) ?>
        </p>
      <?php endif; ?>

      <div class="hero-actions">
        <a href="<?= $e(!empty($doctor['hero_btn1_link']) ? $doctor['hero_btn1_link'] : '#about') ?>" class="btn btn-primary">
          <?= $e(!empty($doctor['hero_btn1_text']) ? $doctor['hero_btn1_text'] : 'Explore My Profile') ?>
        </a>
        <a href="<?= $e(!empty($doctor['hero_btn2_link']) ? $doctor['hero_btn2_link'] : '#contact') ?>" class="btn btn-secondary">
          <?= $e(!empty($doctor['hero_btn2_text']) ? $doctor['hero_btn2_text'] : 'Contact Me') ?>
        </a>
      </div>
    </div>

    <!-- Right Column: Transparent Portrait of the Dentist -->
    <div class="hero-image-wrapper">
      <?php 
        $heroPortrait = !empty($doctor['hero_image']) ? $doctor['hero_image'] : (!empty($doctor['profile_image']) ? $doctor['profile_image'] : '/img/doctor-cutout.png');
        if (!str_starts_with($heroPortrait, 'http') && !str_starts_with($heroPortrait, '/')) {
            $heroPortrait = '/' . $heroPortrait;
        }
      ?>
      <img 
        src="<?= $e($heroPortrait) ?>" 
        alt="<?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> portrait" 
        class="hero-portrait"
        width="560" 
        height="680"
        fetchpriority="high"
      >
    </div>
  </div>
</section>

<!-- ====================================================================
     3. About / Introduction Section with Education Highlights
     ==================================================================== -->
<section class="section section-about" id="about">
  <div class="container">
    <div class="section-title-wrap">
      <span class="section-kicker"><?= $e($doctor['about_kicker'] ?? 'Professional Background') ?></span>
      <h2 class="section-heading">About <?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></h2>
    </div>

    <div class="about-grid">
      <!-- Left Column: Professional Photo with Transparent Cutout Treatment -->
      <div class="about-image-column">
        <div class="about-photo-wrapper">
          <?php 
            $aboutPortrait = !empty($doctor['about_image']) ? $doctor['about_image'] : '/img/doctor-clinic.jpg';
            if (!str_starts_with($aboutPortrait, 'http') && !str_starts_with($aboutPortrait, '/')) {
                $aboutPortrait = '/' . $aboutPortrait;
            }
          ?>
          <img 
            src="<?= $e($aboutPortrait) ?>" 
            alt="<?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> in clinical practice" 
            class="about-portrait"
            loading="lazy"
            width="480" 
            height="600"
          >
          <div class="about-photo-badge">
            <div class="about-badge-icon">
              <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <div class="about-badge-text">
              <strong class="about-badge-title"><?= $e($doctor['about_badge_title'] ?? ($doctor['experience_years'] . '+ Years Clinical Care')) ?></strong>
              <span class="about-badge-subtitle"><?= $e($doctor['about_badge_subtitle'] ?? 'Oral & Dental Surgery') ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Introduction & Background -->
      <div class="about-content-column">
        <h3 class="about-subheading"><?= $e($doctor['about_subheading'] ?? 'Committed to Patient Comfort, Ethics & Clinical Precision') ?></h3>
        
        <?php 
          $bioText = $doctor['biography'] ?? '';
          $bioParagraphs = array_filter(array_map('trim', preg_split('/\r?\n\r?\n/', $bioText)));
          if (empty($bioParagraphs)) {
              $bioParagraphs = [
                  "Dr. Amrit Jaishi completed his Bachelor of Dental Surgery (BDS) from B.P. Koirala Institute of Health Sciences (BPKIHS Dharan), one of Nepal's foremost autonomous medical universities.",
                  "Serving as an Oral and Dental Surgeon at Dhangadhi Sanjeevani Hospital in Farwest Nepal, he brings over four years of clinical experience to daily patient care."
              ];
          }
          foreach ($bioParagraphs as $p):
        ?>
          <p class="about-text"><?= $e($p) ?></p>
        <?php endforeach; ?>

        <!-- Short Education Highlights Subsection -->
        <?php if (!empty($qualifications)): ?>
          <div class="education-highlights-block">
            <h4 class="highlights-title">Education Highlights</h4>
            <div class="highlights-grid" id="aboutHighlightsGrid">
              <?php foreach (array_slice($qualifications, 0, 3) as $q): ?>
                <?php 
                  $yr = !empty($q['completion_year']) ? (!empty($q['start_year']) ? ($q['start_year'] . ' – ' . $q['completion_year']) : $q['completion_year']) : 'Certified';
                ?>
                <div class="highlight-item">
                  <span class="highlight-degree"><?= $e($q['degree']) ?></span>
                  <span class="highlight-institution"><?= $e($q['institution']) ?><?= !empty($q['location']) ? (', ' . $e($q['location'])) : '' ?></span>
                  <span class="highlight-year"><?= $e($yr) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================================
     4. Dental Services Section
     ==================================================================== -->
<section class="section section-services" id="services">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Areas of Practice</span>
      <h2 class="section-heading">Dental Services</h2>
      <p class="section-subtext">Evidence-based preventive, restorative, and surgical dental treatments delivered with gentle precision.</p>
    </div>

    <!-- 3 cards per row on desktop -->
    <div class="services-grid">
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): ?>
          <?php 
            $sImg = !empty($service['image']) ? $service['image'] : '/img/services/teeth_cleaning.jpg';
            if (!str_starts_with($sImg, 'http') && !str_starts_with($sImg, '/')) {
                $sImg = '/' . $sImg;
            }
          ?>
          <article class="service-card">
            <div class="service-media">
              <img src="<?= $e($sImg) ?>" alt="<?= $e($service['name']) ?>" class="service-thumb" loading="lazy">
            </div>
            <div class="service-content">
              <h3 class="service-name">
                <a href="/services/<?= $e($service['slug']) ?>" style="color:inherit;text-decoration:none;"><?= $e($service['name']) ?></a>
              </h3>
              <p class="service-summary"><?= $e($service['short_description'] ?? '') ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align:center;color:var(--text-muted);grid-column:1/-1;">No dental services listed yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ====================================================================
     5. Education and Qualifications Section (Left & Right Cycle Timeline)
     ==================================================================== -->
<section class="section section-education" id="education">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Academic Journey & Clinical Milestones</span>
      <h2 class="section-heading">Education & Qualifications</h2>
      <p class="section-subtext">A chronological roadmap of formal medical university training, national clinical licensing, and specialized dental masteries.</p>
    </div>

    <div class="education-timeline-wrapper">
      <div class="education-timeline">
        <div class="timeline-center-line" aria-hidden="true"></div>

        <?php if (!empty($qualifications)): ?>
          <?php foreach ($qualifications as $idx => $q): ?>
            <?php 
              $isLeft = ($idx % 2 === 0);
              $sideClass = $isLeft ? 'timeline-left' : 'timeline-right';
              $stepNumber = str_pad((string)($idx + 1), 2, '0', STR_PAD_LEFT);
              $dLower = strtolower($q['degree'] ?? '');

              $badgeClass = 'badge-degree';
              $badgeLabel = 'University Degree';
              $iconSvg = '<path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path>';

              if (str_contains($dLower, 'license') || str_contains($dLower, 'council') || str_contains($dLower, 'nmc') || str_contains($dLower, 'registered')) {
                  $badgeClass = 'badge-licensure';
                  $badgeLabel = 'National Licensure';
                  $iconSvg = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline>';
              } elseif (str_contains($dLower, 'sterilization') || str_contains($dLower, 'safety') || str_contains($dLower, 'infection') || str_contains($dLower, 'governance')) {
                  $badgeClass = 'badge-safety';
                  $badgeLabel = 'Clinical Governance';
                  $iconSvg = '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path><path d="M12 5v14M5 12h14"></path>';
              } elseif (str_contains($dLower, 'rotary') || str_contains($dLower, 'endodontic') || str_contains($dLower, 'workshop') || str_contains($dLower, 'restoration')) {
                  $badgeClass = 'badge-workshop';
                  $badgeLabel = 'Specialized Workshop';
                  $iconSvg = '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>';
              } elseif (str_contains($dLower, 'outreach') || str_contains($dLower, 'screening') || str_contains($dLower, 'community')) {
                  $badgeClass = 'badge-outreach';
                  $badgeLabel = 'Community Health';
                  $iconSvg = '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>';
              } elseif (str_contains($dLower, 'consultant') || str_contains($dLower, 'hospital') || str_contains($dLower, 'practice')) {
                  $badgeClass = 'badge-practice';
                  $badgeLabel = 'Clinical Practice';
                  $iconSvg = '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line>';
              }

              $yearText = !empty($q['completion_year']) ? (!empty($q['start_year']) ? ($q['start_year'] . ' – ' . $q['completion_year']) : $q['completion_year']) : 'Certified';
            ?>
            <div class="timeline-item <?= $sideClass ?>">
              <div class="timeline-marker" aria-label="Milestone <?= $stepNumber ?>">
                <div class="marker-circle">
                  <svg class="marker-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <?= $iconSvg ?>
                  </svg>
                </div>
                <span class="marker-step"><?= $stepNumber ?></span>
              </div>
              <article class="timeline-card">
                <div class="timeline-card-header">
                  <span class="timeline-badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                  <span class="timeline-period">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <?= $e($yearText) ?>
                  </span>
                </div>
                <h3 class="timeline-title"><?= $e($q['degree']) ?></h3>
                <div class="timeline-meta">
                  <span class="timeline-inst">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4"></path><line x1="9" y1="21" x2="9" y2="11"></line><line x1="15" y1="21" x2="15" y2="11"></line></svg>
                    <?= $e($q['institution']) ?>
                  </span>
                  <?php if (!empty($q['location'])): ?>
                    <span class="timeline-loc">
                      <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                      <?= $e($q['location']) ?>
                    </span>
                  <?php endif; ?>
                </div>
                <?php if (!empty($q['description'])): ?>
                  <p class="timeline-desc"><?= $e($q['description']) ?></p>
                <?php endif; ?>
              </article>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================================
     6. Professional Career Gallery
     ==================================================================== -->
<section class="section section-gallery" id="gallery">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Clinical & Outreach Moments</span>
      <h2 class="section-heading">Professional Career Gallery</h2>
      <p class="section-subtext">Photographs from clinical consultations, surgical procedures, workshops, and community health camps.</p>
    </div>

    <div class="gallery-filters" role="tablist" aria-label="Gallery category filters">
      <button type="button" class="filter-btn active" data-filter="all" role="tab" aria-selected="true">All</button>
      <button type="button" class="filter-btn" data-filter="clinical-practice" role="tab" aria-selected="false">Clinical Practice</button>
      <button type="button" class="filter-btn" data-filter="training" role="tab" aria-selected="false">Training</button>
      <button type="button" class="filter-btn" data-filter="conferences" role="tab" aria-selected="false">Conferences</button>
      <button type="button" class="filter-btn" data-filter="achievements" role="tab" aria-selected="false">Achievements</button>
    </div>

    <div class="gallery-grid" id="galleryGrid">
      <?php if (!empty($gallery)): ?>
        <?php foreach ($gallery as $g): ?>
          <?php 
            $catRaw = $g['category'] ?? 'clinical-practice';
            $catSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $catRaw));
            $gPath = $g['image_path'];
            if (!str_starts_with($gPath, 'http') && !str_starts_with($gPath, '/')) {
                $gPath = '/' . $gPath;
            }
          ?>
          <figure class="gallery-card" data-category="<?= $e($catSlug) ?>" tabindex="0" role="button" aria-label="View <?= $e($g['image_title'] ?? 'Gallery image') ?>">
            <img src="<?= $e($gPath) ?>" alt="<?= $e($g['image_title'] ?? 'Gallery image') ?>" class="gallery-thumb" loading="lazy">
            <figcaption class="gallery-overlay">
              <span class="gallery-title"><?= $e($g['image_title'] ?? $g['category'] ?? 'Gallery') ?></span>
              <span class="gallery-desc-hidden"><?= $e($g['caption'] ?? '') ?></span>
            </figcaption>
          </figure>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <nav class="section-pagination" id="galleryPagination" aria-label="Gallery Pages"></nav>
  </div>
</section>

<!-- ====================================================================
     6.5 Dental Video Gallery Section
     ==================================================================== -->
<section class="section section-videos" id="videos">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Patient Education & Clinical Walkthroughs</span>
      <h2 class="section-heading">Video Gallery</h2>
      <p class="section-subtext">Informative dental videos, oral health guides, and procedure demonstrations presented by Dr. Amrit Jaishi.</p>
    </div>

    <div class="video-filters" role="tablist" aria-label="Video category filter">
      <button type="button" class="video-filter-btn active" data-filter="all" role="tab" aria-selected="true">All Videos</button>
      <?php if (!empty($videoCategories)): ?>
        <?php foreach ($videoCategories as $cat): ?>
          <button type="button" class="video-filter-btn" data-filter="<?= $e($cat['slug']) ?>" role="tab" aria-selected="false"><?= $e($cat['name']) ?></button>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="videos-grid" id="videosGrid">
      <?php if (!empty($videos)): ?>
        <?php foreach ($videos as $v): ?>
          <?php 
            $catSlug = $v['category_slug'] ?? '';
            $catName = $v['category_name'] ?? 'Dental Video';
            $duration = $v['duration'] ?? '04:00';
            $videoSrc = ($v['video_type'] ?? '') === 'upload' ? ($v['video_file'] ?? '') : ($v['video_url'] ?? '');
            $thumb = !empty($v['thumbnail']) ? $v['thumbnail'] : '/img/services/teeth_cleaning.jpg';
            if (!str_starts_with($thumb, 'http') && !str_starts_with($thumb, '/')) {
                $thumb = '/' . $thumb;
            }
            $pubDate = !empty($v['published_at']) ? date('M Y', strtotime($v['published_at'])) : '';
          ?>
          <article class="video-card" 
                   data-category="<?= $e($catSlug) ?>" 
                   data-video-url="<?= $e($videoSrc) ?>" 
                   data-video-type="<?= $e($v['video_type'] ?? 'youtube') ?>" 
                   data-title="<?= $e($v['title']) ?>" 
                   data-desc="<?= $e($v['description'] ?? '') ?>" 
                   data-category-name="<?= $e($catName) ?>" 
                   data-duration="<?= $e($duration) ?>" 
                   tabindex="0" 
                   role="button" 
                   aria-label="Watch video: <?= $e($v['title']) ?>">
            <div class="video-media">
              <img src="<?= $e($thumb) ?>" alt="<?= $e($v['title']) ?> thumbnail" class="video-thumb" loading="lazy">
              <div class="video-play-btn" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
              </div>
              <span class="video-duration"><?= $e($duration) ?></span>
            </div>
            <div class="video-body">
              <div class="video-meta">
                <span class="video-cat-badge"><?= $e($catName) ?></span>
                <?php if ($pubDate): ?>
                  <span class="video-date"><?= $e($pubDate) ?></span>
                <?php endif; ?>
              </div>
              <h3 class="video-title"><?= $e($v['title']) ?></h3>
              <p class="video-desc"><?= $e($v['description'] ?? '') ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <nav class="video-pagination" id="videoPagination" aria-label="Video Gallery Pages"></nav>
  </div>
</section>

<!-- ====================================================================
     7. Dental Health Blog Section
     ==================================================================== -->
<section class="section section-blog" id="blog">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Patient Education & Advice</span>
      <h2 class="section-heading">Dental Health Blog</h2>
      <p class="section-subtext">Evidence-based oral care guidance, preventive tips, and clear answers to common dental questions.</p>
    </div>

    <div class="articles-grid">
      <?php if (!empty($blogs)): ?>
        <?php foreach ($blogs as $b): ?>
          <?php 
            $catName = $b['category_name'] ?? 'Oral Care';
            $dateStr = !empty($b['published_at']) ? date('F Y', strtotime($b['published_at'])) : 'Recent';
            $bThumb = !empty($b['featured_image']) ? $b['featured_image'] : '/img/services/teeth_cleaning.jpg';
            if (!str_starts_with($bThumb, 'http') && !str_starts_with($bThumb, '/')) {
                $bThumb = '/' . $bThumb;
            }
            $articleUrl = !empty($b['slug']) ? ('/blog/' . $b['slug']) : '/blog';
          ?>
          <article class="article-grid-card">
            <div class="grid-card-media">
              <a href="<?= $e($articleUrl) ?>" aria-label="Read article on <?= $e($b['title']) ?>">
                <img src="<?= $e($bThumb) ?>" alt="<?= $e($b['title']) ?>" class="grid-card-img" loading="lazy">
              </a>
            </div>
            <div class="grid-card-body">
              <div class="grid-card-meta">
                <span class="meta-tag"><?= $e($catName) ?></span>
                <span class="meta-date"><?= $e($dateStr) ?></span>
              </div>
              <h3 class="grid-card-title">
                <a href="<?= $e($articleUrl) ?>" class="article-title-link"><?= $e($b['title']) ?></a>
              </h3>
              <p class="grid-card-desc"><?= $e($b['excerpt'] ?? '') ?></p>
              <div class="grid-card-footer">
                <a href="<?= $e($articleUrl) ?>" class="read-link-btn">Read Article &rarr;</a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <nav class="section-pagination" id="blogPagination" aria-label="Dental Blog Pages"></nav>
  </div>
</section>

<!-- ====================================================================
     8. Research and Publications Section
     ==================================================================== -->
<section class="section section-research" id="research">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Academic & Clinical Science</span>
      <h2 class="section-heading">Research & Publications</h2>
      <p class="section-subtext">Original observational clinical studies and community health epidemiological surveys conducted in Nepal.</p>
    </div>

    <div class="research-grid">
      <?php if (!empty($researches)): ?>
        <?php foreach ($researches as $r): ?>
          <?php 
            $rThumb = !empty($r['cover_image']) ? $r['cover_image'] : '/img/services/rct.jpg';
            if (!str_starts_with($rThumb, 'http') && !str_starts_with($rThumb, '/')) {
                $rThumb = '/' . $rThumb;
            }
          ?>
          <article class="research-wide-card">
            <div class="research-media-wrap">
              <img src="<?= $e($rThumb) ?>" alt="<?= $e($r['title']) ?>" class="research-card-img" loading="lazy">
            </div>
            <div class="research-body">
              <div class="research-meta">
                <span class="research-badge">Peer-Reviewed Paper</span>
                <span class="research-year"><?= $e($r['publication_year'] ?? '') ?></span>
              </div>
              <div class="research-journal"><?= $e($r['journal_name'] ?? '') ?></div>
              <h3 class="research-title"><?= $e($r['title']) ?></h3>
              <p class="research-excerpt"><?= $e($r['summary'] ?? ($r['abstract'] ?? '')) ?></p>
              <div class="research-footer">
                <?php if (!empty($r['publication_url'])): ?>
                  <a href="<?= $e($r['publication_url']) ?>" target="_blank" rel="noopener noreferrer" class="read-link-btn">
                    View Paper Online &rarr;
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <nav class="section-pagination" id="researchPagination" aria-label="Research Pages"></nav>
  </div>
</section>

<!-- ====================================================================
     8. Clinic Locations and Professional Affiliations
     ==================================================================== -->
<section class="section section-clinics" id="clinics">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Where I Practice</span>
      <h2 class="section-heading">Clinic Locations</h2>
      <p class="section-subtext">Official clinical centers where Dr. Amrit Jaishi conducts consultations and treatments.</p>
    </div>

    <div class="clinics-grid">
      <?php if (!empty($clinics)): ?>
        <?php foreach ($clinics as $c): ?>
          <?php 
            $isPrimary = (($c['workplace_type'] ?? '') === 'Permanent Workplace') || (($c['display_order'] ?? 0) == 1);
            $badgeClass = $isPrimary ? 'clinic-badge' : 'clinic-badge-secondary';
            $badgeText = $isPrimary ? 'Primary Practice' : ($c['workplace_type'] ?? 'Community Affiliation');
            $cImg = !empty($c['image']) ? $c['image'] : '/img/clinics/dhangadhi_sanjeevani_hospital.jpg';
            if (!str_starts_with($cImg, 'http') && !str_starts_with($cImg, '/')) {
                $cImg = '/' . $cImg;
            }
            $mapUrl = !empty($c['map_url']) ? $c['map_url'] : ('https://maps.google.com/?q=' . urlencode(($c['clinic_name'] ?? '') . ' ' . ($c['city'] ?? '')));
          ?>
          <article class="clinic-card <?= $isPrimary ? 'primary-clinic' : 'affiliation-clinic' ?>">
            <div class="clinic-media-wrap">
              <img src="<?= $e($cImg) ?>" alt="<?= $e($c['clinic_name']) ?>" class="clinic-img" loading="lazy">
              <div class="clinic-badge-overlay">
                <span class="<?= $badgeClass ?>"><?= $e($badgeText) ?></span>
              </div>
            </div>

            <div class="clinic-body">
              <h3 class="clinic-name"><?= $e($c['clinic_name']) ?></h3>
              <p class="clinic-affiliation"><strong>Affiliation & Role:</strong> <?= $e($c['doctor_role'] ?? 'Consultant Dental Surgeon') ?></p>
              
              <div class="clinic-info-list">
                <div class="clinic-info-item">
                  <span class="clinic-info-label">Full Address</span>
                  <span class="clinic-info-val"><?= $e($c['full_address']) ?></span>
                </div>
                <?php if (!empty($c['city'])): ?>
                  <div class="clinic-info-item">
                    <span class="clinic-info-label">Locality / City</span>
                    <span class="clinic-info-val"><?= $e($c['city']) ?></span>
                  </div>
                <?php endif; ?>
                <div class="clinic-info-item">
                  <span class="clinic-info-label">Contact Numbers</span>
                  <span class="clinic-info-val"><?= $e($c['phone'] ?? '+977-9860644282') ?></span>
                </div>
                <div class="clinic-info-item">
                  <span class="clinic-info-label">Consultation Hours</span>
                  <span class="clinic-info-val"><?= $e($c['working_days'] ?? 'Sunday – Friday') ?>: <?= $e($c['working_hours'] ?? '10:00 AM – 5:00 PM') ?></span>
                </div>
              </div>

              <div class="clinic-card-footer">
                <a href="<?= $e($mapUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-directions">Get Directions</a>
                <?php if (!empty($c['phone'])): ?>
                  <a href="tel:<?= preg_replace('/[^0-9+]/', '', $c['phone']) ?>" class="btn btn-call-clinic">Call Clinic</a>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ====================================================================
     9. Contact Section
     ==================================================================== -->
<section class="section section-contact" id="contact">
  <div class="container">
    <div class="section-title-wrap text-center">
      <span class="section-kicker">Get in Touch</span>
      <h2 class="section-heading">Contact</h2>
      <p class="section-subtext">For professional inquiries, hospital consultations, or clinical appointments.</p>
    </div>

    <div class="contact-layout">
      <!-- Left Column: Contact Information -->
      <div class="contact-info-block">
        <h3 class="contact-block-heading">Professional Inquiries</h3>
        <p class="contact-intro">
          Please feel free to reach out directly via phone or email. Patients seeking clinical consultations may also visit Dhangadhi Sanjeevani Hospital during scheduled outpatient hours.
        </p>

        <div class="contact-list">
          <div class="contact-entry">
            <span class="entry-label">Professional Email</span>
            <a href="mailto:<?= $e($doctor['contact_email'] ?? 'amritjaishi0@gmail.com') ?>" class="entry-val" id="contactEmailVal">
              <?= $e($doctor['contact_email'] ?? 'amritjaishi0@gmail.com') ?>
            </a>
          </div>

          <div class="contact-entry">
            <span class="entry-label">Contact Number</span>
            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $doctor['phone'] ?? '+9779860644282') ?>" class="entry-val" id="contactPhoneVal">
              <?= $e($doctor['phone'] ?? '+977-9860644282') ?>
            </a>
            <?php if (!empty($doctor['emergency_contact'])): ?>
              <span class="entry-sub" id="contactEmergencyVal">Emergency: <?= $e($doctor['emergency_contact']) ?></span>
            <?php endif; ?>
          </div>

          <div class="contact-entry">
            <span class="entry-label">Primary Clinic Location</span>
            <span class="entry-val" id="contactLocationVal"><?= $e($settings['clinic_address'] ?? 'Dhangadhi Sanjeevani Hospital') ?></span>
          </div>

          <div class="contact-entry">
            <span class="entry-label">Consultation Hours</span>
            <span class="entry-val" id="contactHoursVal"><?= $e($settings['working_hours'] ?? 'Sunday – Friday: 10:00 AM – 5:00 PM') ?></span>
          </div>
        </div>

        <div class="contact-quick-buttons">
          <a href="tel:<?= preg_replace('/[^0-9+]/', '', $doctor['phone'] ?? '+9779860644282') ?>" class="btn btn-primary" id="quickCallBtn">Call Directly</a>
          <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $doctor['whatsapp'] ?? '9779860644282') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" id="quickWhatsappBtn">Message on WhatsApp</a>
        </div>
      </div>

      <!-- Right Column: Functional Contact Form -->
      <div class="contact-form-block">
        <h3 class="contact-block-heading">Send a Message</h3>
        <p class="form-disclaimer">
          This form is for general inquiries. For urgent dental emergencies, please call the hospital reception or emergency contact line directly.
        </p>

        <form id="contactForm" class="clean-form" novalidate>
          <div class="form-group">
            <label for="contactName" class="form-label">Full Name <span class="required">*</span></label>
            <input type="text" id="contactName" name="name" class="form-input" placeholder="e.g. Ramesh Sharma" required autocomplete="name">
            <span class="form-error" id="nameError"></span>
          </div>

          <div class="form-group">
            <label for="contactEmail" class="form-label">Email Address <span class="required">*</span></label>
            <input type="email" id="contactEmail" name="email" class="form-input" placeholder="e.g. ramesh@example.com" required autocomplete="email">
            <span class="form-error" id="emailError"></span>
          </div>

          <div class="form-group">
            <label for="contactSubject" class="form-label">Subject <span class="required">*</span></label>
            <input type="text" id="contactSubject" name="subject" class="form-input" placeholder="e.g. Clinical Inquiry / Dental Consultation" required>
            <span class="form-error" id="subjectError"></span>
          </div>

          <div class="form-group">
            <label for="contactMessage" class="form-label">Message <span class="required">*</span></label>
            <textarea id="contactMessage" name="message" class="form-textarea" rows="4" placeholder="How can Dr. Amrit Jaishi assist you?" required></textarea>
            <span class="form-error" id="messageError"></span>
          </div>

          <div class="form-feedback" id="formFeedback" role="alert" aria-live="polite"></div>

          <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
            <span class="btn-text">Submit Message</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Gallery Lightbox Modal -->
<div class="gallery-modal-overlay" id="galleryModal" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
  <div class="gallery-modal-dialog">
    <button type="button" class="gallery-close-btn" id="galleryCloseBtn" aria-label="Close image viewer">&times;</button>
    <div class="gallery-modal-body">
      <img src="" alt="" id="galleryModalImg" class="gallery-modal-image">
      <div class="gallery-modal-caption">
        <span class="gallery-modal-category" id="galleryModalCategory"></span>
        <h3 class="gallery-modal-title" id="galleryModalTitle"></h3>
        <p class="gallery-modal-desc" id="galleryModalDesc"></p>
      </div>
    </div>
  </div>
</div>

<!-- Video Player Modal -->
<div class="video-modal-overlay" id="videoModal" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
  <div class="video-modal-dialog">
    <button type="button" class="video-modal-close" id="closeVideoModal" aria-label="Close video player">&times;</button>
    <div class="video-modal-player-wrap" id="videoModalPlayer"></div>
    <div class="video-modal-content">
      <div class="video-modal-meta">
        <span class="video-modal-cat" id="videoModalCategory"></span>
        <span class="video-modal-duration" id="videoModalDuration"></span>
      </div>
      <h3 class="video-modal-title" id="videoModalTitle"></h3>
      <p class="video-modal-desc" id="videoModalDesc"></p>
    </div>
  </div>
</div>
