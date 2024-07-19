<?php
/*
 * Template Name: Education Page
*/
?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/education-page.css">
<script src="<?php echo get_template_directory_uri() ?>/libs/quiz.js"></script>
<script> page_info = ""; </script>

<?php

function display_not_found() {
    echo '<div class="not-found">Такой страницы не существует</div>';
}

function get_content_post($slug_with_type) {
    $type = substr($slug_with_type, 0, 1);
    $slug = substr($slug_with_type, 1);

    if ($type == 'l') {
        $args = array(
            'name'        => $slug,
            'post_type'   => 'lections',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $lections = get_posts($args);
        return $lections[0];
    }
    elseif ($type == 't') {
        $args = array(
            'name'        => $slug,
            'post_type'   => 'tests',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $tests = get_posts($args);
        return $tests[0];
    }

    return 0;
}

function get_content_icon($slug_with_type) {
    $type = substr($slug_with_type, 0, 1);
    $base = get_template_directory_uri();

    if ($type == 'l') {
        return $base . '/svg/lection.svg';
    }
    elseif ($type == 't') {
        return $base . '/svg/quiz.svg';
    }
}

function get_full_type($slug_with_type) {
    $type = substr($slug_with_type, 0, 1);

    if ($type == 'l') {
        return 'lection';
    }
    elseif ($type == 't') {
        return 'test';
    }
}

function format_test($test_content) {
    $dom = new domDocument();
    $dom->loadHTML(mb_convert_encoding($test_content, 'HTML-ENTITIES', "UTF-8"));
    $selector = new DOMXPath($dom);
    $result = $selector->query('body/pre');

    if (! is_user_logged_in()) {
        ?>
        <p class="login-text">Вы не авторизованы, для сохранения 
        прогресса прохождения тестов 
        <a href="<?php echo get_custom_login_link(); ?>">авторизуйтесь</a></p>
        <?php
    }

    echo "<div class=\"quiz-content\">";

    ?>
    <script>quiz = new Quiz()</script>
    <?php

    $questionIndex = 0;
    foreach ($result as $question) {

        $title = "";
        $questions = array();
        $explanation = "";

        ?>
        <script>question = new Question()</script>
        <?php

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $question->textContent) as $line) {
            $line = trim($line);

            if (empty($line) || strlen($line) < 3) continue;

            $first = $line[0];
            $second = $line[1];
            $third = $line[2];

            if ( $first == "[" ) {
                $answer_cnt = trim(substr($line, 3));

                if (empty($answer_cnt)) continue;
                $questions[] = $answer_cnt;

                if ($second == "*") {
                    ?>
                    <script>question.AddAnswer(new Answer("<?php echo $answer_cnt; ?>", 0))</script>
                    <?php
                }
                else {
                    ?>
                    <script>question.AddAnswer(new Answer("<?php echo $answer_cnt; ?>", 1))</script>
                    <?php
                }
            }
            elseif ( $first == "/" ) {
                $expl_cnt = trim(substr($line, 2));

                if (empty($expl_cnt)) continue;

                $explanation = $expl_cnt;
                ?>
                <script>question.explanation = "<?php echo $expl_cnt; ?>"</script>
                <?php
            }
            elseif ($first == "{") {
                $ref_cnt = trim(substr($line, 2));

                if (empty($ref_cnt)) continue;

                $reference = $ref_cnt;

                ?>
                <script>question.reference = "<?php echo $reference; ?>"</script>
                <?php
            }
            else {
                $title_cnt = $line;
                $title = $title_cnt;
                ?>
                <script>question.title = "<?php echo $title_cnt; ?>"</script>
                <?php
            }
        }

        ?>
        <script>quiz.AddQuestion(question)</script>
        <?php

        echo "<div class=\"question\">";
        
        echo "<h3 class=\"question-title\">" . $title . "</h3>";

        echo "<div class=\"question-buttons\">";
        $answerIndex = 0;
        foreach ($questions as $question) {
            echo "<button onClick=\"Click(" . $questionIndex . "," . $answerIndex . ")\">" . $question . "</button><br>";
            $answerIndex++;
        }
        echo "</div>";

        echo "<div class=\"question-explanation\">" . $explanation . "</div>";

        echo "<div class=\"question-nav\">";

        if ($questionIndex > 0) {
            echo "<button class=\"question-prev\" onClick=\"SwitchToQuestion(" . $questionIndex - 1 . ")\">Предыдущий</button>";
        }

        if ($questionIndex < $result->length - 1) {
            echo "<button class=\"question-next\" onClick=\"SwitchToQuestion(" . $questionIndex + 1 . ")\">Следующий</button>";
        }

        echo "</div>";

        echo "</div>";

        $questionIndex++;
    }

    echo "</div>";

    if (is_user_logged_in())
        echo "<button id=\"end_test_btn\" onClick=\"SendTestResults()\">Завершить тест</button>";
}

