<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package JM-theme
 */

?>

	<footer id="colophon" class="site-footer">

		<div class="site-info">
		<span>© Copyrights </span><?php echo date('Y'); ?>-<?php echo date('y')+1; ?> | All Rights Reserved by <a href="https://softsquare.io/" target="no_blank" rel="noopener noreferrer">SoftSquare.io</a>
		</div>

	</footer>

<?php wp_footer(); ?>

</body>
</html>
