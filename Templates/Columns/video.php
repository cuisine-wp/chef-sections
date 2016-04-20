<div itemprop="video" class="column video">
	<div class="video-wrapper">
		<?php 

			$still = $column->getField( 'still' );
			if( $still && $still['full'] != '' ){

				$url = $still['full'];

				if( isset( $still['large'] ) && $still['large'] != '' )
					$url = $still['large'];

				echo '<div style="background-image:url('.$url.')" class="video-still"></div>';

			}

			echo wp_oembed_get( $column->getField( 'url' ) );


		?>
	</div>

	<?php if( $column->getField( 'title' ) ):?>
		<h2 itemscope itemtype="http://schema.org/Thing" itemprop="name"><?php $column->theField( 'title' );?></h2>
	<?php endif;?>
</div>