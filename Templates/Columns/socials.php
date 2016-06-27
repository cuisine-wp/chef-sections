<?php

	echo '<div class="column socials" itemscope itemtype="https://schema.org/Organization">';

	if( $column->getField( 'title' ) ){

		echo '<h2 itemprop="name">'.esc_html( $column->getField( 'title' ) ).'</h2>';

	}

	
		echo '<div class="socials-icons">';

			if( $column->getField( 'fb' ) ){

				echo '<a itemprop="sameAs" href="'.esc_url( $column->getField( 'fb' ) ).'" class="social fb" target="_blank">';
					echo '<i class="fa fa-facebook"></i>';
				echo '</a>';

			}


			if( $column->getField( 'tw' ) ){

				echo '<a itemprop="sameAs" href="'.esc_url( $column->getField( 'tw' ) ).'" class="social tw" target="_blank">';
					echo '<i class="fa fa-twitter"></i>';
				echo '</a>';

			}


			if( $column->getField( 'in' ) ){

				echo '<a itemprop="sameAs" href="'.esc_url( $column->getField( 'in' ) ).'" class="social in" target="_blank">';
					echo '<i class="fa fa-linkedin"></i>';
				echo '</a>';

			}


			if( $column->getField( 'pin' ) ){

				echo '<a itemprop="sameAs" href="'.esc_url( $column->getField( 'pin' ) ).'" class="social pin" target="_blank">';
					echo '<i class="fa fa-pinterest"></i>';
				echo '</a>';

			}

			if( $column->getField( 'gp' ) ){

				echo '<a itemprop="sameAs" href="'.esc_url( $column->getField( 'gp' ) ).'" class="social gp" target="_blank">';
					echo '<i class="fa fa-google-plus"></i>';
				echo '</a>';

			}


			if( $column->getField( 'ins' ) ){

				echo '<a itemprop="sameAs" href="'.esc_url( $column->getField( 'ins' ) ).'" class="social ins" target="_blank">';
					echo '<i class="fa fa-instagram"></i>';
				echo '</a>';

			}

		echo '</div>';

	echo '</div>';