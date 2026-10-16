<?php
namespace App\Core;

class Sanitizer {
    /**
     * Strict HTML sanitization for rich text content
     */
    public static function cleanHtml(?string $html): string {
        if ($html === null || trim($html) === '') {
            return '';
        }

        // 1. Remove dangerous script and iframe elements directly
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $html);
        $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
        $html = preg_replace('#<object(.*?)>(.*?)</object>#is', '', $html);
        $html = preg_replace('#<embed(.*?)>(.*?)</embed>#is', '', $html);
        $html = preg_replace('#<applet(.*?)>(.*?)</applet>#is', '', $html);
        $html = preg_replace('#<form(.*?)>(.*?)</form>#is', '', $html);

        // 2. Allowed tags
        $allowedTags = [
            'div', 'p', 'br', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 
            'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup',
            'blockquote', 'q', 'ul', 'ol', 'li', 'figure', 'figcaption',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
            'a', 'img', 'span', 'code', 'pre'
        ];

        // 3. Allowed attributes per tag
        $allowedAttrs = [
            'div' => ['class'],
            'a' => ['href', 'title', 'target', 'rel'],
            'img' => ['src', 'alt', 'title', 'width', 'height', 'loading', 'class'],
            'th' => ['colspan', 'rowspan', 'align'],
            'td' => ['colspan', 'rowspan', 'align'],
            'span' => ['class'],
            'p' => ['class'],
            'blockquote' => ['class'],
            'table' => ['class', 'border'],
            'code' => ['class']
        ];

        // Use DOMDocument for precise tree parsing
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        
        $wrapped = '<?xml encoding="utf-8" ?><div>' . $html . '</div>';
        $loaded = $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        if ($loaded) {
            $root = $dom->getElementsByTagName('div')->item(0);
            if ($root) {
                $children = [];
                foreach ($root->childNodes as $child) {
                    $children[] = $child;
                }
                foreach ($children as $child) {
                    self::cleanNode($child, $allowedTags, $allowedAttrs);
                }

                $sanitized = '';
                foreach ($root->childNodes as $child) {
                    $sanitized .= $dom->saveHTML($child);
                }
                return $sanitized;
            }
        }

        // Fallback: strip tags and regex remove on* attributes and javascript:
        $stripped = strip_tags($html, '<p><br><hr><h1><h2><h3><h4><h5><h6><strong><b><em><i><u><s><blockquote><ul><ol><li><table><thead><tbody><tr><th><td><a><img>');
        $cleaned = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $stripped);
        $cleaned = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*("|\')/i', 'href="#"', $cleaned);
        return $cleaned;
    }

    private static function cleanNode(\DOMNode $node, array $allowedTags, array $allowedAttrs): void {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            $tagName = strtolower($node->nodeName);

            if (!in_array($tagName, $allowedTags, true)) {
                // Not allowed: replace node with its text content or remove
                $textNode = $node->ownerDocument->createTextNode($node->textContent);
                $node->parentNode->replaceChild($textNode, $node);
                return;
            }

            // Clean attributes
            if ($node->hasAttributes()) {
                $attrsToRemove = [];
                foreach ($node->attributes as $attr) {
                    $attrName = strtolower($attr->name);
                    $attrValue = trim($attr->value);

                    // Block any event handlers (on*) or javascript: URIs
                    if (str_starts_with($attrName, 'on') || preg_match('/^javascript:/i', $attrValue) || preg_match('/^vbscript:/i', $attrValue)) {
                        $attrsToRemove[] = $attr->name;
                        continue;
                    }

                    // Check allowed attributes for this tag
                    $allowedForTag = $allowedAttrs[$tagName] ?? [];
                    if (!in_array($attrName, $allowedForTag, true)) {
                        $attrsToRemove[] = $attr->name;
                        continue;
                    }

                    // Strict validation for href
                    if ($attrName === 'href') {
                        if (!preg_match('/^(https?:\/\/|mailto:|tel:|\/|#)/i', $attrValue)) {
                            $attrsToRemove[] = $attr->name;
                            continue;
                        }
                    }

                    // Strict validation for img src
                    if ($attrName === 'src') {
                        if (!preg_match('/^(https?:\/\/|\/|uploads\/|data:image\/)/i', $attrValue)) {
                            $attrsToRemove[] = $attr->name;
                            continue;
                        }
                    }

                    // Secure external links
                    if ($tagName === 'a' && $attrName === 'target' && strtolower($attrValue) === '_blank') {
                        $node->setAttribute('rel', 'noopener noreferrer');
                    }
                }

                foreach ($attrsToRemove as $attrName) {
                    $node->removeAttribute($attrName);
                }
            }
        }

        // Recursively clean children
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }
        foreach ($children as $child) {
            self::cleanNode($child, $allowedTags, $allowedAttrs);
        }
    }

    /**
     * Clean plain text input (e.g. titles, form fields)
     */
    public static function cleanString(?string $str): string {
        if ($str === null) return '';
        return trim(strip_tags($str));
    }
}
