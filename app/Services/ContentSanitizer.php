<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Service untuk sanitasi HTML content dari CMS (pages, berita, settings).
 *
 * Mencegah XSS attacks dengan memfilter tag berbahaya sambil
 * mempertahankan tag formatting yang aman (bold, italic, link, dll).
 */
class ContentSanitizer
{
    /**
     * Tag HTML yang diizinkan.
     */
    private const ALLOWED_TAGS = [
        // Text formatting
        'p', 'br', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'strong', 'b', 'em', 'i', 'u', 's', 'del', 'ins', 'mark',
        'small', 'sub', 'sup', 'blockquote', 'cite', 'q',
        // Lists
        'ul', 'ol', 'li', 'dl', 'dt', 'dd',
        // Links & media
        'a', 'img', 'figure', 'figcaption',
        // Tables
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
        'caption', 'colgroup', 'col',
        // Layout
        'div', 'span', 'pre', 'code', 'abbr', 'address',
        // Embedded content
        'iframe', 'video', 'source', 'audio',
    ];

    /**
     * Atribut HTML yang diizinkan per tag.
     */
    private const ALLOWED_ATTRIBUTES = [
        '*' => ['class', 'id', 'style', 'title', 'lang', 'dir'],
        'a' => ['href', 'target', 'rel', 'name'],
        'img' => ['src', 'alt', 'width', 'height', 'loading', 'decoding'],
        'iframe' => ['src', 'width', 'height', 'frameborder', 'allow', 'allowfullscreen', 'loading'],
        'video' => ['src', 'controls', 'autoplay', 'loop', 'muted', 'width', 'height', 'poster'],
        'audio' => ['src', 'controls', 'autoplay', 'loop', 'muted'],
        'source' => ['src', 'type', 'media'],
        'td' => ['colspan', 'rowspan', 'headers'],
        'th' => ['colspan', 'rowspan', 'scope', 'headers'],
        'col' => ['span'],
        'colgroup' => ['span'],
        'table' => ['border', 'cellpadding', 'cellspacing'],
    ];

    /**
     * Sandboxing attributes untuk iframe.
     */
    private const IFRAME_SANDBOX = 'allow-scripts allow-same-origin allow-popups';

    /**
     * Sanitize HTML content.
     *
     * @param string $html Raw HTML content
     * @param array $options Opsi sanitasi tambahan
     * @return string Sanitized HTML
     */
    public function sanitize(string $html, array $options = []): string
    {
        if (empty($html)) {
            return '';
        }

        // Step 1: Decode HTML entities untuk memproses tags
        $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Step 2: Hapus HTML comments
        $cleaned = preg_replace('/<!--.*?-->/s', '', $decoded);

        // Step 3: Hapus tags yang tidak diizinkan (preserve content)
        $cleaned = $this->stripDisallowedTags($cleaned);

        // Step 4: Hapus atribut yang tidak diizinkan
        $cleaned = $this->stripDisallowedAttributes($cleaned);

        // Step 5: Sanitize URLs (hapus javascript: protocol, dll)
        $cleaned = $this->sanitizeUrls($cleaned);

        // Step 6: Sandbox iframe embeds
        $cleaned = $this->sandboxIframes($cleaned);

        // Step 7: Hapus event handlers (onclick, onerror, dll)
        $cleaned = $this->stripEventHandlers($cleaned);

        // Step 8: Trim whitespace
        $cleaned = trim($cleaned);

        return $cleaned;
    }

    /**
     * Sanitize untuk konten sederhana (tanpa embed/iframe).
     *
     * @param string $html Raw HTML content
     * @return string Sanitized HTML
     */
    public function sanitizeSimple(string $html): string
    {
        return $this->sanitize($html, ['allow_iframes' => false]);
    }