$is_logged_in = is_user_logged_in();

while ( have_posts() ) {
    the_post();
    $cur_page_id = get_the_id();
}

$course_slug = $_GET['course_slug'];
$content_slug = $_GET['content'];

if ($course_slug && $content_slug) {
    $args = array(
        'post_type'   => 'Course',
        'name'        => $course_slug,
        'posts_per_page' => 1,
    );
    $loop = new WP_Query($args);
    while ( $loop->have_posts() ) {
        $loop->the_post();
        $free_chapters = get_post_field( 'free_chapters' );

?>
<head>
    <title>Обучение - <?php echo get_the_title(); ?></title>
    <script> page_info = page_info.concat('<?php echo get_the_title(); ?>'); </script>
</head>

<header>
    <div class="logo">
        <?php
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
            echo '<a style="color: #185c8f; text-decoration: none" href="' . get_home_url() . '">';
            if ( has_custom_logo() ) {
                echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '">';
            } else {
                echo '<h2 style="white-space: nowrap; margin: 0;">' . get_bloginfo('name') . '</h2>';
            }
            echo '</a>';
        ?>
    </div>
    <div class="progress">
        <div class="progress-info">
            <div id="completed-percent" class="progress-info-percent"></div>
            <div id="total-lections" class="progress-info-right">
            </div>
        </div>
        <div id="progress-line" class="progress-line">
        </div>
    </div>
</header>
<div class="sidebar">
    <div class="sidebar-header">
        <h2><?php the_title(); ?></h2>
        <a href="<?php the_permalink(); ?>">На страницу курса</a>
    </div>

    <?php
    $chaptersTerms = get_the_terms( $post->ID, 'course-content' );

    $chapterIndex = 0;
    $totalContents = 1;
    foreach ($chaptersTerms as $chapterTerm) {
        echo '<div class="chapter">';
        $separated = explode('@',$chapterTerm->name);

        $chapterName = $separated[0];
        $subchapters = explode('|', $separated[1]);

        ?>
        <div class="chapter-header">
            <h2><?php echo $chapterName ?></h2>
        </div>
        <?php

        $i = 1;
        foreach ($subchapters as $subchapter) {
            $type = substr($subchapter, 0, 1);
            $slug = substr($subchapter, 1);

            $content = get_content_post($subchapter);
            if ($content) {
                ?>
                <button class="chapter-unit <?php if ($content_slug == $slug) {
                    echo 'chapter-unit-selected';
                    $previous_content = get_content_post($subchapters[$i - 2]);
                    $current_content = $content;
                    $current_type = get_full_type($subchapter);
                    $next_chapter_index = $chapterIndex + 1;
                    $currentContentIndex = $totalContents;
                    $next_content = get_content_post($subchapters[$i]);
                }?>">
                    <img src="<?php echo get_content_icon($subchapter); ?>">
                    <p class="chapter-unit-number"><?php echo $i; ?></p>
                    <?php
                    if ($is_logged_in) {
                    ?>
                    <a href="<?php echo get_the_permalink($cur_page_id) .
                        '&course_slug=' . get_post_field( 'post_name', get_post() ) .
                        '&content=' . $content->post_name; ?>" class="chapter-unit-type"><?php echo $content->post_title; ?></a>
                    <?php
                    }
                    else {
                        if ($chapterIndex > $free_chapters - 1) {
                        ?>
                        <a class="not-active-link chapter-unit-type"><?php echo $content->post_title; ?></a>
                        <?php
                        } else {
                        ?>
                        <a href="<?php echo get_the_permalink($cur_page_id) .
                            '&course_slug=' . get_post_field( 'post_name', get_post() ) .
                            '&content=' . $content->post_name; ?>" class="chapter-unit-type"><?php echo $content->post_title; ?></a>
                        <?php
                        }
                    }
                    ?>
                </button>
                <?php

                if ($previous_content)
                    $previous_content_link = get_the_permalink($cur_page_id) .
                        '&course_slug=' . get_post_field( 'post_name', get_post() ) .
                        '&content=' . $previous_content->post_name;

                if ($next_content)
                    $next_content_link = get_the_permalink($cur_page_id) .
                        '&course_slug=' . get_post_field( 'post_name', get_post() ) .
                        '&content=' . $next_content->post_name;
            }
            $i++;
            $totalContents++;
        }
        echo '</div>';
        $chapterIndex++;
    }

    $totalContents--;
    $separated = explode('@',$chaptersTerms[$next_chapter_index]->name);
    $subchapters = explode('|', $separated[1]);
    if ($subchapters[0]) {
        $next_chapter_link = get_the_permalink($cur_page_id) .
            '&course_slug=' . get_post_field( 'post_name', get_post() ) .
            '&content=' . substr($subchapters[0], 1);
    }
    ?>
</div>
<div class="content">
    <div class="content-main">
    <?php
    if ($current_content) {
        echo '<h1>' . $current_content->post_title . '</h1>';
        if ($current_type == "lection") {
            echo $current_content->post_content;
        }
        elseif ($current_type == "test") {
            format_test($current_content->post_content);
        }

        ?> <script> page_info = page_info.concat('<?php echo ' - ' . $current_content->post_title; ?>'); </script> <?php
    }
    ?>
    </div>
    <div class="content-nav">
        <div class="back">
        <?php
        if ($previous_content_link) {
            ?>

            <div class="back-icon"><a href="<?php echo $previous_content_link; ?>"><img src="<?php echo get_template_directory_uri(); ?>/svg/back.svg"></a></div>
            <?php
        }
        ?>
        </div>

        <div class="complete">
            <?php
            if ($next_chapter_link && $is_logged_in) {
            ?>
            <a href="<?php echo $next_chapter_link; ?>">
                <button>
                    Следующая глава
                </button>
            </a>
            <?php
            }
            ?>
        </div>
        <div class="forward">
        <?php
        if ($next_content_link) {
            ?>
            <div class="forward-icon"><a href="<?php echo $next_content_link; ?>"><img src="<?php echo get_template_directory_uri(); ?>/svg/forward.svg"></a></div>
            <?php
        }
        ?>
        </div>
    </div>
</div>

<?php
    }

    if ($loop->found_posts == 0) {
        display_not_found();
    }

}
else {
    display_not_found();
}
?>

