<?php
/**
 * Blogpost block
 *
 * @since 2016
 */

	
	echo '<a itemscope itemtype="http://schema.org/creativeWork" itemprop="hasPart" href="'.esc_url( get_permalink()).'" class="block block-'.esc_attr( get_post_type() ).'">';

			if( has_post_thumbnail() ){

				echo '<div class="thumbnail">';

					the_post_thumbnail('full', $attr = 'itemprop=image');
					
				echo '</div>';

			}
			
		echo '<div class="content-wrapper">';

			echo '<h2 itemprop="name">'.esc_html( get_the_title() ).'</h2>';
			echo '<div class="description" itemprop="description">'.get_the_excerpt().'</div>';

		echo '</div>';

		echo '<meta itemprop="url" content="'.esc_url( get_permalink() ).'"></meta>';

	echo '</a>';
