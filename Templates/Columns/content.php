<div itemprop="text" class="column content">
	<?php if( $column->getField( 'title' ) ):?>
		<h2 itemscope itemtype="http://schema.org/Thing" itemprop="name"><?php $column->theField( 'title' );?></h2>
	<?php endif;?>
	<?php $column->theField( 'content' );?>
</div>