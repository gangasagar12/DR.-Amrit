<?php
/** @var array $categories */
/** @var array $videos */
?>

<!-- Header Banner -->
<section class="section section-subtle" style="padding: 3.5rem 0; background-color: var(--bg-subtle, #f8fafc); border-bottom: 1px solid var(--border-light, #e2e8f0);">
  <div class="container text-center">
    <div class="section-tag" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--primary-teal,#0d9488);background:var(--teal-light,#f0fdfa);padding:0.3rem 0.8rem;border-radius:9999px;margin-bottom:0.75rem;">
      <i data-lucide="play-circle" style="width:14px;height:14px;"></i> Patient Education & Clinical Insights
    </div>
    <h1 style="font-size:clamp(1.85rem, 3.5vw, 2.5rem);font-weight:800;color:var(--text-main, #0f172a);margin-bottom:0.65rem;">
      Dental Video Gallery
    </h1>
    <p class="section-description" style="max-width:720px;margin:0 auto;color:var(--text-muted,#475569);font-size:1.025rem;line-height:1.65;">
      Professional dental guides, clinical procedure explanations, preventive care tutorials, and patient awareness videos by Dr. Amrit Jaishi.
    </p>
  </div>
</section>

<!-- Video Gallery Section -->
<section class="section section-videos" id="videos" style="padding: 4rem 0;">
  <div class="container">
    <!-- Category Filters -->
    <div class="video-filters" role="tablist" aria-label="Video Category Filters">
      <button type="button" class="video-filter-btn active" data-filter="all" role="tab" aria-selected="true">
        All Videos
      </button>
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
          <button type="button" class="video-filter-btn" data-filter="<?= $e($cat['slug']) ?>" role="tab" aria-selected="false">
            <?= $e($cat['name']) ?>
          </button>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Responsive Videos Grid (3 cols desktop, 2 cols tablet, 1 col mobile) -->
    <div class="videos-grid" id="videosGrid">
      <?php if (!empty($videos)): ?>
        <?php foreach ($videos as $v): ?>
          <?php
            $catSlug = $v['category_slug'] ?? '';
            $catName = $v['category_name'] ?? 'Dental Video';
            $duration = $v['duration'] ?: '04:00';
            $videoSrc = ($v['video_type'] === 'upload') ? ($v['video_file'] ?? '') : ($v['video_url'] ?? '');
            $thumb = !empty($v['thumbnail']) ? $v['thumbnail'] : 'img/services/teeth_cleaning.jpg';
            if (!str_starts_with($thumb, 'http') && !str_starts_with($thumb, '/')) {
              $thumbSrc = $asset($thumb);
            } else {
              $thumbSrc = $thumb;
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
              <img src="<?= $e($thumbSrc) ?>" alt="<?= $e($v['title']) ?> preview" class="video-thumb" loading="lazy">
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
      <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1.5rem; background: var(--bg-surface, #f8fafc); border-radius: 12px; border: 1px dashed var(--border-light, #e2e8f0);">
          <p style="color: var(--text-muted, #64748b); font-size: 1rem; margin: 0;">No videos published yet. Please check back soon.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination for Videos (2 rows / 6 cards max per page) -->
    <nav class="video-pagination" id="videoPagination" aria-label="Video Gallery Pages"></nav>
  </div>
</section>

<!-- Video Player Modal -->
<div class="video-modal-overlay" id="videoModal" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
  <div class="video-modal-dialog">
    <button type="button" class="video-modal-close" id="closeVideoModal" aria-label="Close video player">&times;</button>
    <div class="video-modal-player-wrap" id="videoModalPlayer">
      <!-- Dynamically injected iframe or HTML5 video tag -->
    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  const videoModal = document.getElementById('videoModal');
  const closeVideoModal = document.getElementById('closeVideoModal');
  const videoModalPlayer = document.getElementById('videoModalPlayer');
  const videoModalTitle = document.getElementById('videoModalTitle');
  const videoModalDesc = document.getElementById('videoModalDesc');
  const videoModalCategory = document.getElementById('videoModalCategory');
  const videoModalDuration = document.getElementById('videoModalDuration');

  function parseYouTubeId(url) {
    if (!url) return null;
    url = url.trim();
    if (/^[a-zA-Z0-9_-]{11}$/.test(url)) {
      return url;
    }
    const regExp = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/ ]{11})/i;
    const match = url.match(regExp);
    return match ? match[1] : null;
  }

  function parseVimeoId(url) {
    if (!url) return null;
    const match = url.match(/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_\-]+)?/);
    return match ? match[1] : null;
  }

  function openVideoModal(card) {
    if (!videoModal || !videoModalPlayer) return;

    const videoUrl = card.getAttribute('data-video-url') || '';
    const videoType = card.getAttribute('data-video-type') || 'youtube';
    const title = card.getAttribute('data-title') || card.querySelector('.video-title')?.textContent || 'Dental Video';
    const desc = card.getAttribute('data-desc') || card.querySelector('.video-desc')?.textContent || '';
    const cat = card.getAttribute('data-category-name') || card.querySelector('.video-cat-badge')?.textContent || '';
    const duration = card.getAttribute('data-duration') || card.querySelector('.video-duration')?.textContent || '';

    let playerHtml = '';

    const ytId = parseYouTubeId(videoUrl);
    const vmId = parseVimeoId(videoUrl);

    if (ytId) {
      playerHtml = `<iframe src="https://www.youtube.com/embed/${ytId}?autoplay=1&rel=0&modestbranding=1&enablejsapi=1" title="${title}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
    } else if (vmId) {
      playerHtml = `<iframe src="https://player.vimeo.com/video/${vmId}?autoplay=1" title="${title}" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
    } else if (videoType === 'upload' || videoUrl.match(/\.(mp4|webm|ogg)$/i)) {
      playerHtml = `
        <video controls autoplay playsinline style="width:100%;height:100%;background:#000;">
          <source src="${videoUrl}" type="video/mp4">
          Your browser does not support HTML5 video.
        </video>
      `;
    } else if (videoUrl && videoUrl.startsWith('http')) {
      playerHtml = `<iframe src="${videoUrl}" title="${title}" allow="autoplay; fullscreen" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
    } else {
      playerHtml = `<div style="color:#fff;display:flex;align-items:center;justify-content:center;height:100%;font-size:1rem;">Video source unavailable.</div>`;
    }

    videoModalPlayer.innerHTML = playerHtml;
    if (videoModalTitle) videoModalTitle.textContent = title;
    if (videoModalDesc) videoModalDesc.textContent = desc;
    if (videoModalCategory) videoModalCategory.textContent = cat;
    if (videoModalDuration) videoModalDuration.textContent = duration ? `Duration: ${duration}` : '';

    videoModal.classList.add('open');
    videoModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeVideo() {
    if (!videoModal) return;
    videoModal.classList.remove('open');
    videoModal.setAttribute('aria-hidden', 'true');
    if (videoModalPlayer) {
      videoModalPlayer.innerHTML = '';
    }
    document.body.style.overflow = '';
  }

  if (closeVideoModal) {
    closeVideoModal.addEventListener('click', closeVideo);
  }

  if (videoModal) {
    videoModal.addEventListener('click', function(e) {
      if (e.target === videoModal) closeVideo();
    });
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && videoModal.classList.contains('open')) closeVideo();
    });
  }

  document.addEventListener('click', function(e) {
    const card = e.target.closest('.video-card');
    if (card) {
      e.preventDefault();
      openVideoModal(card);
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      const card = e.target.closest('.video-card');
      if (card && document.activeElement === card) {
        e.preventDefault();
        openVideoModal(card);
      }
    }
  });

  // 2 Rows / Max 6 Videos Per Page Pagination
  const VIDEOS_PER_PAGE = 6;
  let currentVideoPage = 1;
  let currentVideoCategory = 'all';

  function updateVideoDisplay(scrollToVideos) {
    const allCards = document.querySelectorAll('#videosGrid .video-card');
    const paginationEl = document.getElementById('videoPagination');
    if (!allCards || allCards.length === 0) return;

    const matchingCards = [];
    allCards.forEach(function (card) {
      const cat = card.getAttribute('data-category') || '';
      if (currentVideoCategory === 'all' || cat === currentVideoCategory) {
        matchingCards.push(card);
      } else {
        card.style.display = 'none';
      }
    });

    const totalMatching = matchingCards.length;
    const totalPages = Math.ceil(totalMatching / VIDEOS_PER_PAGE) || 1;

    if (currentVideoPage > totalPages) {
      currentVideoPage = 1;
    }

    const startIndex = (currentVideoPage - 1) * VIDEOS_PER_PAGE;
    const endIndex = startIndex + VIDEOS_PER_PAGE;

    matchingCards.forEach(function (card, index) {
      if (index >= startIndex && index < endIndex) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });

    if (!paginationEl) return;

    if (totalPages <= 1) {
      paginationEl.innerHTML = '';
      return;
    }

    let paginationHtml = '';

    const isFirstPage = currentVideoPage === 1;
    paginationHtml += `
      <button type="button" class="video-page-btn" data-page="${currentVideoPage - 1}" ${isFirstPage ? 'disabled aria-disabled="true"' : ''} aria-label="Previous Page">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><polyline points="15 18 9 12 15 6"></polyline></svg>
        <span>Prev</span>
      </button>
    `;

    for (let p = 1; p <= totalPages; p++) {
      const isActive = p === currentVideoPage;
      paginationHtml += `
        <button type="button" class="video-page-btn ${isActive ? 'active' : ''}" data-page="${p}" ${isActive ? 'aria-current="page"' : ''} aria-label="Page ${p}">
          ${p}
        </button>
      `;
    }

    const isLastPage = currentVideoPage === totalPages;
    paginationHtml += `
      <button type="button" class="video-page-btn" data-page="${currentVideoPage + 1}" ${isLastPage ? 'disabled aria-disabled="true"' : ''} aria-label="Next Page">
        <span>Next</span>
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>
    `;

    paginationEl.innerHTML = paginationHtml;

    paginationEl.querySelectorAll('.video-page-btn').forEach(function (btn) {
      btn.onclick = function () {
        if (btn.disabled || btn.classList.contains('active')) return;
        const targetPage = parseInt(btn.getAttribute('data-page'), 10);
        if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages) {
          currentVideoPage = targetPage;
          updateVideoDisplay(true);
        }
      };
    });

    if (scrollToVideos) {
      const videoSection = document.getElementById('videos');
      if (videoSection) {
        videoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
  }

  const filterBtns = document.querySelectorAll('.video-filter-btn');
  filterBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      const filterVal = btn.getAttribute('data-filter') || 'all';
      filterBtns.forEach(function(b) { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');

      currentVideoCategory = filterVal;
      currentVideoPage = 1;
      updateVideoDisplay(false);
    });
  });

  updateVideoDisplay(false);
});
</script>
