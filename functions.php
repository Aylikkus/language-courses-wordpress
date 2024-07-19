<?php

define('THEME_FOLDER', trailingslashit(get_template_directory(__FILE__)));
require_once(THEME_FOLDER . 'widgets/svg-text-widget.php');
require_once(THEME_FOLDER . 'widgets/yandexmap-widget.php');
require_once(THEME_FOLDER . 'widgets/popular-course.php');

// Библиотеки
require_once(THEME_FOLDER . 'libs/scores.php');
require_once(THEME_FOLDER . 'libs/olympiads.php');

function get_read_time($post) {
	$content = get_post_field( 'post_content', $post->ID );
	$count_words = substr_count( strip_tags( $content ), ' ') + 1;

	$read_time = ceil( $count_words / 150);

	$suffix = 'минут';

	if ($read_time != 11 && $read_time % 10 == 1) {
		$suffix = 'минута';
	}
	else if (($read_time < 12 || $read_time > 14) && ($read_time % 10 > 1 && $read_time % 10 < 5)) {
		$suffix = 'минуты';
	}

	return $read_time . ' ' . $suffix;
}

function strWordCut($string, $length, $end='...')
{
    $string = strip_tags($string);

    if (strlen($string) > $length) {

        // truncate string
        $stringCut = substr($string, 0, $length);

        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . " " . $end;
    }

    return $string;
}

function get_star_svgs($rating) {
	$rating_float = floatval($rating);

	if ($rating_float <= 5 and $rating_float >= 0) {
		$floor = floor($rating_float);
		for ($i = 1; $i <= $floor; $i++) {
			?>
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 32 32"><path fill="url(#star-gradient100)" d="M20.388,10.918L32,12.118l-8.735,7.749L25.914,31.4l-9.893-6.088L6.127,31.4l2.695-11.533L0,12.118l11.547-1.2L16.026,0.6L20.388,10.918z"/></svg>
			<?php
		}
		if ( $rating_float - $floor >= 0.5 )
		{
			?>
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 32 32"><path fill="url(#star-gradient50)" d="M20.388,10.918L32,12.118l-8.735,7.749L25.914,31.4l-9.893-6.088L6.127,31.4l2.695-11.533L0,12.118l11.547-1.2L16.026,0.6L20.388,10.918z"/></svg>
			<?php
		}
		else if ( $rating_float != 5 )
		{
			?>
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 32 32"><path fill="url(#star-gradient0)" d="M20.388,10.918L32,12.118l-8.735,7.749L25.914,31.4l-9.893-6.088L6.127,31.4l2.695-11.533L0,12.118l11.547-1.2L16.026,0.6L20.388,10.918z"/></svg>
			<?php
		}
		for ($i = $floor + 2; $i <= 5; $i++) {
			?>
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 32 32"><path fill="url(#star-gradient0)" d="M20.388,10.918L32,12.118l-8.735,7.749L25.914,31.4l-9.893-6.088L6.127,31.4l2.695-11.533L0,12.118l11.547-1.2L16.026,0.6L20.388,10.918z"/></svg>
			<?php
		}
	}
}

function get_star_svg_defs() {
	?>
	<svg class="svg-star-defs" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<defs>
			<linearGradient id="star-gradient0">
				<stop offset="0" stop-color="#185c8f"/>
				<stop offset="0" stop-color="grey"/>
			</linearGradient>
			<linearGradient id="star-gradient50">
				<stop offset="50%" stop-color="#185c8f"/>
				<stop offset="50%" stop-color="grey"/>
			</linearGradient>
			<linearGradient id="star-gradient100">
				<stop offset="100%" stop-color="#185c8f"/>
				<stop offset="100%" stop-color="grey"/>
			</linearGradient>
		</defs>
	</svg>
	<?php
}

function get_profile_page() {
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'page-profile.php'
	));

	return get_the_permalink($pages[0]->ID);
}

function get_profile_page_for_logged_in_user() {
	if ( is_user_logged_in() ) {
		return get_profile_page() . '&userid=' . wp_get_current_user()->ID;
	}
}

function get_profile_page_for($userID) {
	return get_profile_page() . '&userid=' . $userID;
}

function get_custom_registration_link() {
	$args = array(
		'name'        => 'login',
		'post_type'   => 'page',
		'post_status' => 'publish',
		'numberposts' => 1
	);
	$posts = get_posts($args);
	return get_permalink($posts[0]->ID) . '&register=1';
}

function get_custom_login_link() {
	$args = array(
		'name'        => 'login',
		'post_type'   => 'page',
		'post_status' => 'publish',
		'numberposts' => 1
	);
	$posts = get_posts($args);
	return get_permalink($posts[0]->ID);
}

function get_course_education_page($post) {
	$pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'education-page.php'
	));

	$chaptersTerms = get_the_terms( $post->ID, 'course-content' );
	$separated = explode('@', $chaptersTerms[0]->name);
	$chapterName = $separated[0];
	$subchapters = explode('|', $separated[1]);
	$firstSubchapter = $subchapters[0];

	if ($firstSubchapter[0] == 'l') {
		$type = 'lection';
		$firstSubchapter = substr($firstSubchapter, 1);
	}

	if ($pages[0] && $firstSubchapter && $type) {
		return get_the_permalink($pages[0]) . '&course_slug=' . $post->post_name .
			'&type=' . $type . '&content=' . $firstSubchapter;
	}

	return false;
}

