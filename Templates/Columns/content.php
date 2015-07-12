<div class="column content">
	<?php if( $column->getField( 'title' ) ):?>
		<h2><?php $column->theField( 'title' );?></h2>
	<?php endif;?>
	<?php $column->theField( 'content' );?>
</div>