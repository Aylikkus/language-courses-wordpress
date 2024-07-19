<?php
/*
 * Template Name: Instruments Page
*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-instruments.css">
<script src="<?php echo get_template_directory_uri() ?>/libs/docx.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/libs/FileSaver.js"></script>
<?php

get_header();

?>
<div class="instruments">
    <h1>ИНСТРУМЕНТЫ</h1>
    <ul>
        <li><button type="button" onclick="docxModalOpen()">Генерация DOCX</button></li>
    </ul>
</div>

<div id="docx-modal" class="modal">
    <div class="modal-content">
        <form id="docx-generate-form">
            <p><label for="title">Название</label><input placeholder="Название..." type="text" name="title"/></p>
            <p class="select-row"><label for="docType">Тип документа</label><select class="widefat" name="docType">
				<option value="muLab">МУ для лабораторных</option>
				<option value="muPrac">МУ для практических</option>
				<option value="muKP">МУ для курсовых проектов</option>
				<option value="muKR">МУ для курсовых работ</option>

				<option value="labPrac">Лабораторный практикум</option>
				<option value="tutorial">Учебное пособие</option>
				<option value="textbook">Учебник</option>
				<option value="monograph">Монография</option>
			</select></p>
            <p id="purpose-p"><label for="purpose">Назначение</label><input placeholder="Назначение..." type="text" name="purpose"/></p>
            <p id="rector-p"><label for="rector">Ректор</label><input placeholder="Ректор..." type="text" name="rector"/></p>
            <p><label for="authors">Автор(-ы)</label><input placeholder="Список авторов..." type="text" name="authors"/></p>
            <p><label for="udk">Универсальная десятичная классификация</label><input placeholder="УДК..." type="text" name="udk"/></p>
            <p><label for="bbk">Библиотечно-библиографическая классификация</label><input placeholder="ББК..." type="text" name="bbk"/></p>
            <p id="cafedry-p"><label for="cafedry">Кафедра</label><input placeholder="Кафедра..." type="text" name="cafedry"/></p>
            <button type="button" onclick="generateDocx('<?php echo get_template_directory_uri() ?>/images/bgtu-logo.png')">Сгенерировать DOCX</button>
        </form>
    </div>
</div>

<script src="<?php echo get_template_directory_uri() ?>/page-js/page-instruments.js"></script>

<?php

get_footer();

?>
