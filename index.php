<?php
get_star_svg_defs();
get_header()
?>

<!--
<div class="header-search">
	<div class="search-wrapper">
		<h1>ОГРОМНЫЙ ВЫБОР КУРСОВ ИНОСТРАННЫХ ЯЗЫКОВ ПО БРЯНСКОЙ ОБЛАСТИ</h1>
		<?php
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-course-search.php'
		));

		if ($pages[0]) {
		?>
		<form class="header-search-input" method="get">
			<input type="hidden" name="page_id" value="<?php echo $pages[0]->ID; ?>">
			<input type="text" name="query" placeholder="Поиск...">
			<button type="submit">Начать поиск</button>
		</form>
		<?php } ?>
	</div>
	<?php
		echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
	?>
</div>
-->

<div class="header-image">
	<div class="header-img-wrapper">
		<img src="<?php echo get_template_directory_uri() . '/images/background.jpg' ?>">
	</div>
	<div class="image-text-wrapper">
		<h1>ОГРОМНЫЙ ВЫБОР КУРСОВ ИНОСТРАННЫХ ЯЗЫКОВ ПО БРЯНСКОЙ ОБЛАСТИ</h1>
		<?php
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-course-search.php'
		));

		if ($pages[0]) {
		?>
		<form class="header-search-input" method="get">
			<input type="hidden" name="page_id" value="<?php echo $pages[0]->ID; ?>">
			<input type="text" name="query" placeholder="Поиск...">
			<button type="submit">Начать поиск</button>
		</form>
		<?php } ?>
	</div>
</div>

<div class="linewrapper"><hr></div>

<div class="news">
	<h1>НОВОСТИ</h1>
	<div class="news-grid">
		<?php
			$args = array(
				'post_type'      => 'News',
				'posts_per_page' => 3,
			);
			$loop = new WP_Query($args);
			while ( $loop->have_posts() ) {
				$loop->the_post();

		?>
			<div class="news-column">
				<?php
				$terms = get_the_terms( $post->ID, 'news-category' );
				if ( ! is_wp_error( $terms ) and $terms[0] ) {
				?>
					<div class="news-category">
						<?php echo strtoupper($terms[0]->name); ?>
					</div>
				<?php } ?>
				<?php
					if ( has_post_thumbnail() )
						echo get_the_post_thumbnail();
					else
						echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
				?>
				<div class="news-col-content">
					<div class="news-col-author">
						<?php $author_id = get_post_field( 'post_author' ); ?>
						<?php echo get_avatar( get_the_author_meta( $author_id ) ); ?>
						<h4><?php echo '<a href="' . get_profile_page_for($author_id) . '">' . get_the_author_meta( 'display_name', $author_id ) . '</a>'; ?></h4>
						<div class="vertical-line"></div>
						<h4><?php echo get_the_date(); ?></h4>
						<div class="vertical-line"></div>
						<h4><?php echo get_read_time($post); ?></h4>
					</div>
					<h3><?php the_title() ?></h3>
					<?php the_excerpt(); ?>
					<div class="news-open">
						<a href="<?php the_permalink(); ?>">Открыть</a>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'page-news-search.php'
	));
	if ($pages[0]) {
		?>
		<div class="all-content-link"><a href="<?php echo get_the_permalink($pages[0]); ?>">Все новости</a></div>
		<?php
	}
	?>
</div>
<div class="linewrapper"><hr></div>
<ul id="popular-course-sidebar">
	<?php
		if ( is_active_sidebar( 'popular-course-sidebar' ) ) {
			dynamic_sidebar( 'popular-course-sidebar' );
		}
	?>
</ul>
<div class="linewrapper"><hr></div>
<div class="courses">
	<h1>КУРСЫ НА ВЫБОР</h1>
	<div class="courses-grid">
	<?php
	$args = array(
		'post_type'      => 'Course',
		'posts_per_page' => 6,
	);
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ) {
		$loop->the_post();
		?>
		<div class="course-box">
			<?php
				$terms = get_the_terms( $post->ID, 'course-difficulty' );
				if ( ! is_wp_error( $terms ) and $terms[0] ) {
				?>
					<div class="course-difficulty
						<?php if ( $terms[0]->name == "Начинающий" ) {
							echo "diff-green";
						}
						elseif ( $terms[0]->name == "Продвинутый" ) {
							echo "diff-red";
						}
						?>">
						<?php echo strtoupper($terms[0]->name); ?>
					</div>
				<?php } ?>
			<?php
				if ( has_post_thumbnail() )
					echo get_the_post_thumbnail();
				else
					echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
			?>
			<div class="course-info">
				<div class="course-header">
					<h2><?php the_title(); ?></h2>
					<div class="vertical-line"></div>
					<div class="course-author">
						<?php $author_id = get_post_field( 'post_author' ); ?>
						<?php echo get_avatar( get_the_author_meta( $author_id ) ); ?>
						<h4><?php echo '<a href="' . get_profile_page_for($author_id) . '">' . get_the_author_meta( 'display_name', $author_id ) . '</a>'; ?></h4>
					</div>
				</div>
				<div class="course-attrbiutes">
					<div class="course-hours">
						<img src="<?php echo get_template_directory_uri()?>/svg/clock.svg">
						<?php
							$hours_field = get_post_field( 'hours' );
							if ($hours_field)
								echo $hours_field;
							else
								echo "0";
						?> ч.
					</div>
					<div class="vertical-line"></div>
					<div class="course-people-count">
						<img src="<?php echo get_template_directory_uri()?>/svg/people.svg">
						<?php
							$people_count = get_post_field( 'people-count' );
							if ($people_count)
								echo $people_count;
							else
								echo "0";
						?> чел.
					</div>
					<div class="vertical-line"></div>
					<div class="course-work-count">
						<img src="<?php echo get_template_directory_uri()?>/svg/workcount.svg">
						<?php
							$lections = get_post_field( 'lections' );
							if ($lections)
								echo $lections;
							else
								echo "0";
						?> лек.
					</div>
					<div class="vertical-line"></div>
					<div class="course-rating">
						<?php
							$rating = get_post_field( 'rating' );
							get_star_svgs($rating);
						?>
					</div>
				</div>
				<?php
					$terms = get_the_terms( $post->ID, 'course-features' );
					if ( $terms and ! is_wp_error( $terms ) ) {
				?>
				<div class="course-list-description">
					<ul>
						<?php
							foreach ( $terms as $term ) {
								echo '<li>' . $term->name . '</li>';
							}
						?>
					</ul>
				</div>
				<?php } ?>
			</div>
			<div class="course-price-enroll">
				<div class="course-price">
					<?php
						$price = get_post_field( 'price' );
						if ( $price ) {
							echo $price . ' ₽';
						}
						else {
							echo 'Бесплатно';
						}
					?>
				</div>
				<div class="course-enroll">
					<a href="<?php the_permalink(); ?>">Записаться</a>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
	<?php
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'page-course-search.php'
	));
	if ($pages[0]) {
		?>
		<div class="all-content-link"><a href="<?php echo get_the_permalink($pages[0]); ?>">Все курсы</a></div>
		<?php
	}
	?>
