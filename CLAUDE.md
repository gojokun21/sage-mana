# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

`sage-nature` — a Roots **Sage 10** WordPress theme for a Romanian WooCommerce store (Natura). Ported from a legacy theme (`mana-naturii`); shortcodes and ACF fields are still in use alongside native Blade views. Customer-facing strings are in Romanian; keep them in Romanian when editing.

## Commands

```bash
npm run dev          # Vite dev server with HMR (writes hot file; WP detects it)
npm run build        # Production build → public/build/
npm run translate    # Make + update .pot/.po (wp-cli required)
npm run translate:compile  # Compile .mo + JS translation JSON
composer install     # PHP deps (Acorn + sage-woocommerce)
vendor/bin/pint      # Laravel Pint — PHP formatter/linter
```

No test suite is configured.

## Architecture

### Stack
- **Sage 10** + **Acorn 5** — Laravel IoC container inside WordPress. `functions.php` bootstraps `Application::configure()->withProviders([ThemeServiceProvider])` which extends `SageServiceProvider`.
- **Laravel Blade** templates in `resources/views/`. Compiled cache lives at `wp-content/cache/acorn/framework/views/` — clear it if a `.blade.php` change doesn't reflect.
- **Vite 8** + **Tailwind v4** (via `@tailwindcss/vite`) + `@roots/vite-plugin` + `laravel-vite-plugin`. Entry points declared in `vite.config.js`; `base` is `/wp-content/themes/sage-nature/public/build/`.
- **PHP 8.2+**, PSR-4 `App\\` → `app/`.

### PHP file registration
Theme PHP files are loaded from `app/` via `collect([...])->each(...)` in `functions.php`. **To add a new top-level PHP file (hooks, AJAX, etc.), append its basename to this array** — otherwise it won't be loaded:

```php
collect(['setup', 'filters', 'ajax-search', 'woocommerce-tabs', 'mini-cart'])
```

All such files use `namespace App;` and register WP hooks at load time.

### View layer
- `resources/views/layouts/app.blade.php` is the single layout; top-level templates (`index.blade.php`, `single.blade.php`, `page.blade.php`, `search.blade.php`, `404.blade.php`) `@extends('layouts.app')` and `@yield('content')`.
- `app/View/Composers/` — Acorn view composers. `App.php` targets `*` and exposes `$siteName` to every view.
- WooCommerce Blade overrides live in `resources/views/woocommerce/` (enabled by `generoi/sage-woocommerce`). Some WC subtemplates are still plain `.php` (e.g. `single-product/add-to-cart/simple.php`) — those bypass Blade.

### Frontend assets
- `resources/js/app.js` is the main entry. Heavy modules are **lazy-loaded** based on DOM presence: e.g. `if (document.getElementById('miniCartDrawer')) import('./mini-cart.js')`. Follow this pattern when adding feature modules.
- CSS is plain CSS (not Tailwind utilities in most components). `app.css` imports Tailwind then chains feature files via `@import "./feature.css"` at the bottom. Custom properties defined in `:root`.
- Vanilla JS only — no jQuery in build. When interop with WooCommerce core is needed (e.g. WC's `added_to_cart` event), use `window.jQuery` as a bridge since WC loads jQuery anyway. Don't add jQuery as a build dep.

### AJAX conventions
Custom AJAX endpoints follow this pattern (see `app/ajax-search.php`, `app/mini-cart.php`):

1. Register `wp_ajax_{action}` + `wp_ajax_nopriv_{action}`.
2. Localize config inline via `add_action('wp_footer', fn() => echo '<script>var foo = {...}</script>', 5)` — no `wp_localize_script` because Vite handles script enqueue.
3. Always `check_ajax_referer()` with a per-endpoint nonce.
4. Return JSON via `wp_send_json_success/error()`. For fragments, render Blade partials with `View::make('partials.foo')->render()`.

### WooCommerce
- Default WC stylesheets are dequeued in `app/filters.php`.
- WC support is declared in `app/setup.php` (`add_theme_support('woocommerce')` + gallery features).
- Mini cart is custom (see `app/mini-cart.php` + `partials/mini-cart*.blade.php` + `resources/js/mini-cart.js`); it refreshes by listening to WC's jQuery events (`added_to_cart`, `removed_from_cart`, etc.) via the `window.jQuery` bridge.

### Menus & ACF
Primary menu location: `primary_navigation` (slug also checked as `primary-menu`). Mega-menu items are detected by CSS class prefix `mega-` on the menu item, then populated from ACF options (`pachete`, `mega_menu_image`) or dynamically from `product_cat` taxonomy when the class is `mega-produse`.
