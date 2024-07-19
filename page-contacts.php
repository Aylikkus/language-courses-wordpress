<?php
/*
 * Template Name: Contacts Page
*/

get_header();

while ( have_posts() ) {
    the_post();
?>

<div class="contacts">
	<div class="contacts-text">
		<h1><?php the_title(); ?></h1>
		<ul id="contacts-sidebar">
            <?php
                if ( is_active_sidebar( 'contacts-sidebar' ) ) {
                    dynamic_sidebar( 'contacts-sidebar' );
                }
            ?>
        </ul>
	</div>
	<ul id="contacts-sidebar-right">
	<?php
		if ( is_active_sidebar( 'contacts-sidebar-right' ) ) {
			dynamic_sidebar( 'contacts-sidebar-right' );
		}
	?>
	</ul>
</div>

<?php } ?>

<?php get_footer(); ?>