</div>
<div class="linewrapper"><hr></div>
<div class="manuals">
	<h1>СПРАВОЧНИКИ</h1>
	<div class="manuals-grid">
	<?php

	$args = array(
		'post_type'   => 'Manual',
		'nopaging'    => true,
		'posts_per_page' => 3,
	);
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ) {
		$loop->the_post();
	?>
		<div class="manual-box">
			<?php
				if ( has_post_thumbnail() )
					echo get_the_post_thumbnail();
				else
					echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
			?>
			<div class="manual-info">
				<div class="manual-header">
					<h2><?php the_title(); ?></h2>
				</div>
				<div class="manual-attributes">
					<div class="manual-chapters">
						<img src="<?php echo get_template_directory_uri()?>/svg/workcount.svg">
						<?php
						$dom = new DOMDocument();
						$content = get_the_content();
						if ($content) $dom->loadHTML($content);
						$elems = $dom->getElementsByTagName('h3');
						if ($elems) {
							$count = $elems->length;
						}
						else {
							$count = 0;
						}
						echo $count;
						?>
						<?php
						if ($count % 10 > 1 && $count % 10 < 5) {
							echo 'раздела';
						}
						else if ($count % 10 == 0 || $count % 10 > 4) {
							echo 'разделов';
						}
						else {
							echo 'раздел';
						}
						?>
					</div>
				</div>
			</div>
			<div class="manual-open">
				<a href="<?php the_permalink(); ?>">Открыть</a>
			</div>
		</div>
	<?php } ?>
	</div>
	<?php
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'page-manual-search.php'
	));
	if ($pages[0]) {
		?>
		<div class="all-content-link"><a href="<?php echo get_the_permalink($pages[0]); ?>">Все справочники</a></div>
		<?php
	}
	?>
</div>
<div class="linewrapper"><hr></div>
<div class="metrics-wrapper">
	<h1>НАШИ ДОСТИЖЕНИЯ</h1>
	<div class="metrics">
		<div class="metric-containter">
			<div class="metric-svg-container"><img src="<?php echo get_template_directory_uri()?>/svg/people.svg"></div>
			<p>Число статей</p>
			<h2>...</h2>
		</div>
		<div class="metric-containter">
			<div class="metric-svg-container"><img src="<?php echo get_template_directory_uri()?>/svg/graduate.svg"></div>
			<p>Число справочников</p>
			<h2>...</h2>
		</div>
		<div class="metric-containter">
			<div class="metric-svg-container"><img src="<?php echo get_template_directory_uri()?>/svg/diploma.svg"></div>
			<p>Число курсов</p>
			<h2>...</h2>
		</div>
		<div class="metric-containter">
			<div class="metric-svg-container"><img src="<?php echo get_template_directory_uri()?>/svg/diploma.svg"></div>
			<p>Число прохождений курса</p>
			<h2>...</h2>
		</div>
	</div>
</div>
<div class="linewrapper"><hr></div>
<div class="contacts">
	<div class="contacts-text">
		<h1>СВЯЖИТЕСЬ С НАМИ</h1>
		<div class="contacts-text">
			<ul id="contacts-sidebar">
				<?php
					if ( is_active_sidebar( 'contacts-sidebar' ) ) {
						dynamic_sidebar( 'contacts-sidebar' );
					}
				?>
			</ul>
		</div>
	</div>
	<ul id="contacts-sidebar-right">
	<?php
		if ( is_active_sidebar( 'contacts-sidebar-right' ) ) {
			dynamic_sidebar( 'contacts-sidebar-right' );
		}
	?>
	</ul>
</div>

<?php get_footer(); ?>
