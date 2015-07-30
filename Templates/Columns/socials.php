<?php

	echo '<div class="column socials">';

	if( $column->getField( 'title' ) ){

		echo '<h2>'.$column->getField( 'title' ).'</h2>';

	}


	echo '<div class="socials-icons">';

	if( $column->getField( 'fb' ) ){

		echo '<a href="'.$column->getField( 'fb' ).'" class="social fb">';
			echo '<i class="fa fa-facebook"></i>';
		echo '</a>';

	}


	if( $column->getField( 'tw' ) ){

		echo '<a href="'.$column->getField( 'tw' ).'" class="social tw">';
			echo '<i class="fa fa-twitter"></i>';
		echo '</a>';

	}


	if( $column->getField( 'in' ) ){

		echo '<a href="'.$column->getField( 'in' ).'" class="social in">';
			echo '<i class="fa fa-linkedin"></i>';
		echo '</a>';

	}


	if( $column->getField( 'pin' ) ){

		echo '<a href="'.$column->getField( 'pin' ).'" class="social pin">';
			echo '<i class="fa fa-pinterest"></i>';
		echo '</a>';

	}

	if( $column->getField( 'gp' ) ){

		echo '<a href="'.$column->getField( 'gp' ).'" class="social gp">';
			echo '<i class="fa fa-google-plus"></i>';
		echo '</a>';

	}


	echo '<h1>PASCAL!!!</h1>';

	echo '</div>';

	echo '</div>';