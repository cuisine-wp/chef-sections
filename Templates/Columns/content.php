<div class="column content">
	<?php if( $column->getField( 'title' ) ):?>
		<h2 itemprop="name"><?php $column->theField( 'title' );?></h2>
	<?php endif;?>
	<?php 
		echo '<span itemprop="text">';
			$column->theField( 'content' );
		echo '</span>';
	?>
</div>