    /**
     * Sanitize plain text (hilangkan semua HTML tags).
     *
     * @param string $text Input text
     * @return string Clean text
     */
    public function sanitizeText(string $text): string
    {
        return strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /**
     * Strip tags yang tidak diizinkan, pertahankan kontennya.
     */
    private function stripDisallowedTags(string $html): string
    {
        // Hapus self-closing tags yang tidak diizinkan (script, style, dll)
        $html = preg_replace(
            '/<\s*(script|style|object|embed|form|input|textarea|button|select|option|link|meta|base)\b[^>]*\/?>/is',
            '',
            $html
        );

        // Hapus closing tags yang tidak diizinkan
        $html = preg_replace(
            '/<\s*\/\s*(script|style|object|embed|form|input|textarea|button|select|option|link|meta|base)\b[^>]*>/is',
            '',
            $html
        );

        // Hapus iframe jika tidak diizinkan
        $html = preg_replace('/<\s*iframe\b[^>]*>.*?<\s*\/\s*iframe\s*>/is', '', $html);
        $html = preg_replace('/<\s*iframe\b[^>]*\/?>/is', '', $html);

        return $html;
    }

    /**
     * Strip atribut yang tidak diizinkan dari tags.
     */
    private function stripDisallowedAttributes(string $html): string
    {
        return preg_replace_callback(
            '/<(\w+)\s+([^>]*)>/i',
            function ($matches) {
                $tag = strtolower($matches[1]);
                $attributes = $matches[2];

                $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];
                $globalAllowed = self::ALLOWED_ATTRIBUTES['*'] ?? [];
                $allAllowed = array_unique(array_merge($allowed, $globalAllowed));

                if (empty($allAllowed)) {
                    return "<{$tag}>";
                }

                $filteredAttributes = $this->filterAttributes($attributes, $allAllowed);

                return "<{$tag}{$filteredAttributes}>";
            },
            $html
        );
    }

    /**
     * Filter atribut HTML, pertahankan hanya yang diizinkan.
     */
    private function filterAttributes(string $attributesString, array $allowedAttributes): string
    {
        $result = '';
        $pattern = '/(\w[\w-]*)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|(\S+)))?/i';

        if (preg_match_all($pattern, $attributesString, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $attrName = strtolower($match[1]);

                if (in_array($attrName, $allowedAttributes, true)) {
                    $value = $match[2] ?? $match[3] ?? $match[4] ?? $attrName;
                    $result .= " {$attrName}=\"{$value}\"";
                }
            }
        }

        return $result;
    }

    /**
     * Sanitize URLs di atribut href dan src.
     */
    private function sanitizeUrls(string $html): string
    {
        // Hapus javascript: protocol
        $html = preg_replace('/href\s*=\s*["\']?\s*javascript\s*:/i', 'href="#"', $html);
        $html = preg_replace('/src\s*=\s*["\']?\s*javascript\s*:/i', 'src=""', $html);

        // Hapus data: protocol kecuali untuk gambar (src attribute)
        $html = preg_replace_callback(
            '/(\w+)\s*=\s*["\']?\s*data\s*:[^"\']*/i',
            function ($matches) {
                $attrName = strtolower($matches[1]);
                if ($attrName === 'src') {
                    return $matches[0];
                }
                return $attrName . '="#"';
            },
            $html
        );

        // Hapus vbscript: protocol
        $html = preg_replace('/href\s*=\s*["\']?\s*vbscript\s*:/i', 'href="#"', $html);

        return $html;
    }

    /**
     * Tambah sandbox attribute ke iframe untuk keamanan.
     */
    private function sandboxIframes(string $html): string
    {
        // Tambah sandbox attribute ke iframe yang belum punya
        $html = preg_replace_callback(
            '/<iframe\b([^>]*)>/i',
            function ($matches) {
                $attrs = $matches[1];

                if (preg_match('/\bsandbox\s*=/i', $attrs)) {
                    return $matches[0];
                }

                return '<iframe sandbox="' . self::IFRAME_SANDBOX . '"' . $attrs . '>';
            },
            $html
        );

        // Tambah rel="noopener noreferrer" ke iframe
        $html = preg_replace(
            '/<iframe\b([^>]*)>/i',
            function ($matches) {
                $attrs = $matches[1];
                if (!preg_match('/\brel\s*=/i', $attrs)) {
                    return '<iframe rel="noopener noreferrer"' . $attrs . '>';
                }
                return $matches[0];
            },
            $html
        );

        return $html;
    }

    /**
     * Hapus event handlers dari tags (onclick, onerror, dll).
     */
    private function stripEventHandlers(string $html): string
    {
        return preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|\S+)/i', '', $html);
    }
}
