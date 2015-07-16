<div class="column video">
	<div class="video-wrapper">
		<?php 

			$still = $column->getField( 'still' );
			if( $still ){

				$url = $still['full'];

				if( isset( $still['large'] ) )
					$url = $still['large'];

				echo '<img src="'.$url.'" class="video-still"/>';

			}

			echo wp_oembed_get( $column->getField( 'url' ) );


		?>
	</div>

	<?php if( $column->getField( 'title' ) ):?>
		<h2><?php $column->theField( 'title' );?></h2>
	<?php endif;?>
</div>