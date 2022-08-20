<?php

/**
 * projetcs functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package projetcs
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function projetcs_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on projetcs, use a find and replace
		* to change 'projetcs' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('projetcs', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'projetcs'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'projetcs_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'projetcs_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function projetcs_content_width()
{
	$GLOBALS['content_width'] = apply_filters('projetcs_content_width', 640);
}
add_action('after_setup_theme', 'projetcs_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function projetcs_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'projetcs'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'projetcs'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'projetcs_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function projetcs_scripts()
{
	wp_enqueue_style('projetcs-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('projetcs-style', 'rtl', 'replace');

	wp_enqueue_script('projetcs-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'projetcs_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

//--------------
//-------------- Custom Code Here
//--------------

function get_the_user_ip()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if (strpos($ip, '77.29') === 0) {
		$url = 'https://www.google.com/';
		wp_redirect($url);
		exit();
	}
}
add_action('template_redirect', 'get_the_user_ip');

//----------------

function custom_post_type()
{

	// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x('Projects', 'Post Type General Name'),
		'singular_name'       => _x('Project', 'Post Type Singular Name'),
		'menu_name'           => __('Projects'),
		'parent_item_colon'   => __('Parent Project'),
		'all_items'           => __('All Projects'),
		'view_item'           => __('View Project'),
		'add_new_item'        => __('Add New Project'),
		'add_new'             => __('Add New'),
		'edit_item'           => __('Edit Project'),
		'update_item'         => __('Update Project'),
		'search_items'        => __('Search Project'),
		'not_found'           => __('Not Found'),
		'not_found_in_trash'  => __('Not found in Trash'),
	);

	// Set other options for Custom Post Type

	$args = array(
		'label'               => __('projects'),
		'description'         => __('Project description'),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array('project-types'),
		/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest' => true,

	);

	// Registering your Custom Post Type
	register_post_type('projects', $args);
}

/* Hook into the 'init' action so that the function
	* Containing our post type registration is not 
	* unnecessarily executed. 
	*/

add_action('init', 'custom_post_type', 0);


//--------------------------------

function add_custom_taxonomies()
{
	// Add new "Locations" taxonomy to Posts
	register_taxonomy('project-types', 'projects', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => false,
		'show_in_rest' => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		'show_in_admin_bar'   => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x('Project Types', 'taxonomy general name'),
			'singular_name' => _x('Project Type', 'taxonomy singular name'),
			'search_items' =>  __('Search Project Types'),
			'all_items' => __('All Project Types'),
			'parent_item' => __('Parent Project Types'),
			'parent_item_colon' => __('Parent Project Type:'),
			'edit_item' => __('Edit Project Type'),
			'update_item' => __('Update Project Type'),
			'add_new_item' => __('Add New Project Type'),
			'new_item_name' => __('New Project Type Name'),
			'menu_name' => __('Project Types'),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'project-types', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}
add_action('init', 'add_custom_taxonomies');


//------------------------------------------
add_action('init', 'setup_init');


function setup_init()
{

	add_action('rest_api_init', 'custom_endpoint');

	function custom_endpoint()
	{

		register_rest_route('projects', '/architecture', array(
			'methods' => 'GET',
			'callback' => 'custom_callback',
		));
		register_rest_route('projects', '/architecture/(?P<loggedin>\d+)', array(
			'methods' => 'GET',
			'callback' => 'custom_callback',
		));
	}
	function custom_callback($request_data)
	{
		$loggedIn = $request_data['loggedin'];
		if ($loggedIn == 1) {
			$num = 6;
		} else {
			$num = 3;
		}

		$the_query = new WP_Query(array(
			'post_type' => 'projects',
			'taxonomy' => 'project-types',
			'term' => 'architecture',
			'posts_per_page' => $num,
		));

		$projetcs = [];
		if ($the_query->have_posts()) :
			while ($the_query->have_posts()) : $the_query->the_post();
				$projetcs[] =  array(
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'link' => get_permalink()
				);
			endwhile;
		endif;
		$post_data = array(
			'success' => true,
			'data' => $projetcs
		);
		$post_data = wp_send_json($post_data);
		return $post_data;
	}
}


//----------------------------
function hs_give_me_coffee()
{
	$request = wp_remote_get('https://coffee.alexflipnote.dev/random.json');
	if (!empty($request)) {
		$data = json_decode($request['body'], TRUE);
		echo "<img src='" . $data['file'] . "' />";
	}
}
add_shortcode('GetCoffee', 'hs_give_me_coffee');
//-------------------------------------
function kanyeQuotes()
{
	for ($i = 0; $i < 5; $i++) {
		$request = wp_remote_get('https://api.kanye.rest/');
		if (!empty($request)) {
			$data = json_decode($request['body'], TRUE);
			echo "<h5>" . $data['quote'] . "</h5>";
		}
	}
}
add_shortcode('KanyeQuotes', 'kanyeQuotes');
  //-------------------------------------
