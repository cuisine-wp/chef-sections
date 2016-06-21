<div class="column content">
	<?php if( $column->getField( 'title' ) ):?>
		<h2 itemprop="name"><?php echo esc_html( $column->getField( 'title' ) );?></h2>
	<?php endif;?>

	<?php if( $column->getField( 'content' ) ):?>
	<span itemprop="text">
		<?php $column->theField( 'content' );?>
	</span>
	<?php endif;?>
</div>