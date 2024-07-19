<?php
/*
 * Template Name: News Search
*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-news-search.css">
<?php

$query = $_GET['query'];
$category = $_GET['category'];

get_header();

?>
<div class="wrapper">

	<div class="search-results">
		<div class="search-result">
			<div class="news-grid">
	<?php

	$args = array(
		'post_type'   => 'News',
		's'           => $query,
		'nopaging'    => true,
		'tax_query'	  => array(),
	);
	if ($category) {
		$args['tax_query'][] = array(
            'taxonomy' => 'news-category',
            'field' => 'term_id',
            'terms' => $category,
            'operator' => 'IN',
        );
	}
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ) {
		$loop->the_post();
	?>
				<div class="news-column">
					<?php
						if ( has_post_thumbnail() )
							echo get_the_post_thumbnail();
						else
							echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
					?>
					<div class="news-col-content">
						<h3>
						<?php echo strWordCut(get_the_title(), 120); ?>
						</h3>
						<div class="news-col-author">
							<?php $author_id = get_post_field( 'post_author' ); ?>
							<?php echo get_avatar( get_the_author_meta( $author_id ) ); ?>
							<h4><?php echo get_the_author_meta( 'display_name', $author_id ); ?></h4>
							<div class="vertical-line"></div>
							<h4><?php echo get_the_date(); ?></h4>
							<div class="vertical-line"></div>
							<h4><?php echo get_read_time($post); ?></h4>
						</div>
						<div class="news-open">
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
            <h3>Категории</h3>
            <form id="categories" class="numbers-form">
                <select name="category" class="select">
                    <option value="">Не выбрано</option>
                    <?php
                        $category_terms = get_terms( array(
                            'taxonomy'  =>  'news-category',
                            'hide_empty' => false
                        ) );
                        foreach ( $category_terms as $cat ) {
                            echo '<option value="' . $cat->term_id . '">' . $cat->name . '</option>';
                        }
                    ?>
                </select>
            </form>
            <!-- Дата публикации -->
            <form id="search-form" class="search-input">
                <input type="text" name="query" placeholder="Поиск...">
                <button>Начать поиск</button>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo get_template_directory_uri() ?>/page-js/page-news-search.js"></script>

<?php

get_footer();

?>
