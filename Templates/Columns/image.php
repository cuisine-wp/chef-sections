<?php
/**
 * Image column
 *
 * @package ChefOehlala
 * @since 2016
 */


	$url = $column->getField( 'medium' );
	if( !$url || $url == '' )
		$url = $column->getField( 'full' );

	echo '<div class="column image">';
		echo '<meta itemprop="image" content="'.$url.'">';
		echo '<img src="'. $url. '"/>';
	echo '</div>';

	