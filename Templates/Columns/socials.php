<?php

	echo '<div class="column socials" itemscope itemtype="https://schema.org/Organization">';

	if( $column->getField( 'title' ) ){

		echo '<h2 itemprop="name">'.esc_html( $column->getField( 'title' ) ).'</h2>';

	}

	echo '<div class="socials-icons socials">';

		$socials = $column->getField( 'socials' );
		if( !empty( $socials ) && !empty( $socials[0]['link'] ) ){

			foreach( $socials as $social ){

				echo '<a itemprop="sameAs" href="'.esc_url( $social['link'] ).'" class="social '.$social['icon'].'" target="_blank">';
					echo '<i class="fa fa-'.$social['icon'].'"></i>';
				echo '</a>';

			} 

		}
	echo '</div>';

	echo '</div>';