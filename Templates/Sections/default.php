<?php
	
	if( $section->hide_title === 'false' )
		$section->theTitle();
		

	if( $section->getProperty( 'hide_container' ) == 'false' )
		echo '<div class="container">';

			echo '<div class="column-wrapper column-row '.esc_attr( $section->view ).'">';
					
				the_columns( $section );
	
			echo '</div>';

	if( $section->getProperty( 'hide_container' ) == 'false' )
		echo '</div>';


?>