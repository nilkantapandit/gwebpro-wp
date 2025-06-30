<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;


// replace acf file id by file url for REST API
function my_acf_to_rest_api() {
    if( function_exists('register_rest_field') ) {
        // Register ACF fields for the 'page' post type
        register_rest_field('page', 'acf', array(
            'get_callback' => function($object) {
                $acf_fields = get_fields($object['id']);
                
                // Check if a field is an image and replace ID with URL
                if (isset($acf_fields['image'])) {
                    $image_id = $acf_fields['image'];
                    $acf_fields['image'] = wp_get_attachment_url($image_id); // Get the image URL
                }
                
                return $acf_fields;
            },
            'schema' => null
        ));
    }
}
add_action('rest_api_init', 'my_acf_to_rest_api');

add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init()
{

    // Check function exists.
    if (function_exists('acf_add_options_page')) {

        // Register options page.
        $option_page = acf_add_options_page(array(
            'page_title' => __('Theme General Settings'),
            'menu_title' => __('Theme Settings'),
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        ));
    }
}

//ACF data for Theme Settings
function acf_options_rest_route() {
    register_rest_route('acf/v1', '/options/', array(
        'methods' => 'GET',
        'callback' => 'get_acf_options',
        'permission_callback' => '__return_true'
    ));
}

add_action('rest_api_init', 'acf_options_rest_route');

function get_acf_options() {
    $options = array(
        'header_logo' => get_field('header_logo', 'option'),
        'header_review_sites' => get_field('header_review_site_list', 'option'),
        'footer_logo' => get_field('footer_logo', 'option'),
        'review_site_list' => get_field('review_site_list', 'option'),
        'social_site_list' => get_field('social_site_list', 'option'),
        'address_list' => get_field('address_list', 'option'),
        'phone_number_title' => get_field('phone_number_title', 'option'),
        'phone_number' => get_field('phone_number', 'option'),
        'email_address_title' => get_field('email_address_title', 'option'),
        'email_address' => get_field('email_address', 'option'),
    );

    return new WP_REST_Response($options, 200);
}


//ACF data for leader brands
function acf_brand_list_rest_route() {
    register_rest_route('acf/v1', '/leader-brands/', array(
        'methods' => 'GET',
        'callback' => 'get_acf_brand_leaders',
        'permission_callback' => '__return_true'
    ));
}

add_action('rest_api_init', 'acf_brand_list_rest_route');

function get_acf_brand_leaders() {
    $data = array(
       
        'brand_section_title' => get_field('brand_section_title', 'option'),
        'brand_section_desc' => get_field('brand_section_desc', 'option'),
        'brand_logo_list' => get_field('brand_logo_list', 'option')
    );

    return new WP_REST_Response($data, 200);
}

function enable_classic_menus() {
    add_theme_support('menus'); // Enable menu support
    register_nav_menu('primary', __('Primary Menu', 'twentytwentyfive'));
}
add_action('after_setup_theme', 'enable_classic_menus');




//fetch hierarchical menu item API
function get_menu_items_hierarchical($menu_id) {
    $items = wp_get_nav_menu_items($menu_id);
    $menu_tree = array();

    // Create a lookup table for menu items
    $menu_items = array();
    foreach ($items as $item) {

    	 $slug = '';

        // If the menu item is linked to a page or post, get its slug
        if ($item->object === 'page' || $item->object === 'post') {
            $slug = get_post_field('post_name', $item->object_id);
        }

        $menu_items[$item->ID] = array(
            'id'       => $item->ID,
            'title'    => $item->title,
            'url'      => $item->url,
            'slug'     => $slug, // Add slug here
            'parent'   => $item->menu_item_parent,
            'children' => array(), // Placeholder for child items
        );
    }

    // Build hierarchical structure
    foreach ($menu_items as $id => &$menu_item) {
        if ($menu_item['parent'] == 0) {
            $menu_tree[] = &$menu_item;
        } else {
            $menu_items[$menu_item['parent']]['children'][] = &$menu_item;
        }
    }

    return $menu_tree;
}

function get_menu_hierarchical_callback($request) {
    $menu_slug = $request['slug'];
    $menu = wp_get_nav_menu_object($menu_slug);

    if (!$menu) {
        return new WP_Error('menu_not_found', 'Menu not found', array('status' => 404));
    }

    $menu_items = get_menu_items_hierarchical($menu->term_id);

    return rest_ensure_response(array(
        'id'    => $menu->term_id,
        'name'  => $menu->name,
        'slug'  => $menu->slug,
        'items' => $menu_items,
    ));
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/menu/(?P<slug>[a-zA-Z0-9-_]+)/', array(
        'methods'  => 'GET',
        'callback' => 'get_menu_hierarchical_callback',
        'permission_callback' => '__return_true',
    ));
});


// Rest API to fetch ACF custom post type

add_action('rest_api_init', function () {

    register_rest_route('custom/v1', '/posts/', array(
        'methods'  => 'GET',
        'callback' => 'fetch_custom_posts_with_acf_and_taxonomy',
        'permission_callback' => '__return_true', // Public endpoint (no auth). Secure it if needed.
    ));

});

/**
 * Callback function to fetch custom posts with ACF fields and taxonomy terms
 */
