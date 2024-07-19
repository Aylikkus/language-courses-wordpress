<?php
/*
 * Template Name: About Us Page
*/

get_header();

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-about.css">
<?php
while ( have_posts() ) {
    the_post();
?>

<div class="about">
	<div class="about-text">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</div>
	<?php
	if ( has_post_thumbnail() )
		echo get_the_post_thumbnail();
	else
		echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
	?>
</div>

<?php } ?>

<?php get_footer(); ?>
