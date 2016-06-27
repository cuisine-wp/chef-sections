<div class="column content">
	<?php if( $column->getField( 'title' ) ):?>
		<h2 itemprop="name"><?php echo esc_html( $column->getField( 'title' ) );?></h2>
	<?php endif;?>

	<?php 
		echo '<span itemprop="text">';
			echo $column->getField( 'content' );
		echo '</span>';
	?>
</div>