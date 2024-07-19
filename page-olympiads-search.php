<?php
/*
 * Template Name: Olympiads Search
*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-olympiads-search.css">
<?php

$query = $_GET['query'];

get_header();

?>
<div class="wrapper">

	<div class="search-results">
		<div class="search-result">
			<div class="olympiads-grid">
	<?php

	$args = array(
		'post_type'   => 'Olympiad',
		's'           => $query,
		'nopaging'    => true,
	);
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ) {
		$loop->the_post();
	?>
				<div class="olympiads-column">
					<?php
						if ( has_post_thumbnail() )
							echo get_the_post_thumbnail();
						else
							echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
					?>
					<div class="olympiads-col-content">
						<h3>
						<?php echo strWordCut(get_the_title(), 120); ?>
						</h3>
						<div class="olympiads-col-author">
							<h4>
							<?php
								$expected = get_post_field( 'date' );
								if ( $expected ) {
									echo $expected;
								}
								else {
									echo 'Дата проведения неизвестна';
								}
							?>
							</h4>
						</div>
						<div class="olympiads-open">
							<a href="<?php the_permalink(); ?>">Открыть</a>
						</div>
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

<script src="<?php echo get_template_directory_uri() ?>/page-js/page-olympiads-search.js"></script>

<?php

get_footer();

?>
