<?php
/*
 * Template Name: Course Search
*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-course-search.css">
<?php

get_header();
get_star_svg_defs();

$compares = array(
    "equal" => '=',
    "more" => '>',
    "less" => '<',
);

$level = $_GET['level'];
$query = $_GET['query'];

$hours = $_GET['hours_count'];
$hours_compare = $compares[$_GET['hours_compare']];

$people = $_GET['people_count'];
$people_compare = $compares[$_GET['people_compare']];

$lections = $_GET['lections_count'];
$lections_compare = $compares[$_GET['lections_compare']];

$rating = $_GET['rating'];
$rating_compare = $compares[$_GET['rating_compare']];

$price = $_GET['price'];
$price_compare = $compares[$_GET['price_compare']];

$category = $_GET['category'];

?>

<div class="course-search">
    <div class="search-wrapper">
        <h1>ОГРОМНЫЙ ВЫБОР КУРСОВ ИНОСТРАННЫХ ЯЗЫКОВ ПО БРЯНСКОЙ ОБЛАСТИ</h1>
        <form id="search-form" class="header-search-input">
            <input type="text" name="query" placeholder="Поиск...">
            <button>Начать поиск</button>
        </form>
    </div>
</div>
<div class="linewrapper"><hr></div>

<div class="wrapper">

    <div class="search-results">
        <div class="search-result">
            <div class="courses-grid">
    <?php

    $args = array(
        'post_type'   => 'Course',
        's'           => $query,
        'nopaging'    => true,
        'meta_query'  => array(
            'relation' => 'and',
        ),
        'tax_query'   => array(
            'relation' => 'and',
        ),
    );
    if ($level) {
        $args['tax_query'][] = array(
            'taxonomy' => 'course-difficulty',
            'field' => 'slug',
            'terms' => $level,
            'operator' => 'IN',
        );
    }
    if ($category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'course-category',
            'field' => 'term_id',
            'terms' => $category,
            'operator' => 'IN',
        );
    }
    if ($hours && $hours_compare) {
        $args['meta_query'][] = array (
            'key' => 'hours',
            'value' => $hours,
            'compare' => $hours_compare,
        );
    }
    if ($people && $people_compare) {
        $args['meta_query'][] = array (
            'key' => 'people-count',
            'value' => $people,
            'compare' => $people_compare,
        );
    }
    if ($lections && $lections_compare) {
        $args['meta_query'][] = array (
            'key' => 'lections',
            'value' => $lections,
            'compare' => $lections_compare,
        );
    }
    if ($rating && $rating_compare) {
        $args['meta_query'][] = array (
            'key' => 'rating',
            'value' => $rating,
            'compare' => $rating_compare,
        );
    }
    if ($price && $price_compare) {
        $args['meta_query'][] = array (
            'key' => 'price',
            'value' => $price,
            'compare' => $price_compare,
        );
    }
    $loop = new WP_Query($args);
    while ( $loop->have_posts() ) {
        $loop->the_post();
    ?>
                <div class="course-box">
                    <?php
                        $terms = get_the_terms( $post->ID, 'course-difficulty' );
                        if ( ! is_wp_error( $terms ) and $terms[0] ) {
                        ?>
                            <div class="course-difficulty">
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
                                <h4><?php echo get_the_author_meta( 'display_name', $author_id ); ?></h4>
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
    <?php
    }

    if ($loop->found_posts == 0) {
        echo '<div></div><div class="message-box">Ничего не нашлось</div>';
    }
    ?>
            </div>
        </div>
        <div class="filter-sidebar">
            <div class="filter-sidebar-input">

            </div>
        </div>
    </div>

    <div class="filters-wrapper">
        <div class="filters">
            <h3>Уровень сложности</h3>
            <form id="difficulty-radio" class="level-radio-buttons">
                <div class="form-radio">
                    <input id="beginner" type="radio" name="level" value="начинающий">
                    <label for="beginner">Начинающий</label>
                </div>
                <div class="form-radio">
                    <input id="middle" type="radio" name="level" value="средний">
                    <label for="middle">Средний</label>
                </div>
                <div class="form-radio">
                    <input id="advanced" type="radio" name="level" value="продвинутый">
                    <label for="advanced">Продвинутый</label>
                </div>
                <div class="form-radio">
                    <input id="any" type="radio" name="level" value="">
                    <label for="any">Любой</label>
                </div>
            </form>
            <h3>Параметры курса</h3>
            <form id="hours" class="numbers-form">
                <label for="hours-count">Часов</label>
                <input type="number" min="0" name="hours-count" placeholder="0">
                <select name="hours-compare" class="select">
                    <option value="equal">Равно</option>
                    <option value="less">Меньше</option>
                    <option value="more">Больше</option>
                </select>
            </form>
            <form id="people" class="numbers-form">
                <label for="people-count">Человек</label>
                <input type="number" min="0" name="people-count" placeholder="0">
                <select name="people-compare" class="select">
                    <option value="equal">Равно</option>
                    <option value="less">Меньше</option>
                    <option value="more">Больше</option>
                </select>
            </form>
            <form id="lections" class="numbers-form">
                <label for="lections-count">Лекций</label>
                <input type="number" min="0" name="lections-count" placeholder="0">
                <select name="lections-compare" class="select">
                    <option value="equal">Равно</option>
                    <option value="less">Меньше</option>
                    <option value="more">Больше</option>
                </select>
            </form>
            <form id="rating" class="numbers-form">
                <label for="rating">Рейтинг</label>
                <input type="number" min="0" max="5" step="0.01" name="rating" placeholder="0.00">
                <select name="rating-compare" class="select">
                    <option value="equal">Равно</option>
                    <option value="less">Меньше</option>
                    <option value="more">Больше</option>
                </select>
            </form>
            <form id="price" class="numbers-form">
                <label for="price">Цена</label>
                <input type="number" min="0" name="price" step="0.01" placeholder="0.00">
                <select name="price-compare" class="select">
                    <option value="equal">Равно</option>
                    <option value="less">Меньше</option>
                    <option value="more">Больше</option>
                </select>
            </form>
            <h3>Категории</h3>
            <form id="categories" class="numbers-form">
                <select name="category" class="select">
                    <option value="">Не выбрано</option>
                    <?php
                        $category_terms = get_terms( array(
                            'taxonomy'  =>  'course-category',
                            'hide_empty' => false
                        ) );
                        foreach ( $category_terms as $cat ) {
                            echo '<option value="' . $cat->term_id . '">' . $cat->name . '</option>';
                        }
                    ?>
                </select>
            </form>
            <form id="additional-search-form" class="search-input">
                <input type="text" name="query" placeholder="Поиск...">
                <button>Начать поиск</button>
            </form>
        </div>
    </div>

</div>

<script src="<?php echo get_template_directory_uri() ?>/page-js/page-course-search.js"></script>

<?php

get_footer();

?>
