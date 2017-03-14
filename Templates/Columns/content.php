<?php 

echo '<div class="column content">';

	$column->theTitle();

	echo '<span itemprop="text">';
		echo $column->getField( 'content' );
	echo '</span>';

echo '</div>';

?>