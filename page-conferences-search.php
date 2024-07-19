<?php
/*
 * Template Name: Conferences Page
*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-conferences-search.css">
<?php

$query = $_GET['query'];

get_header();

?>
<div class="wrapper">

	<div class="search-results">
		<div class="search-result">
			<div class="conferences-grid">
	<?php

	$args = array(
		'post_type'   => 'conferences',
		's'           => $query,
		'nopaging'    => true,
	);
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ) {
		$loop->the_post();
	?>
				<div class="conferences-column">
					<?php
						if ( has_post_thumbnail() )
							echo get_the_post_thumbnail();
						else
							echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
					?>
					<div class="conferences-col-content">
						<h3>
						<?php echo strWordCut(get_the_title(), 120); ?>
						</h3>
						<h4>
							<?php
							$date = get_post_field( 'date' );
							if ( $date ) {
								echo 'Дата: ' . $date;
							}
							else {
								echo 'Дата неизвестна';
							}
							?>
						</h4>
						<?php the_excerpt(); ?>
					</div>
				</div>
	<?php
	}
	?>
			</div>
		</div>
	</div>

	<div class="filters-wrapper">
        <div class="filters">
            <form id="search-form" class="search-input">
                <input type="text" name="query" placeholder="Поиск...">
                <button>Начать поиск</button>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo get_template_directory_uri() ?>/page-js/page-search.js"></script>

<?php

get_footer();

?>
