<?php wp_head(); ?>
<header>
	<div class="top-row">
		<div class="logo">
			<?php
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
				echo '<a style="color: #185c8f; text-decoration: none" href="' . get_home_url() . '">';
				if ( has_custom_logo() ) {
					echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '" style="max-height:50px;">';
				} else {
					echo '<h2 style="white-space: nowrap; margin: 0;">' . get_bloginfo('name') . '</h2>';
				}
				echo '</a>';
			?>
		</div>
		<div class="menu-container">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'primary'
				));
			?>
		</div>
	</div>
</header>

<div class="linewrapper"><hr></div>
