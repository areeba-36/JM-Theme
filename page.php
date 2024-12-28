<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JM-theme
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.


$rating = get_post_meta(get_the_ID(), '_jm_page_rating', true);
if ($rating) {
    echo '<div class="page-rating">';
    for ($i = 1; $i <= 5; $i++) {
        echo '<span class="star ' . ($i <= $rating ? 'filled' : '') . '">&#9733;</span>';
    }
    echo '</div>';
} else {
    echo '<p>No rating has been assigned to this page.</p>';
}
		?>


	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
