<?php
	
	$class = 'section';
	$class .= ' '.$section->name;

	echo '<div class="'.$class.'" id="'.$section->name.'">';


		if( $section->hide_title === 'false' ){
			echo '<h2 class="section-title">';
				echo $section->title;
			echo '</h2>';
		}

		if( $section->getProperty( 'hide_container' ) == 'false' )
			echo '<div class="container">';

				echo '<div class="column-wrapper column-row '.$section->view.'">';
					
					the_columns( $section );
	
				echo '</div>';

		if( $section->getProperty( 'hide_container' ) == 'false' )
			echo '</div>';

	echo '</div>';

?>