add_theme_support( 'title-tag' );
add_theme_support( 'custom-logo' );
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );

function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}

add_action('get_header', 'remove_admin_login_header');

function enqueue_styles() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'enqueue_styles' );

add_action( 'switch_theme', 'remove_user_roles' );
function remove_user_roles() {
	remove_role( 'teacher' );
	remove_role( 'teacher-temp' );
	remove_role( 'student' );
}

function add_user_roles() {
	add_role('teacher', 'Teacher',
		array(
			'read'         => true,  // true разрешает эту возможность
			'edit_posts'   => true,  // true разрешает редактировать посты
			'upload_files' => true,  // может загружать файлы
		));
	add_role('teacher-temp', 'Teacher Temporary',
		array(
			'read'         => true,
			'edit_posts'   => false,
			'upload_files' => false,
		));
	add_role('student', 'Student',
		array(
			'read'         => true,
			'edit_posts'   => false,
			'upload_files' => false,
		));
}
add_action( 'after_switch_theme', 'add_user_roles' );

function custom_logo_setup() {
	$defaults = array(
		'flex-height'          => true,
		'flex-width'           => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => true,
	);
	add_theme_support( 'custom-logo', $defaults );
};

add_action( 'after_setup_theme', 'custom_logo_setup' );

function register_my_widgets(){
	register_sidebar( array(
		'name'          => 'Footer Area',
		'id'            => "footer-sidebar",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );

	register_sidebar( array(
		'name'          => 'Contacts Sidebar',
		'id'            => "contacts-sidebar",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );

	register_sidebar( array(
		'name'          => 'About Sidebar',
		'id'            => "about-sidebar",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );

	register_sidebar( array(
		'name'          => 'Contacts Sidebar Right',
		'id'            => "contacts-sidebar-right",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );

	register_sidebar( array(
		'name'          => 'Popular Course Sidebar',
		'id'            => "popular-course-sidebar",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );

	register_sidebar( array(
		'name'          => 'Footer Header Column 1',
		'id'            => "footer-first-column",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );
	register_sidebar( array(
		'name'          => 'Footer Header Column 2',
		'id'            => "footer-second-column",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );
	register_sidebar( array(
		'name'          => 'Footer Header Column 3',
		'id'            => "footer-third-column",
		'description'   => '',
		'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => "</li>\n",
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
		'before_sidebar' => '', // WP 5.6
		'after_sidebar'  => '', // WP 5.6
	) );

	register_widget( 'Svg_Widget' );
	register_widget( 'YandexMap_Widget' );
	register_widget( 'PopularCourse_Widget' );
}

add_action( 'widgets_init', 'register_my_widgets' );

function register_my_menus() {
	register_nav_menu( 'primary', __( 'Primary Menu' ) );

	register_nav_menu( 'footer-col-1', __( 'Footer First Column' ) );
	register_nav_menu( 'footer-col-2', __( 'Footer Second Column' ) );
	register_nav_menu( 'footer-col-3', __( 'Footer Third Column' ) );
}

add_action( 'after_setup_theme', 'register_my_menus' );

function register_courses() {
	register_post_type('course', [
		'label' => __('Course', 'txtdomain'),
		'public' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-text-page',
		'supports' => ['title', 'editor', 'thumbnail', 'author', 'revisions', 'custom-fields'],
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'labels' => [
			'singular_name' => __('Course', 'txtdomain'),
			'add_new_item' => __('Add new Course', 'txtdomain'),
			'new_item' => __('New Course', 'txtdomain'),
			'view_item' => __('View Course', 'txtdomain'),
			'not_found' => __('No Course found', 'txtdomain'),
			'not_found_in_trash' => __('No Course found in trash', 'txtdomain'),
			'all_items' => __('All Courses', 'txtdomain'),
			'insert_into_item' => __('Insert into Course', 'txtdomain')
		],
	]);
	flush_rewrite_rules();

	register_taxonomy('course-difficulty', ['course'], [
		'hierarchical'  => false,
		'labels'        => array(
			'name'              => _x( 'Difficulty', 'taxonomy general name' ),
			'singular_name'     => _x( 'Difficulty', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Difficulties' ),
			'all_items'         => __( 'All Difficulties' ),
			'edit_item'         => __( 'Edit Difficulty' ),
			'update_item'       => __( 'Update Difficulty' ),
			'add_new_item'      => __( 'Add New Difficulty' ),
			'new_item_name'     => __( 'New Difficulty' ),
			'menu_name'         => __( 'Difficulty' ),
		),
		'query_var'     => true,
		'public'		=> true,
		'show_admin_column'	=> true,
	]);
	register_taxonomy_for_object_type('course-difficulty', 'course');

	register_taxonomy('course-features', ['course'], [
		'hierarchical'  => false,
		'labels'        => array(
			'name'              => _x( 'Features', 'taxonomy general name' ),
			'singular_name'     => _x( 'Feature', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Features' ),
			'all_items'         => __( 'All Features' ),
			'edit_item'         => __( 'Edit Feature' ),
			'update_item'       => __( 'Update Feature' ),
			'add_new_item'      => __( 'Add New Feature' ),
			'new_item_name'     => __( 'New Feature' ),
			'menu_name'         => __( 'Features' ),
		),
		'query_var'     => true,
		'public'		=> true,
		'show_in_rest' => true,
	]);
	register_taxonomy_for_object_type('course-features', 'course');

	register_taxonomy('course-category', ['course'], [
		'hierarchical'  => false,
		'labels'        => array(
			'name'              => _x( 'Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Categories' ),
			'all_items'         => __( 'All Categories' ),
			'edit_item'         => __( 'Edit Categories' ),
			'update_item'       => __( 'Update Category' ),
			'add_new_item'      => __( 'Add New Category' ),
			'new_item_name'     => __( 'New Category' ),
			'menu_name'         => __( 'Categories' ),
		),
		'query_var'     => true,
		'public'		=> true,
		'show_in_rest' => true,
	]);
	register_taxonomy_for_object_type('course-category', 'course');

	register_taxonomy('course-content', ['course'], [
		'hierarchical'  => false,
		'description'	=> 'Формат: <Название главы>@l<SlugЛекция1>|l<SlugЛекция2>|l...@',
		'labels'        => array(
			'name'              => _x( 'Content', 'taxonomy general name' ),
			'singular_name'     => _x( 'Content', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Content' ),
			'all_items'         => __( 'All Content' ),
			'edit_item'         => __( 'Edit Content' ),
			'update_item'       => __( 'Update Content' ),
			'add_new_item'      => __( 'Add New Content' ),
			'new_item_name'     => __( 'New Content' ),
			'menu_name'         => __( 'Content' ),
		),
		'query_var'     => true,
		'public'		=> true,
		'show_in_rest' => true,
	]);
	register_taxonomy_for_object_type('course-content', 'course');
}

function register_manuals() {
	register_post_type('manual', [
		'label' => __('Manual', 'txtdomain'),
		'public' => true,
		'menu_position' => 6,
		'menu_icon' => 'dashicons-book',
		'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'labels' => [
			'singular_name' => __('Manual', 'txtdomain'),
			'add_new_item' => __('Add new Manual', 'txtdomain'),
			'new_item' => __('New Manual', 'txtdomain'),
			'view_item' => __('View Manual', 'txtdomain'),
			'not_found' => __('No Manual found', 'txtdomain'),
			'not_found_in_trash' => __('No Manual found in trash', 'txtdomain'),
			'all_items' => __('All Manuals', 'txtdomain'),
			'insert_into_item' => __('Insert into Manual', 'txtdomain')
		],
	]);
	flush_rewrite_rules();
}

function register_news() {
	register_post_type('news', [
		'label' => __('News', 'txtdomain'),
		'public' => true,
		'menu_position' => 7,
		'menu_icon' => 'dashicons-testimonial',
		'supports' => ['title', 'editor', 'thumbnail', 'author', 'revisions', 'comments'],
		'show_in_rest' => true,
		'labels' => [
			'singular_name' => __('News', 'txtdomain'),
			'add_new_item' => __('Add new News', 'txtdomain'),
			'new_item' => __('New News', 'txtdomain'),
			'view_item' => __('View News', 'txtdomain'),
			'not_found' => __('No News found', 'txtdomain'),
			'not_found_in_trash' => __('No News found in trash', 'txtdomain'),
			'all_items' => __('All News', 'txtdomain'),
			'insert_into_item' => __('Insert into News', 'txtdomain')
		],
	]);
	flush_rewrite_rules();

	register_taxonomy('news-category', ['news'], [
		'hierarchical'  => false,
		'labels'        => array(
			'name'              => _x( 'Category', 'taxonomy general name' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Categories' ),
			'all_items'         => __( 'All Categories' ),
			'edit_item'         => __( 'Edit Category' ),
			'update_item'       => __( 'Update Category' ),
			'add_new_item'      => __( 'Add New Category' ),
			'new_item_name'     => __( 'New Category' ),
			'menu_name'         => __( 'Category' ),
		),
		'query_var'     => true,
		'public'		=> true,
		'show_in_rest' => true,
	]);
	register_taxonomy_for_object_type('news-category', 'news');
}

function register_lections() {
	register_post_type('lections', [
		'label' => __('Lections', 'txtdomain'),
		'public' => true,
		'menu_position' => 8,
		'menu_icon' => 'dashicons-text',
		'supports' => ['title', 'editor', 'revisions'],
		'show_in_rest' => true,
		'labels' => [
			'singular_name' => __('Lection', 'txtdomain'),
			'add_new_item' => __('Add new Lection', 'txtdomain'),
			'new_item' => __('New Lections', 'txtdomain'),
			'view_item' => __('View Lections', 'txtdomain'),
			'not_found' => __('No Lections found', 'txtdomain'),
			'not_found_in_trash' => __('No Lections found in trash', 'txtdomain'),
			'all_items' => __('All Lections', 'txtdomain'),
			'insert_into_item' => __('Insert into Lections', 'txtdomain')
		],
	]);
	flush_rewrite_rules();
}

function register_tests() {
	register_post_type('tests', [
		'label' => __('Tests', 'txtdomain'),
		'public' => true,
		'menu_position' => 8,
		'menu_icon' => 'dashicons-text',
		'supports' => ['title', 'editor', 'revisions'],
		'show_in_rest' => true,
		'labels' => [
			'singular_name' => __('Test', 'txtdomain'),
			'add_new_item' => __('Add new Test', 'txtdomain'),
			'new_item' => __('New Tests', 'txtdomain'),
			'view_item' => __('View Tests', 'txtdomain'),
			'not_found' => __('No Tests found', 'txtdomain'),
			'not_found_in_trash' => __('No Tests found in trash', 'txtdomain'),
			'all_items' => __('All Tests', 'txtdomain'),
			'insert_into_item' => __('Insert into Tests', 'txtdomain')
		],
	]);
	flush_rewrite_rules();
}

function register_olympiads() {
	register_post_type('olympiad', [
		'label' => __('Olympiads', 'txtdomain'),
		'public' => true,
		'menu_position' => 8,
		'menu_icon' => 'dashicons-text',
		'supports' => ['title', 'editor', 'revisions', 'custom-fields'],
		'show_in_rest' => true,
		'labels' => [
			'singular_name' => __('Olympiad', 'txtdomain'),
			'add_new_item' => __('Add new Olympiad', 'txtdomain'),
			'new_item' => __('New Olympiads', 'txtdomain'),
			'view_item' => __('View Olympiads', 'txtdomain'),
			'not_found' => __('No Olympiads found', 'txtdomain'),
			'not_found_in_trash' => __('No Olympiads found in trash', 'txtdomain'),
			'all_items' => __('All Olympiads', 'txtdomain'),
			'insert_into_item' => __('Insert into Olympiads', 'txtdomain')
		],
	]);
	flush_rewrite_rules();
}

add_action( 'init', 'register_courses' );
add_action( 'init', 'register_manuals' );
add_action( 'init', 'register_news' );
add_action( 'init', 'register_lections' );
add_action( 'init', 'register_tests' );
add_action( 'init', 'register_olympiads' );

function echo_message($message) {
	echo '<div class="message">' . $message . '</div>';
}

function admin_post_register_user() {
	$username = $_POST['username'];
	$email = $_POST['e-mail'];
	$first_name = $_POST['first name'];
	$last_name = $_POST['last name'];
	$password = $_POST['password'];

	if ($_POST['role'] == 'teacher') {
		$role = 'teacher-temp';
	}
	else if ( $_POST['role'] == 'student' ) {
		$role = 'student';
	}

	$error = false;
	$error_messages = array();

	if ( ! $username ) {
		array_push($error_messages, 'Нет логина');
		$error = true;
	}
	else if ( username_exists($username) ) {
		array_push($error_messages, 'Логин занят');
		$error = true;
	}

	if ( ! is_email( $email ) ) {
		array_push($error_messages, 'Почта введена в неверном формате');
		$error = true;
	}
	else if ( email_exists($email) ) {
		array_push($error_messages, 'Почта занята');
		$error = true;
	}

	if ( ! $password ) {
		array_push($error_messages, 'Нет пароля');
		$error = true;
	}

	if ( ! $role ) {
		array_push($error_messages, 'Не выбран студент/преподаватель');
		$error = true;
	}

	if ( $error ) {
		foreach ( $error_messages as $error_message ) {
			echo_message( $error_message );
		}
	}
	else {
		$userdata = array(
			'user_pass' => $password,
			'user_login' => $username,
			'user_email' => $email,
			'role' => $role
		);

		if ( $first_name ) {
			array_push($userdata, 'first_name', $first_name);
		}

		if ( $last_name ) {
			array_push($userdata, 'last_name', $last_name);
		}

		if ( is_wp_error( wp_insert_user( $userdata ) ) ) {
			echo_message( 'Произошла ошибка при регистрации пользователя' );
		}
		else {
			echo_message( 'Пользователь успешно зарегистрирован' );
		}
	}
	die();
}

add_action( 'admin_post_register-user', 'admin_post_register_user' );
add_action( 'admin_post_nopriv_register-user', 'admin_post_register_user' );

function login_fail( $username ) {
	$referrer = $_SERVER['HTTP_REFERER'];
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
		if ( str_contains( $referrer, '&failed=1' ) ) {
			wp_redirect( $referrer );
		}
		else {
			wp_redirect( $referrer . '&failed=1' );
		}
		exit;
	}
}

add_action( 'wp_login_failed', 'login_fail' );

function get_feature_id( $feature_string ) {
	$feature_term = get_term_by( 'name', $feature_string, 'course-features' );
	if ( $feature_term ) {
		return $feature_term->ID;
	}
	else {
		return wp_insert_term( $feature_string, 'course-features' )['term_id'];
	}
}

function admin_post_add_course() {
	if ( wp_get_current_user()->roles[0] != 'teacher' ) {
		echo 'Только преподаватели могут добавлять курсы';
		die();
	}

	$title = $_POST['title'];
	$content = $_POST['description'];
	$features = explode( ',', $_POST['features'] );
	$hours = $_POST['hours'];
	$lections = $_POST['lections'];
	$peoples = $_POST['people-count'];
	$price = $_POST['price'];
	$difficulty_id = $_POST['difficulty'];
	$category_id = $_POST['category'];

	$error = false;
	$error_messages = array();

	if ( ! $title ) {
		array_push($error_messages, 'Нет заголовка');
		$error = true;
	}

	if ( ! $content ) {
		array_push($error_messages, 'Нет описания курса');
		$error = true;
	}

	if ( ! $features ) {
		array_push($error_messages, 'Нет особенностей курса');
		$error = true;
	}

	if ( ! $hours ) {
		array_push($error_messages, 'Нет часов');
		$error = true;
	}

	if ( ! $lections ) {
		array_push($error_messages, 'Нет лекций');
		$error = true;
	}

	if ( ! $peoples ) {
		array_push($error_messages, 'Нет количества людей');
		$error = true;
	}

	if ( ! $price ) {
		array_push($error_messages, 'Нет цены');
		$error = true;
	}

	if ( $error ) {
		foreach ( $error_messages as $error_message ) {
			echo_message( $error_message );
		}
	}
	else {
		$post_args = array(
			'post_title'	=> sanitize_text_field( $title ),
			'post_content'	=> $content,
			'post_status'	=> 'publish',
			'post_type'		=> 'course',
		);

		$post_id = wp_insert_post( $post_args );

		if ( is_wp_error( $post_id ) ) {
			echo_message( 'Произошла ошибка при добавлении курса' );
			die();
		}

		$difficulty_term = get_term_by( 'id', $difficulty_id, 'course-difficulty');
		wp_set_object_terms($post_id, $difficulty_term->name, 'course-difficulty');

		$category_term = get_term_by( 'id', $category_id, 'course-category');
		wp_set_object_terms($post_id, $category_term->name, 'course-category');

		update_post_meta( $post_id, 'hours', $hours );
		update_post_meta( $post_id, 'lections', $lections );
		update_post_meta( $post_id, 'people-count', $peoples );
		update_post_meta( $post_id, 'price', $price );

		wp_set_object_terms($post_id, $features, 'course-features');

		echo_message( 'Курс успешно добавлен!' );
	}

	die();
}

add_action( 'admin_post_add-post', 'admin_post_add_course' );

function admin_post_delete_current_user() {
	$user = wp_get_current_user();
	if ( $user->ID != 1 ) {
		wp_delete_user($user->ID);
		echo 'Аккаунт ' . $user->data->user_login . ' удалён';
	}
}

add_action( 'admin_post_delete-current-user', 'admin_post_delete_current_user' );

function admin_post_insert_score() {
	if (! is_user_logged_in())
	{
		echo "Для добавления результата прохождения тестов необходимо авторизоваться";
		die();
	}

	$score = $_POST['score'];
	$testInfo = $_POST['testInfo'];
	$percent = $_POST['percent'];
	$refs = $_POST['refs'];
	$user = wp_get_current_user();

	$scoreDb = new Scores();
	$scoreDb->InsertScore($user->ID, $score, $testInfo, $percent, $refs);
	echo "Результат успешно добавлен в личностную траекторию развития";
	die();
}

add_action( 'admin_post_insert-score', 'admin_post_insert_score' );

function admin_post_insert_olympiad() {
	if (! is_user_logged_in())
	{
		echo "Для записи на олимпиады необходимо аутентифицироваться";
		die();
	}

	$pageId = $_POST['pageId'];
	$olympiadTitle = $_POST['olympiadTitle'];
	$user = wp_get_current_user();

	$olympiadsDb = new Olympiads();
	$olympiadsDb->InsertEntry($user->ID, $user->display_name, $pageId, $olympiadTitle);
	echo "Вы успешно зарегистрировались на олимпиаду";
	die();
}

add_action( 'admin_post_insert-olympiad', 'admin_post_insert_olympiad' );

function register_persons() {
	register_post_type('persons', [
		'label' => __('Известные личности', 'txtdomain'),
		'public' => true,
		'menu_position' => 8,
		'menu_icon' => 'dashicons-text',
		'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
		'show_in_rest' => true,
		'labels' => [
			'singular_name' => __('Известная личность', 'txtdomain'),
			'add_new_item' => __('Добавить новую личность', 'txtdomain'),
			'new_item' => __('Новая личность', 'txtdomain'),
			'view_item' => __('Посмотреть личностей', 'txtdomain'),
			'not_found' => __('Личность не найдена', 'txtdomain'),
			'not_found_in_trash' => __('Личность не найдена в корзине', 'txtdomain'),
			'all_items' => __('Все личности', 'txtdomain'),
			'insert_into_item' => __('Вставить в личность', 'txtdomain')
		],
	]);
	flush_rewrite_rules();
}

function register_conferences() {
	register_post_type('conferences', [
		'label' => __('Конференции', 'txtdomain'),
		'public' => true,
		'menu_position' => 8,
		'menu_icon' => 'dashicons-text',
		'supports' => ['title', 'editor', 'revisions', 'thumbnail',  'custom-fields'],
		'show_in_rest' => true,
		'labels' => [
			'singular_name' => __('Конференция', 'txtdomain'),
			'add_new_item' => __('Добавить новую конференцию', 'txtdomain'),
			'new_item' => __('Новая конференция', 'txtdomain'),
			'view_item' => __('Посмотреть конференции', 'txtdomain'),
			'not_found' => __('Конференция не найдена', 'txtdomain'),
			'not_found_in_trash' => __('Конференция не найдена в корзине', 'txtdomain'),
			'all_items' => __('Все конференции', 'txtdomain'),
			'insert_into_item' => __('Вставить в конференцию', 'txtdomain')
		],
	]);
	flush_rewrite_rules();
}

add_action( 'init', 'register_persons' );
add_action( 'init', 'register_conferences' );

?>
