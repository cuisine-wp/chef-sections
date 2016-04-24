<?php

	$url = $column->getField( 'large' );

	if( $url == false || $url == 'false' || $url == '' )
		$url = $column->getField( 'full' );

?>
<div class="column image">
	<img itemscope itemtype="http://schema.org/Thing" itemprop="image" src="<?php echo $url;?>"/>
</div>