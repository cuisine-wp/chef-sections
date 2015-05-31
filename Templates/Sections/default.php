<?php
	
	$class = 'section';
	$class .= ' type-'.$section->view;
	$class .= ' '.sanitize_title( $section->title );

	echo '<div class="'.$class.'" id="section-'.$section->id.'">';

		if( $section->hide_title === false ){
			echo '<h2 class="section-title">';
				echo $section->title;
			echo '</h2>';
		}

		echo '<div class="container">';

			echo '<div class="column-wrapper column-row '.$section->view.'">';
				
				the_columns( $section );

			echo '</div>';

		echo '</div>';

	echo '</div>';

?>