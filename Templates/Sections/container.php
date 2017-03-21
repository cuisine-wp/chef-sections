<?php
	
	if( $section->hide_title === 'false' )
		$section->theTitle();
		

		echo '<div class="section-wrapper '.esc_attr( $section->slug ).'">';
					
			the_containered_sections( $section );

		echo '</div>';
