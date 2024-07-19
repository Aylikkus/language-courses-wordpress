<footer>
	<div class="footer-columns">
		<div class="footer-logo-column">
			<div class="logo-text">
				<?php
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
					if ( has_custom_logo() ) {
						echo '<a style="color: #185c8f; text-decoration: none" href="' . get_home_url() . '">';
						echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '" style="max-height:25px;">';
						echo '</a>';
					}
				?>
				<h4><?php echo bloginfo('name'); ?></h4>
			</div>
			<ul id="footer-sidebar">
				<?php
					if ( is_active_sidebar( 'footer-sidebar' ) ) {
						dynamic_sidebar( 'footer-sidebar' );
					}
				?>
			</ul>
		</div>
		<div class="footer-info-column">
			<ul id="footer-sidebar">
			<?php
				if ( is_active_sidebar( 'footer-first-column' ) ) {
					dynamic_sidebar( 'footer-first-column' );
				}
			?>
			</ul>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-col-1'
				));
			?>
		</div>
		<div class="footer-info-column">
			<ul id="footer-sidebar">
			<?php
				if ( is_active_sidebar( 'footer-second-column' ) ) {
					dynamic_sidebar( 'footer-second-column' );
				}
			?>
			</ul>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-col-2'
				));
			?>
		</div>
		<div class="footer-info-column">
			<ul id="footer-sidebar">
			<?php
				if ( is_active_sidebar( 'footer-third-column' ) ) {
					dynamic_sidebar( 'footer-third-column' );
				}
			?>
			</ul>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-col-3'
				));
			?>
		</div>
	</div>
	<div class="linewrapper"><hr></div>
	<div class="footer-copyright">
		© <?php echo bloginfo('name') . ' ' . date('Y'); ?>
	</div>
</footer>
