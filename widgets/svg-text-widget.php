<?php

class Svg_Widget extends WP_Widget {

	public const svg_options = array(
		'clock.svg' => 'Clock',
		'quiz.svg' => 'Quiz',
		'lection.svg' => 'Lection',
		'workcount.svg' => 'Workcount',
		'people.svg' => 'People',
		'diploma.svg' => 'Diploma',
		'graduate.svg' => 'Graduate',
		'phone.svg' => 'Phone',
		'mail.svg' => 'Mail',
		'address.svg' => 'Address',
		'forward.svg' => 'Forward',
		'back.svg' => 'Back',
	);

	public function __construct() {
		parent::__construct(
			'svg-widget',  // Base ID
			'Svg Widget'   // Name
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
		echo '<div class="contacts-entry-header">';
		echo file_get_contents(THEME_FOLDER . 'svg/' . $instance['svg']);
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo '</div>';
		echo '<p>';
		echo esc_html__( $instance['text'], 'text_domain' );
		echo '</p>';
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title	= ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
		$text	= ! empty( $instance['text'] ) ? $instance['text'] : esc_html__( '', 'text_domain' );
		$svg	= ! empty( $instance['svg'] ) ? $instance['svg'] : esc_html__( '', 'text_domain' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'Text' ) ); ?>"><?php echo esc_html__( 'Text:', 'text_domain' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" cols="30" rows="10"><?php echo esc_attr( $text ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'svg' ) ); ?>"><?php echo esc_html__( 'Svg:', 'text_domain' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'svg' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'svg' ) ); ?>">
				<?php
					foreach ( Svg_Widget::svg_options as $key => $value ) {
						if ( $key == $instance['svg'] ) {
							echo '<option selected value="' . $key . '">' . $value . '</option>';
						}
						else {
							echo '<option value="' . $key . '">' . $value . '</option>';
						}
					}
				?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['text']  = ( ! empty( $new_instance['text'] ) ) ? $new_instance['text'] : '';
		$instance['svg']  = ( ! empty( $new_instance['svg'] ) ) ? $new_instance['svg'] : 'Clock.svg';
		return $instance;
	}
}
?>
