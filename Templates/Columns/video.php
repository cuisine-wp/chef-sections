<div itemprop="video" class="column video">

	<?php if( $column->getField( 'title' ) ):?>
		<h2 itemprop="name"><?php echo esc_html( $column->getField( 'title' ) );?></h2>
	<?php endif;?>

	<div class="video-wrapper">
		<?php 

			$still = $column->getField( 'still' );
			if( $still && $still['full'] != '' ){

				$url = $still['full'];

				if( isset( $still['large'] ) && $still['large'] != '' )
					$url = $still['large'];

				echo '<div itemprop="video" style="background-image:url('.esc_url( $url ).')" class="video-still"></div>';

			}

			echo wp_oembed_get( $column->getField( 'url' ) );

		?>
	</div>

	
</div>