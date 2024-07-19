<?php

class YandexMap_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'yandexmap-widget',  // Base ID
			'Yandex Map Widget'   // Name
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
		echo '<div class="yandex-maps">';
		?>
		<div class="yandex-maps" style="position:relative;overflow:hidden;"><iframe src="<?php echo $instance['link']; ?>" width="560" height="400" frameborder="1" allowfullscreen="true" style="position:relative;"></iframe></div>
		<?php
		echo '</div>';
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$link = ! empty( $instance['link'] ) ? $instance['link'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php echo 'Link from Yandex Maps Widget:'; ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';
		return $instance;
	}
}
?>
