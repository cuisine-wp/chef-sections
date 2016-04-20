<?php

	$url = $column->getField( 'large' );

	if( $url == false || $url == 'false' || $url == '' )
		$url = $column->getField( 'full' );

?>
<div itemprop="image" class="column image">
	<img src="<?php echo $url;?>"/>
</div>