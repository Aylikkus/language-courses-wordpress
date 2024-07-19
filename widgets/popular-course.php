<?php

class PopularCourse_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'popular-course-widget',  // Base ID
			'Popular Course Widget'   // Name
		);
	}

	public $args = array(
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
		'before_widget' => '<div class="widget-wrap">',
		'after_widget'  => '</div></div>',
	);

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$posts = get_posts(array(
            'name' => $instance['course_slug'],
            'post_type' => 'course',
            'numberposts' => 1,
        ));
		$course = $posts[0];
        if ($course) {
?>
<div class="popular-course">
	<div class="popular-course-content">
		<h1><?php echo strtoupper(get_the_title($course)); ?></h1>
		<?php echo get_the_excerpt($course); ?>
		<div class="popular-course-attributes">
			<?php
			$terms = get_the_terms( $course->ID, 'course-difficulty' );
			if ( ! is_wp_error( $terms ) && $terms[0] ) {
			?>
				<div class="popular-course-level">
					<?php echo $terms[0]->name; ?>
				</div>
				<div class="vertical-line"></div>
			<?php } ?>
			<div class="popular-course-price">
				<?php
					$price = get_post_field( 'price', $course );
					if ( $price ) {
						echo $price . ' ₽';
					}
					else {
						echo 'Бесплатно';
					}
				?>
			</div>
			<div class="vertical-line"></div>
			<div class="popular-course-rating">
				<?php
					$rating = get_post_field( 'rating', $course );
					get_star_svgs($rating);
				?>
			</div>
		</div>
		<div class="course-enroll">
			<a href="<?php echo get_the_permalink($course); ?>" class="popular-course-button">Открыть</a>
		</div>
	</div>
	<div class="popular-course-image">
		<?php
			if ( has_post_thumbnail($course) )
				echo get_the_post_thumbnail($course);
			else
				echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
		?>
	</div>
</div>
<?php
        }
        else {
            echo '<div class="message-box">Нет курса с ярлыком "' . $instance['course_slug'] . '"</div>';
        }
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$slug = ! empty( $instance['course_slug'] ) ? $instance['course_slug'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'course_slug' ); ?>"><?php echo 'Course Slug'; ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'course_slug' ); ?>" name="<?php echo $this->get_field_name( 'course_slug' ); ?>" type="text" value="<?php echo $slug; ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['course_slug'] = ( ! empty( $new_instance['course_slug'] ) ) ? $new_instance['course_slug'] : '';
		return $instance;
	}
}
?>
