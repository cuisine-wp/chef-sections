<?php

	$url = $column->getField( 'large' );

	if( $url == false )
		$url = $column->getField( 'full' );

?>
<div class="column image">
	<img src="<?php echo $url;?>"/>
</div>