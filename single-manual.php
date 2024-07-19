<?php

get_header();

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/single-manual.css">

<?php
while ( have_posts() ) {
    the_post();
    $dom = new domDocument();
    $dom->loadHTML(mb_convert_encoding(get_the_content(), 'HTML-ENTITIES', "UTF-8"));
    $selector = new DOMXPath($dom);
    $result = $selector->query('body/*');

    $chapters = array();
    $curChapter = 0;
    $curSubchapter = 0;
    foreach($result as $node) {
        if ($node->localName == 'h2') {
            $chapters[$node->textContent] = array();
            $curChapter = $node->textContent;
        }
        else if ($node->localName == 'h3') {
            $chapters[$curChapter][$node->textContent] = array();
            $curSubchapter = $node->textContent;
        }
        else {
            if ($curChapter && $curSubchapter) {
                array_push($chapters[$curChapter][$curSubchapter], $dom->saveXML($node));
            }
        }
    }
?>

<h1><?php the_title() ?></h1>
<div class="manual-main">
    <div class="chapters-sidebar-wrapper">
        <div id="chapters-sidebar" class="chapters-sidebar">
            <ol>
                <?php
                foreach ($chapters as $keyC => $subchapters) {
                    if ($subchapters && ! empty($subchapters) ) {
                    ?>
                    <li class=chapter><a href="<?php echo '#chapter=' . $keyC . '&subchapter=' . array_key_first($subchapters); ?>"><?php echo $keyC ?></a></li>
                    <?php

                        ?>
                        <ol class="subchapters">
                        <?php
                        foreach ($subchapters as $keyS => $content) {
                            ?>
                                <li class="subchapter"><a href="<?php echo '#chapter=' . $keyC . '&subchapter=' . $keyS; ?>"><?php echo $keyS ?></a></li>
                            <?php
                        }
                        ?>
                        </ol>
                        <?php
                    }
                }
                ?>
            </ol>
        </div>
    </div>

    <?php
    foreach ($chapters as $subchapters) {
        foreach ($subchapters as $key => $contents) {
            ?>
            <div id="subchapter=<?php echo $key ?>" class="content">
            <?php
            foreach ($contents as $content) {
                echo $content;
            }
            ?>
            </div>
            <?php
        }
    }
    ?>
</div>

<?php
}
?>

<script src="<?php echo get_template_directory_uri() ?>/page-js/single-manual.js"></script>

<?php

get_footer();

?>
