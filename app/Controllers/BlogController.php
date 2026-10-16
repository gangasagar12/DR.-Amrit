<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Blog;
use App\Models\Category;
use App\Helpers\Seo;

class BlogController {
    public function index(Request $request): void {
        $page = max(1, (int)$request->get('page', 1));
        $categorySlug = $request->get('category');
        $type = $request->get('type');
        $search = $request->get('q');

        $categoryId = null;
        $currentCategory = null;
        if ($categorySlug) {
            $cat = Category::findBySlug($categorySlug);
            if ($cat) {
                $categoryId = (int)$cat['id'];
                $currentCategory = $cat;
            }
        }

        $result = Blog::allPublished($page, 6, $categoryId, $type, $search);
        $categories = Category::all();

        $pageTitle = 'Oral Health Blog & Guides | Dr. Amrit Jaishi (BDS BPKIHS)';
        if ($currentCategory) {
            $pageTitle = $currentCategory['name'] . ' - Dental Articles | Dr. Amrit Jaishi';
        } elseif ($type === 'Research Publication') {
            $pageTitle = 'Dental Articles & Clinical Guides | Dr. Amrit Jaishi';
        }

        View::render('public/blog', [
            'title' => $pageTitle,
            'metaDescription' => 'Educational oral health articles, preventive dental care, root canal guides, and emergency dental advice by Dr. Amrit Jaishi.',
            'blogs' => $result['items'],
            'total' => $result['total'],
            'currentPage' => $result['page'],
            'totalPages' => $result['total_pages'],
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'currentType' => $type,
            'search' => $search,
            'activeNav' => 'blog'
        ]);
    }

    public function show(Request $request, array $params): void {
        $slug = $params['slug'] ?? '';
        $blog = Blog::findBySlug($slug);

        if (!$blog) {
            http_response_code(404);
            View::render('public/404', ['title' => 'Article Not Found'], 'layouts/main');
            return;
        }

        // Calculate approximate reading time (average 200 words per minute)
        $wordCount = str_word_count(strip_tags($blog['content_html']));
        $readingTime = max(1, ceil($wordCount / 200));

        $related = Blog::related((int)$blog['id'], !empty($blog['category_id']) ? (int)$blog['category_id'] : null, 3);
        $categories = Category::all();

        $articleSchema = Seo::getArticleSchema($blog);
        $breadcrumbSchema = Seo::getBreadcrumbSchema([
            'Home' => '/',
            'Blog' => '/blog',
            $blog['title'] => '/blog/' . $blog['slug']
        ]);

        View::render('public/blog_detail', [
            'title' => $blog['meta_title'] ?: ($blog['title'] . ' | Dr. Amrit Jaishi'),
            'metaDescription' => $blog['meta_description'] ?: $blog['excerpt'],
            'blog' => $blog,
            'readingTime' => $readingTime,
            'related' => $related,
            'categories' => $categories,
            'articleSchema' => $articleSchema,
            'breadcrumbSchema' => $breadcrumbSchema,
            'activeNav' => 'blog'
        ]);
    }
}
