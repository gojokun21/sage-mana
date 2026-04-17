<?php

/**
 * Performance: resource hints for above-the-fold content.
 *
 * Emits <link rel="preload"> from wp_head for:
 *   - Primary webfont (Rubik regular, Latin range) — discovered late otherwise
 *     because it's only referenced from inside app.css via @font-face.
 *   - First hero slide image on pages that render `partials/home/hero.blade.php`
 *     (detected via ACF `hero_section`). Separate media-gated hints for
 *     desktop / mobile variants so browsers only fetch the one they need.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

add_action('wp_head', __NAMESPACE__ . '\\emit_preloads', 1);

function emit_preloads(): void
{
    emit_font_preload();
    emit_hero_preload();
}

function emit_font_preload(): void
{
    try {
        $url = Vite::asset('resources/fonts/rubik-latin.woff2');
    } catch (\Throwable $e) {
        return;
    }

    if (! $url) {
        return;
    }

    printf(
        '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
        esc_url($url)
    );
}

function emit_hero_preload(): void
{
    if (! function_exists('get_field')) {
        return;
    }
    if (! is_singular()) {
        return;
    }

    $hero = get_field('hero_section');
    if (empty($hero) || ! is_array($hero)) {
        return;
    }

    $sliders = find_hero_sliders($hero);
    if (empty($sliders)) {
        return;
    }

    $first = $sliders[0];
    $desktop_url = hero_image_url($first['image'] ?? null, 'full');
    $mobile_url = hero_image_url($first['mobile_image'] ?? null, 'large');

    // Mobile: preload only when viewport matches. If no mobile_image is set,
    // the desktop one is reused below — but we don't want mobile devices to
    // also load the desktop-only hint, so we gate the desktop hint with
    // `min-width: 769px` whenever a mobile variant exists.
    if ($mobile_url) {
        printf(
            '<link rel="preload" as="image" href="%s" fetchpriority="high" media="(max-width: 768px)">' . "\n",
            esc_url($mobile_url)
        );
    }
    if ($desktop_url) {
        $media = $mobile_url ? ' media="(min-width: 769px)"' : '';
        printf(
            '<link rel="preload" as="image" href="%s" fetchpriority="high"%s>' . "\n",
            esc_url($desktop_url),
            $media
        );
    }
}

/**
 * The `hero_section` ACF field may be a group (direct `sliders` key) or a
 * repeater/flexible layout (rows, each with its own `sliders`). Handle both.
 */
function find_hero_sliders(array $hero): ?array
{
    if (isset($hero['sliders']) && is_array($hero['sliders'])) {
        return $hero['sliders'];
    }

    foreach ($hero as $row) {
        if (is_array($row) && ! empty($row['sliders']) && is_array($row['sliders'])) {
            return $row['sliders'];
        }
    }

    return null;
}

/**
 * ACF image fields can return an array, a numeric attachment ID, or a URL
 * string depending on their "Return Format" setting. Normalize to a URL,
 * preferring a sized version when we have the ID.
 */
function hero_image_url($image, string $size = 'full'): ?string
{
    if (empty($image)) {
        return null;
    }

    if (is_array($image)) {
        $id = $image['ID'] ?? $image['id'] ?? null;
        if ($id) {
            $src = wp_get_attachment_image_src((int) $id, $size);
            if ($src) {
                return $src[0];
            }
        }
        return $image['url'] ?? null;
    }

    if (is_numeric($image)) {
        $src = wp_get_attachment_image_src((int) $image, $size);
        return $src ? $src[0] : null;
    }

    return is_string($image) ? $image : null;
}
