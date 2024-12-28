<?php
/**
 * JM-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JM-theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */

// functions.php
function my_theme_setup() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'text_domain'),
    ));
}
add_action('after_setup_theme', 'my_theme_setup');

 function jm_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on JM-theme, use a find and replace
		* to change 'jm-theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'jm-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'jm-theme' ),
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
			'jm_theme_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

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
add_action( 'after_setup_theme', 'jm_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function jm_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'jm_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'jm_theme_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function jm_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'jm-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'jm-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'jm_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function jm_theme_scripts() {
	wp_enqueue_style( 'jm-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'jm-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'jm-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'jm_theme_scripts' );

/** Enqueuing ajax-load.js in functions.php */
function jm_enqueue_scripts() {
    // Enqueue theme's JavaScript
    wp_enqueue_script('ajax-load', get_template_directory_uri() . '/js/ajax-load.js', array('jquery'), null, true);

    // Localize script with AJAX URL and parameters
    wp_localize_script('ajax-load', 'jm_ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'posts_per_page' => get_option('posts_per_page'),
        'max_page' => wp_count_posts()->publish / get_option('posts_per_page'),
    ));
}
add_action('wp_enqueue_scripts', 'jm_enqueue_scripts');

/** Enqueuing ajax-load.js in functions.php */

/** Modify functions.php to Handle AJAX Requests: */

function jm_load_more_posts() {
    // Get the page number from the AJAX request
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Query the posts
    $query = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => $paged,
    ));

    // Generate the posts HTML
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Use your existing post template part
            get_template_part('template-parts/content', get_post_format());
        }
    } else {
        echo 'no_more_posts';
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_load_more_posts', 'jm_load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'jm_load_more_posts');

/** Modify functions.php to Handle AJAX Requests: */


/** Add Theme Setting for Font Color */

function jm_customize_register($wp_customize) {
    // Add Font Color Setting
    $wp_customize->add_setting('jm_font_color', array(
        'default' => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'jm_font_color', array(
        'label' => 'Font Color',
        'section' => 'colors',
        'settings' => 'jm_font_color',
    )));
}
add_action('customize_register', 'jm_customize_register');

/** Add dynamic css*/

function jm_dynamic_styles() {
    $font_color = get_theme_mod('jm_font_color', '#000000'); // Default to black
    ?>
    <style type="text/css">
        body {
            color: <?php echo esc_attr($font_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'jm_dynamic_styles');


/** Add Theme Setting for Font Color */

/** Rating Feature For Admins in pages */

function jm_add_rating_meta_box() {
    add_meta_box(
        'jm_rating_meta_box',
        'Page Rating (Stars)',
        'jm_display_star_rating_meta_box',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'jm_add_rating_meta_box');

function jm_display_star_rating_meta_box($post) {
    // Get the current rating value
    $rating = get_post_meta($post->ID, '_jm_page_rating', true);
    ?>
    <div id="jm-star-rating">
        <?php for ($i = 1; $i <= 5; $i++) : ?>
            <span class="jm-star <?php echo ($i <= $rating) ? 'selected' : ''; ?>" data-value="<?php echo $i; ?>">&#9733;</span>
        <?php endfor; ?>
    </div>
    <input type="hidden" name="jm_page_rating" id="jm_page_rating" value="<?php echo esc_attr($rating); ?>">
    <style>
        #jm-star-rating {
            cursor: pointer;
        }
        .jm-star {
            font-size: 20px;
            color: #ccc;
            transition: color 0.3s ease;
        }
        .jm-star.selected {
            color: gold;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('#jm-star-rating .jm-star');
            const input = document.getElementById('jm_page_rating');

            stars.forEach(star => {
                star.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    input.value = value;

                    // Highlight stars
                    stars.forEach(s => {
                        s.classList.remove('selected');
                        if (s.getAttribute('data-value') <= value) {
                            s.classList.add('selected');
                        }
                    });
                });
            });
        });
    </script>
    <?php
}

/** Now Save Rating Meta Field */


function jm_save_page_rating($post_id) {
    if (array_key_exists('jm_page_rating', $_POST)) {
        update_post_meta($post_id, '_jm_page_rating', intval($_POST['jm_page_rating']));
    }
}
add_action('save_post', 'jm_save_page_rating');



/** Now Save Rating Meta Field */


/** Rating Feature For Admins in pages */

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
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}




