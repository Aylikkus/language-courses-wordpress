<?php
/*
 * Template Name: Manual Search
*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-manual-search.css">
<?php

get_header();

$query = $_GET['query'];

?>
<div class="manuals-search">
    <div class="search-wrapper">
        <h1>ПОИСК СПРАВОЧНИКОВ</h1>
        <form id="search-form" class="header-search-input">
            <input type="text" name="query" placeholder="Поиск...">
            <button>Начать поиск</button>
        </form>
    </div>
</div>
<div class="linewrapper"><hr></div>
<div class="search-results">
    <div class="search-result">
        <div class="manuals-grid">
<?php

$args = array(
    'post_type'   => 'Manual',
    's'           => $query,
    'nopaging'    => true,
);
$loop = new WP_Query($args);
while ( $loop->have_posts() ) {
    $loop->the_post();
?>
            <div class="manual-box">
                <?php
                    if ( has_post_thumbnail() )
                        echo get_the_post_thumbnail();
                    else
                        echo '<img src="' . get_template_directory_uri() . '/default_image.png">';
                ?>
                <div class="manual-info">
                    <div class="manual-header">
                        <h2><?php the_title(); ?></h2>
                    </div>
                    <div class="manual-attributes">
                        <div class="manual-chapters">
                            <img src="<?php echo get_template_directory_uri()?>/svg/workcount.svg">
                            <?php
                            $dom = new DOMDocument();
                            $content = get_the_content();
                            if ($content) $dom->loadHTML($content);
                            $elems = $dom->getElementsByTagName('h3');
                            if ($elems) {
                                $count = $elems->length;
                            }
                            else {
                                $count = 0;
                            }
                            echo $count;
                            ?>
                            <?php
                            if ($count % 10 > 1 && $count % 10 < 5) {
                                echo 'раздела';
                            }
                            else if ($count % 10 == 0 || $count % 10 > 4) {
                                echo 'разделов';
                            }
                            else {
                                echo 'раздел';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="manual-open">
                    <a href="<?php the_permalink(); ?>">Открыть</a>
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
</div>

<script src="<?php echo get_template_directory_uri() ?>/page-js/page-manual-search.js"></script>

<?php

get_footer();

?>