function fetch_custom_posts_with_acf_and_taxonomy($request) {

    $args = array(
        'post_type'      => 'portfolio', // Replace with your CPT slug
        'posts_per_page' => -1,             // Fetch all posts
        'post_status'    => 'publish',
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return new WP_REST_Response(array('message' => 'No custom posts found'), 404);
    }

    $posts_data = array();

    while ($query->have_posts()) {
        $query->the_post();

        $post_id = get_the_ID();

        // Fetch ACF fields (you can list specific fields if needed)
        $acf_fields = get_fields($post_id); // Returns all ACF fields for the post

        // Fetch terms from the custom taxonomy
        $terms = get_the_terms($post_id, 'portfolio-category'); // Replace with your taxonomy slug

        $term_names = array();
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $term_names[] = $term->name;
            }
        }

        // Build the post data
        $posts_data[] = array(
            'id'          => $post_id,
            'title'       => get_the_title(),
            'content'     => get_the_content(),
            'slug'        => get_post_field('post_name', $post_id), // Slug added here
            'acf_fields'  => $acf_fields,
            'taxonomies'  => $term_names,
            'link'        => get_permalink(),
        );
    }

    // Reset post data
    wp_reset_postdata();

    // Return the response
    return new WP_REST_Response($posts_data, 200);
}

// Rest API to fetch portfolio category

add_action('rest_api_init', function () {

    register_rest_route('custom/v1', '/portfolio-categories/', array(
        'methods'             => 'GET',
        'callback'            => 'get_portfolio_categories',
        'permission_callback' => '__return_true', // Public access — restrict if needed
    ));

});

/**
 * Callback to return all terms from 'portfolio-category' taxonomy
 */
function get_portfolio_categories($request) {

    $terms = get_terms(array(
        'taxonomy'   => 'portfolio-category',
        'hide_empty' => false, // Set true if you want to exclude empty categories
    ));

    if (is_wp_error($terms) || empty($terms)) {
        return new WP_REST_Response(['message' => 'No terms found.'], 404);
    }

    $response = array();

    foreach ($terms as $term) {
        $response[] = array(
            'id'          => $term->term_id,
            'name'        => $term->name,
            'slug'        => $term->slug,
            'description' => $term->description,
            'count'       => $term->count,
        );
    }

    return new WP_REST_Response($response, 200);
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/portfolio-categories-with-post/', array(
        'methods'             => 'GET',
        'callback'            => 'get_portfolio_categories_with_posts',
        'permission_callback' => '__return_true',
    ));
});

/**
 * Returns all terms from 'portfolio-category' taxonomy along with their posts
 */
function get_portfolio_categories_with_posts($request) {

    $terms = get_terms(array(
        'taxonomy'   => 'portfolio-category',
        'order' => 'DESC',
        'hide_empty' => false,
    ));

    if (is_wp_error($terms) || empty($terms)) {
        return new WP_REST_Response(['message' => 'No terms found.'], 404);
    }

    $response = array();

    foreach ($terms as $term) {

        // Fetch posts under each taxonomy term
        $posts = get_posts(array(
            'post_type'      => 'portfolio', // Adjust to your CPT slug
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'portfolio-category',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
        ));

        $post_list = array();

        foreach ($posts as $post) {

            // Get taxonomy terms for this post
            $post_terms = get_the_terms($post->ID, 'portfolio-category');
            $taxonomy_list = array();

            if (!empty($post_terms) && !is_wp_error($post_terms)) {
                foreach ($post_terms as $t) {
                    $taxonomy_list[] = array(
                        'id'   => $t->term_id,
                        'name' => $t->name,
                        'slug' => $t->slug,
                    );
                }
            }

            $post_list[] = array(
                'id'         => $post->ID,
                'title'      => get_the_title($post->ID),
                'content'     => apply_filters('the_content', $post->post_content),
                'slug'       => $post->post_name,
                'link'       => get_permalink($post->ID),
                'acf_fields' => function_exists('get_fields') ? get_fields($post->ID) : [],
                'taxonomies'    => $taxonomy_list
            );
        }

        $response[] = array(
            'id'          => $term->term_id,
            'name'        => $term->name,
            'slug'        => $term->slug,
            'description' => $term->description,
            'count'       => $term->count,
            'posts'       => $post_list,
        );
    }

    return new WP_REST_Response($response, 200);
}


// Register REST route to fetch post by slug
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/post-by-slug/(?P<slug>[a-zA-Z0-9-_]+)', array(
        'methods'             => 'GET',
        'callback'            => 'get_post_by_slug',
        'permission_callback' => '__return_true',
    ));
});

/**
 * Callback function to get post details by slug
 */
function get_post_by_slug($request) {
    $slug = sanitize_title($request['slug']);

    $post = get_page_by_path($slug, OBJECT, array('post', 'page', 'portfolio')); // Adjust post types as needed

    if (!$post) {
        return new WP_REST_Response(['message' => 'Post not found.'], 404);
    }


      // Get taxonomy terms for this post
            $post_terms = get_the_terms($post->ID, 'portfolio-category');
            $taxonomy_list = array();

            if (!empty($post_terms) && !is_wp_error($post_terms)) {
                foreach ($post_terms as $t) {

                    array_push($taxonomy_list, $t->name);
                    
                }
            }

            $template= "";
            if(in_array("Web Development", $taxonomy_list)){

                 $template= "web";

            }
            else{

                 $template= "seo";
            }

    // Gather ACF fields if ACF is available
    $acf_fields = function_exists('get_fields') ? get_fields($post->ID) : [];

    $data = array(
        'id'         => $post->ID,
        'title'      => get_the_title($post),
        'slug'       => $post->post_name,
        'content'    => apply_filters('the_content', $post->post_content),
        'excerpt'    => get_the_excerpt($post),
        'link'       => get_permalink($post),
        'template'   => $template,
        'taxonomy_list'=> $taxonomy_list,
        'acf_fields' => $acf_fields

    );

    return new WP_REST_Response($data, 200);
}

//Allow webm video

function allow_webm_upload($mimes) {
    $mimes['webm'] = 'video/webm';
    return $mimes;
}
add_filter('upload_mimes', 'allow_webm_upload');

add_theme_support('post-thumbnails');


?>
