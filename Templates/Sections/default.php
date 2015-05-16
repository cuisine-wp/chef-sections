<?php
	
	$class = 'section';
	$class .= ' type-'.$section->view;
	$class .= ' '.sanitize_title( $section->title );

	echo '<div class="'.$class.'" id="section-'.$section->id.'">';

		if( $section->show_title ){
			echo '<h2 class="section-title">';
				echo $section->title;
			echo '</h2>';
		}

		echo '<div class="container">';

			echo '<div class="column-wrapper '.$section->view.'">';
				
				the_columns( $section );

			echo '</div>';

		echo '</div>';

	echo '</div>';

?>