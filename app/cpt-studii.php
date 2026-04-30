<?php

/**
 * Custom Post Type: Studii + taxonomia proprie `categorie_studiu`.
 *
 * Studiile au taxonomia lor distincta — separata de categoriile postarilor
 * de blog. Asta permite ca admin-ul sa organizeze studiile dupa criterii
 * complet diferite (ex: "Cardio", "Endocrin", "Inflamație") fara sa
 * polueze clusterele blogului.
 *
 * URL-uri:
 *   /studii/                            — arhiva CPT
 *   /studii/<slug>/                     — single studiu
 *   /categorie-studiu/<term>/           — arhiva taxonomie
 *
 * Pentru detalii suplimentare (autor, link sursa, jurnal, an) — adauga
 * campuri ACF cu Location: `Post Type` is equal to `studiu`.
 */

namespace App;

add_action('init', function () {
    $labels = [
        'name'                  => __('Studii', 'sage'),
        'singular_name'         => __('Studiu', 'sage'),
        'menu_name'             => __('Studii', 'sage'),
        'name_admin_bar'        => __('Studiu', 'sage'),
        'add_new'               => __('Adaugă nou', 'sage'),
        'add_new_item'          => __('Adaugă studiu nou', 'sage'),
        'new_item'              => __('Studiu nou', 'sage'),
        'edit_item'             => __('Editează studiu', 'sage'),
        'view_item'             => __('Vezi studiu', 'sage'),
        'view_items'            => __('Vezi studii', 'sage'),
        'all_items'             => __('Toate studiile', 'sage'),
        'search_items'          => __('Caută studii', 'sage'),
        'parent_item_colon'     => __('Studiu părinte:', 'sage'),
        'not_found'             => __('Niciun studiu găsit.', 'sage'),
        'not_found_in_trash'    => __('Niciun studiu în coșul de gunoi.', 'sage'),
        'featured_image'        => __('Imagine reprezentativă', 'sage'),
        'set_featured_image'    => __('Setează imaginea', 'sage'),
        'remove_featured_image' => __('Șterge imaginea', 'sage'),
        'use_featured_image'    => __('Folosește ca imagine', 'sage'),
        'archives'              => __('Arhivă studii', 'sage'),
        'insert_into_item'      => __('Inserează în studiu', 'sage'),
        'uploaded_to_this_item' => __('Încărcat în acest studiu', 'sage'),
        'filter_items_list'     => __('Filtrează lista studiilor', 'sage'),
        'items_list_navigation' => __('Navigare studii', 'sage'),
        'items_list'            => __('Listă studii', 'sage'),
    ];

    register_post_type('studiu', [
        'labels'              => $labels,
        'description'         => __('Studii clinice peer-reviewed citate în articolele de blog.', 'sage'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'show_in_rest'        => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-book-alt',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'has_archive'         => 'studii',
        'rewrite'             => [
            'slug'       => 'studii',
            'with_front' => false,
            'feeds'      => true,
            'pages'      => true,
        ],
        'query_var'           => true,
        'supports'            => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'revisions',
            'author',
            'comments',
            'page-attributes',
            'post-formats',
        ],
    ]);

    /**
     * Taxonomia proprie a studiilor — `categorie_studiu`.
     * Hierarchical (ca Categories) ca admin-ul sa o foloseasca cu checkbox-uri
     * in loc de tag-uri free-form. Apare in meniul Studii.
     */
    $tax_labels = [
        'name'                       => __('Categorii studii', 'sage'),
        'singular_name'              => __('Categorie studiu', 'sage'),
        'menu_name'                  => __('Categorii', 'sage'),
        'all_items'                  => __('Toate categoriile', 'sage'),
        'edit_item'                  => __('Editează categoria', 'sage'),
        'view_item'                  => __('Vezi categoria', 'sage'),
        'update_item'                => __('Actualizează categoria', 'sage'),
        'add_new_item'               => __('Adaugă categorie nouă', 'sage'),
        'new_item_name'              => __('Nume categorie nouă', 'sage'),
        'parent_item'                => __('Categorie părinte', 'sage'),
        'parent_item_colon'          => __('Categorie părinte:', 'sage'),
        'search_items'               => __('Caută categorii', 'sage'),
        'popular_items'              => __('Categorii populare', 'sage'),
        'separate_items_with_commas' => __('Separă categoriile cu virgulă', 'sage'),
        'add_or_remove_items'        => __('Adaugă sau șterge categorii', 'sage'),
        'choose_from_most_used'      => __('Alege din cele mai folosite', 'sage'),
        'not_found'                  => __('Nicio categorie găsită.', 'sage'),
        'back_to_items'              => __('← Înapoi la categorii', 'sage'),
    ];

    register_taxonomy('categorie_studiu', ['studiu'], [
        'labels'            => $tax_labels,
        'hierarchical'      => true,
        'public'            => true,
        'publicly_queryable' => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true,
        'show_tagcloud'     => false,
        'rewrite'           => [
            'slug'         => 'categorie-studiu',
            'with_front'   => false,
            'hierarchical' => true,
        ],
        'query_var'         => true,
    ]);

    /**
     * Tag-uri pentru studii — taxonomie non-hierarchical, free-form.
     * Pentru keyword-uri (ex: "RCT", "meta-analiză", "anti-aging").
     */
    $tag_labels = [
        'name'                       => __('Etichete studii', 'sage'),
        'singular_name'              => __('Etichetă', 'sage'),
        'menu_name'                  => __('Etichete', 'sage'),
        'all_items'                  => __('Toate etichetele', 'sage'),
        'edit_item'                  => __('Editează eticheta', 'sage'),
        'view_item'                  => __('Vezi eticheta', 'sage'),
        'update_item'                => __('Actualizează eticheta', 'sage'),
        'add_new_item'               => __('Adaugă etichetă nouă', 'sage'),
        'new_item_name'              => __('Nume etichetă nouă', 'sage'),
        'search_items'               => __('Caută etichete', 'sage'),
        'popular_items'              => __('Etichete populare', 'sage'),
        'separate_items_with_commas' => __('Separă cu virgulă', 'sage'),
        'add_or_remove_items'        => __('Adaugă sau șterge etichete', 'sage'),
        'choose_from_most_used'      => __('Alege din cele mai folosite', 'sage'),
        'not_found'                  => __('Nicio etichetă găsită.', 'sage'),
        'back_to_items'              => __('← Înapoi la etichete', 'sage'),
    ];

    register_taxonomy('eticheta_studiu', ['studiu'], [
        'labels'            => $tag_labels,
        'hierarchical'      => false,
        'public'            => true,
        'publicly_queryable' => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => true,
        'show_tagcloud'     => true,
        'rewrite'           => [
            'slug'       => 'eticheta-studiu',
            'with_front' => false,
        ],
        'query_var'         => true,
    ]);
});

/**
 * Activeaza comentariile by default pentru studii noi.
 */
add_filter('default_comment_status', function ($status, $post_type) {
    if ($post_type === 'studiu') {
        return 'open';
    }

    return $status;
}, 10, 2);

/**
 * Activeaza post formats pentru CPT studiu (Standard, Quote, Image, etc.).
 */
add_action('after_setup_theme', function () {
    add_post_type_support('studiu', 'post-formats');
}, 11);

/**
 * Include studii in main blog query — daca vrei ca studiile sa apara
 * alaturi de posts pe arhiva blogului sau in feed-ul principal,
 * decomenteaza filtrul de mai jos.
 *
 *   add_action('pre_get_posts', function ($query) {
 *       if ($query->is_main_query() && (is_home() || is_category() || is_tag())) {
 *           $types = (array) $query->get('post_type') ?: ['post'];
 *           $query->set('post_type', array_unique([...$types, 'studiu']));
 *       }
 *   });
 */
