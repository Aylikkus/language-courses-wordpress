<?php
/*
 * Template Name: Profile Page
*/
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/page-css/page-profile.css">
<?php

if ($_GET['userid']) {
	$user = get_user_by('id', $_GET['userid']);
}
else {
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
	}
	else {
		$no_current_user = true;
	}
}

ob_start();
get_header();
$header = ob_get_clean();

if ( $user ) {
    $header = preg_replace('#<title>(.*?)<\/title>#', '<title>Профиль ' . get_the_author_meta('display_name', $user->ID ) . '</title>', $header);
    echo $header;

	$is_logged_in_user = wp_get_current_user()->ID == $user->ID;

    if ( $user->roles[0] == 'teacher' ) {
        $role = 'преподаватель';
        $can_add_course = $is_logged_in_user;
        $is_teacher = true;
    }
    else if ( $user->roles[0] == 'teacher-temp' ) {
        $role = 'преподаватель (неподтверждён)';
    }
    else if ( $user->roles[0] == 'student' ) {
        $role = 'ученик';
    }
    else {
        $role = $user->roles[0];
    }
?>

<div class="user-information">

<div class="user-card">
    <div class="user-avatar">
        <?php echo get_avatar( $user->ID, 250 ); ?>
    </div>
    <div class="user-name-role">
        <h3><?php echo $user->data->display_name; ?></h3>
        <p><?php echo 'Роль - ' . $role; ?></p>
        <p><?php echo 'Электронная почта - ' . $user->data->user_email; ?></p>
        <p><?php echo 'Дата регистрации - ' . $user->data->user_registered; ?></p>
        <div class="user-buttons">
        <?php
        if ( $can_add_course ) {
            ?>
            <button id="modal-open" class="course-create">
                Создать курс
            </button>
            <?php
        }
        if ( $is_logged_in_user ) {
			?>
			<form action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="delete-current-user"/>
				<input class="delete-user" type="submit" name="submit" value="Удалить уч. запись"/>
			</form>
			<?php
		}
        ?>
        </div>
    </div>
</div>

<div class="user-description">
    <p>
    <?php
        $description = get_the_author_meta( 'description', $user->ID );
        if ($description) {
            echo $description;
        }
        else {
            echo 'Нет описания';
        }
    ?>
    </p>
</div>

<?php

	if ( $is_teacher ) {
		$args = array(
			'post_type'         => 'Course',
			'posts_per_page'    => 6,
			'author'            => $user->ID
		);
		$loop = new WP_Query($args);
		while ( $loop->have_posts() ) {
			$loop->the_post();
			?>

	<div class="courses">
		<h1>Курсы преподавателя</h1>
		<div class="courses-grid">
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
	?>
		</div>
	</div>
	<?php
	}
	?>
</div>


<div class="trajectory">
	<h1>Индивидуальная траектория развития</h1>
	<table>
		<tr>
			<th>Дата</th>
			<th>Баллы</th>
			<th>Информация о тесте</th>
			<th>Процент правильных ответов</th>
			<th>Дополнительный материал для подготовки</th>
		</tr>
	<?php
		$scores = new Scores();
		$results = $scores->GetScoresFor($user->ID);
		foreach ($results as $row) {
			$links = explode(", ", $row->refs);
			?>
			<tr
			<?php
				if ($row->percent < 40)
					echo "class=\"row-red\"";
				elseif ($row->percent > 80)
					echo "class=\"row-green\"";
			?>
			>
				<th><?php echo $row->time; ?></th>
				<th><?php echo $row->score; ?></th>
				<th><?php echo $row->testInfo; ?></th>
				<th><?php echo $row->percent; ?></th>
				<th class="link-col">
					<?php 
					foreach ($links as $link) {
						$parts = parse_url($link);
						parse_str($parts['query'], $query);
						$content = str_replace("-", " ", $query['content']);
						$content = mb_strtoupper(mb_substr($content, 0, 1)).mb_substr($content, 1);
						echo "<a href=" . $link . ">" . $content . "</a>";
					}

					if (empty($row->refs))
						echo "<p>Вы правильно ответили на все вопросы.</p>
						<p>Материалы для подготовки не требуются.</p>
						<p></p>"; 
					?>
				</th>
			</tr>
			<?php
		}
	?>
	</table>
</div>

<div class="olympiads-participation">
	<h1>Участие в олимпиадах</h1>
	<div class="olympiads-list">
		<?php
		$olympiadsDb = new Olympiads();
		$olympiads = $olympiadsDb->GetEntriesFor($user->ID);

		if (empty($olympiads)) {
			echo '<p>Пользователь не принимал участие в олимпиадах</a>';
		}

		$first = true;
		foreach ($olympiads as $olympiad) {
			if (! $first) {
				echo ', ';
			}

			$url = home_url('/?page_id=' . $olympiad->page_id . "&olympiad=" . $olympiad->olympiad_title);
			echo "<a href=\"{$url}\">{$olympiad->olympiad_title}</a>";
		}
		?>
	</div>
</div>
<?php
}
elseif ($no_current_user) {
	$header = preg_replace('#<title>(.*?)<\/title>#', '<title>Профиля не существует</title>', $header);
    echo $header;
    echo '<div class="user-not-found">Вы не залогинены :(</div>';
}
else {
    $header = preg_replace('#<title>(.*?)<\/title>#', '<title>Профиля не существует</title>', $header);
    echo $header;
    echo '<div class="user-not-found">Такого пользователя не существует :(</div>';
}

if ( $can_add_course ) {

    $difficulty_terms = get_terms( array(
        'taxonomy'  =>  'course-difficulty',
        'hide_empty' => false
    ) );
    $category_terms = get_terms( array(
        'taxonomy'  =>  'course-category',
        'hide_empty' => false
    ) );
?>

<div id="modal" class="modal">
    <div class="modal-content">
        <form action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="post">
            <input type="hidden" name="action" value="add-post" />
            <p><label for="title">Заголовок</label><input placeholder="Заголовок..." type="text" name="title"/></p>
            <p><label for="features">Особенности</label><input placeholder="Особенности, через запятую" type="text" name="features"/></p>
            <div class="meta-columns">
                <p><label for="hours">Кол-во часов</label><input type="number" min="0" name="hours"/></p>
                <p><label for="lections">Кол-во лекций</label><input type="number" min="0" name="lections"/></p>
                <p><label for="people-count">Кол-во человек</label><input type="number" min="0" name="people-count"/></p>
            </div>
            <p><label for="price">Цена</label><input type="number" min="0" step="0.01" name="price"/></p>
            <p class="select-row"><label for="difficulty">Сложность</label><select class="widefat" name="difficulty">
				<?php
					foreach ( $difficulty_terms as $diff ) {
						echo '<option value="' . $diff->term_id . '">' . $diff->name . '</option>';
					}
				?>
			</select></p>
			<p class="select-row"><label for="category">Категория</label><select class="widefat" name="category">
				<?php
					foreach ( $category_terms as $cat ) {
						echo '<option value="' . $cat->term_id . '">' . $cat->name . '</option>';
					}
				?>
			</select></p>
			<p><label for="description">Описание</label><textarea class ="description-textarea" placeholder="Описание курса..." rows="5" cols="33" name="description"></textarea></p>
            <p class="course-submit"><input class="button" type="submit" name="submit" value="Создать курс"/></p>
        </form>
    </div>
</div>

<script src="<?php echo get_template_directory_uri() ?>/page-js/page-profile.js"></script>

<?php
}

get_footer();

?>
