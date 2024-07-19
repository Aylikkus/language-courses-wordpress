<?php

get_header();

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/single-olympiad.css">

<?php
while ( have_posts() ) {
    the_post();
?>
<div class="olympiad-main">
    <div class="olympiad-info">
        <h1><?php the_title() ?></h1>
        <h2>
        <?php
            $expected = get_post_field( 'date' );
            if ( $expected ) {
                echo $expected;
            }
            else {
                echo 'Дата проведения неизвестна';
            }
        ?>
        </h2>
    </div>
    <div class="content">
        <?php
        if ( has_post_thumbnail() )
            echo get_the_post_thumbnail();

        the_content();
        ?>
    </div>
<?php
$user = wp_get_current_user();

if (! empty($user)) {
?>
    <script>
        sendParticipateRequest = function()
        {
            var form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', '<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>');
            form.style.display = 'hidden';

            var action = document.createElement("input");
            action.setAttribute('type', 'hidden');
            action.setAttribute('name', 'action');
            action.setAttribute('value', 'insert-olympiad');
            form.appendChild(action);

            var pageId = document.createElement("input");
            pageId.setAttribute('type', 'text');
            pageId.setAttribute('name', 'pageId');
            pageId.setAttribute('value', '<?php echo get_the_ID(); ?>');
            form.appendChild(pageId);

            var olympiadTitle = document.createElement("input");
            olympiadTitle.setAttribute('type', 'text');
            olympiadTitle.setAttribute('name', 'olympiadTitle');
            olympiadTitle.setAttribute('value', '<?php echo get_the_title(); ?>');
            form.appendChild(olympiadTitle);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
    <div class="register-btn">
        <button onClick="sendParticipateRequest()">Принять участие</button>
    </div>
<?php 
} 

$olympiadsDb = new Olympiads();
$registered = $olympiadsDb->GetAllEntries(get_the_ID(), get_the_title());

if (! empty($registered)) {
?>
    <div class="participants">
        <h1>Участники</h1>
        <?php 
        $first = true;
        foreach ($registered as $entry) {
            if (! $first)
                echo ', ';
            echo $entry->user_name;
            $first = false;
        }
        
        ?>
    </div> 
<?php
}
?>
</div>

<?php
}

get_footer();

?>
