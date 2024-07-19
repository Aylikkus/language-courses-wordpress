<?php
/*
 * Template Name: Login Page
*/
?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/login.css">

<?php

parse_str($_SERVER['QUERY_STRING'], $query);
$is_register = $query['register'];
$is_logout = $query['logout'];
$is_failed = $query['failed'];

if ($is_logout) {
    wp_logout();
    header('Location:' . get_permalink());
    exit();
}

if ($is_register) {
    ?>
    <form action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="post">
        <input type="hidden" name="action" value="register-user" />
        <p><label for="username">Логин</label><input placeholder="exampleLogin" type="text" name="username"/></p>
        <p><label for="e-mail">Электронная почта</label><input placeholder="example@mail.ex" type="text" name="e-mail"/></p>
        <p><label for="first name">Имя</label><input placeholder="Иван" type="text" name="first name"/></p>
        <p><label for="last name">Фамилия</label><input placeholder="Иванов" type="text" name="last name"/></p>
        <p><label for="password">Пароль</label><input placeholder="********" type="password" name="password"/></p>
        <div class="role-radio-buttons">
            <p class="form-radio">
                <label for="student">Студент</label>
                <input id="student" type="radio" name="role" value="student">
            </p>
            <p class="form-radio">
                <label for="student">Преподаватель</label>
                <input id="teacher" type="radio" name="role" value="teacher">
            </p>
        </div>
        <p class="registration-submit"><input class="button" type="submit" name="submit" value="Зарегистрироваться"/></p>
    </form>
    <?php
}
else {
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        echo '<p>Вы залогинены под ' . get_the_author_meta( 'display_name', $current_user->ID ) . '</p>';
        echo '<a href="' . get_permalink() . '&logout=1' . '">Выйти</a>';
        echo '<a href="' . get_home_url() . '">Главная</a>';
        echo '<a href="' . get_profile_page_for_logged_in_user() . '">Профиль</a>';
    }
    else {
        if ( $is_failed ) {
            echo '<div class="failed-login">Неверный логин/почта/пароль</div>';
        }
        wp_login_form();
        echo '<div class="no-profile-msg">Нет профиля? <a href="' . $_SERVER['REQUEST_URI'] . '&register=1">Зарегистрироваться</a></div>';
    }
}

?>