<?php

$percent_completed = ($currentContentIndex / $totalContents) * 100;
if ( $percent_completed >= 0 && $percent_completed <= 100 ) {
?>
<script>

document.getElementById("progress-line").innerHTML = `
<svg width="100%" height="100%" viewbox="0 0 100% 10" xmlns="http://www.w3.org/2000/svg">
    <linearGradient id="gradient">
        <stop offset="<?php echo $percent_completed; ?>%" stop-color="#185c8f"/>
        <stop offset="<?php echo $percent_completed; ?>%" stop-color="grey"/>
    </linearGradient>
    <rect x1="0" y1="0" width="100%" height="100%" rx="5" fill="url(#gradient)"/>
</svg>`;

<?php
if ($is_logged_in) {
?>
document.getElementById("completed-percent").innerHTML = "<?php echo number_format($percent_completed, 0); ?>% ЗАВЕРШЕНО";
<?php
}
else {
    ?>
document.getElementById("completed-percent").innerHTML = "ЗАРЕГИСТРИРУЙТЕСЬ ЧТОБЫ ПРОХОДИТЬ КУРС";
    <?php
}
?>

document.getElementById("total-lections").innerHTML = '<?php echo $currentContentIndex . "/" . $totalContents; ?> Лекций';

</script>

<?php
}
?>

<script src="<?php echo get_template_directory_uri() ?>/page-js/education-page.js"></script>

<script>

score_insert_post_link = '<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>';

</script>
