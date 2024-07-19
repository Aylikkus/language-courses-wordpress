<?php

get_star_svg_defs();

get_header();

?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/single-course.css">

<?php

while ( have_posts() ) {
    the_post();
?>

<div class="course">
    <div class="course-content">
        <h1><?php strtoupper(the_title()); ?></h1>
        <?php the_excerpt(); ?>
        <div class="course-attributes">
            <?php
			$terms = get_the_terms( $post->ID, 'course-difficulty' );
			if ( ! is_wp_error( $terms ) and $terms[0] ) {
			?>
				<div class="course-level">
					<?php echo $terms[0]->name; ?>
				</div>
				<div class="vertical-line"></div>
			<?php } ?>
			<div class="course-price">
				<?php
					$price = get_post_field( 'price' );
					if ( $price ) {
						echo $price . ' ₽';
					}
					else {
						echo Бесплатно;
					}
				?>
			</div>
        </div>
        <?php
        $education_link = get_course_education_page($post);
		if ($education_link) {
        ?>
        <div class="course-enroll">
			<a href="<?php echo $education_link; ?>" class="popular-course-button">Обучение</a>
		</div>
		<?php } ?>
    </div>
    <div class="course-image">
        <?php
			if ( has_post_thumbnail() )
				echo get_the_post_thumbnail();
			else
				echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
		?>
    </div>
</div>
<div class="linewrapper"><hr></div>
<div class="tab-container">
	<div class="tab-row">
		<a id="Описание" href="#Описание">Описание</a>
		<a id="Рейтинг" href="#Рейтинг">Рейтинг</a>
		<a id="Автор" href="#Автор">Автор</a>
	</div>

	<div id="content=Описание" class="course-info">
		<?php the_content(); ?>
	</div>

	<div id="content=Рейтинг" class="course-info">
		<div class="course-rating">
			<?php
				$rating = get_post_field( 'rating' );
				get_star_svgs($rating);
			?>
		</div>
		<div class="comments">
			<?php comments_template(); ?>
		</div>
	</div>

	<div id="content=Автор" class="course-info">
		<div class="user-card">
			<?php
			$user = get_user_by('id', $post->post_author);
			if ( $user->roles[0] == 'teacher' ) {
				$role = 'преподаватель';
			}
			else if ( $user->roles[0] == 'teacher-temp' ) {
				$role = 'преподаватель (неподтверждён)';
			}
			else if ( $user->roles[0] == 'student' ) {
				$role = 'ученик';
			}
			else {
				$role = $user->roles[0];
			}
			?>
			<div class="user-avatar">
				<?php echo get_avatar( $user->ID, 250 ); ?>
			</div>
			<div class="user-name-role">
				<h3><?php echo $user->data->display_name; ?></h3>
				<p><?php echo 'Роль - ' . $role; ?></p>
				<p><?php echo 'Электронная почта - ' . $user->data->user_email; ?></p>
				<p><?php echo 'Дата регистрации - ' . $user->data->user_registered; ?></p>
			</div>
			<div class="user-description">
			<?php
				$description = get_the_author_meta( 'description', $user->ID );
				if ($description) {
					echo $description;
				}
				else {
					echo 'Нет описания';
				}
			?>
			</div>
		</div>
	</div>
</div>

<?php } ?>

<script src="<?php echo get_template_directory_uri() ?>/page-js/single-course.js"></script>

<?php get_footer(); ?>
