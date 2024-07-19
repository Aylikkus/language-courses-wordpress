<?php

get_header();

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/single-news.css">

<?php
while ( have_posts() ) {
    the_post();
?>
<div class="news-main">
    <div class="news-info">
        <h1><?php the_title() ?></h1>
    </div>
    <div class="author-info">
        <?php $author_id = get_post_field( 'post_author' ); ?>
        <?php echo get_avatar( get_the_author_meta( $author_id ) ); ?>
        <h4><?php echo get_the_author_meta( 'display_name', $author_id ); ?></h4>
        <div class="vertical-line"></div>
        <h4><?php echo get_the_date(); ?></h4>
        <div class="vertical-line"></div>
        <h4><?php echo get_read_time($post); ?></h4>
    </div>
    <div class="content">
        <?php
        if ( has_post_thumbnail() )
            echo get_the_post_thumbnail();

        the_content();
        ?>
    </div>
</div>

<?php
}

get_footer();

?>
