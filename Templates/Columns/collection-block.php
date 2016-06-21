<?php
/**
 * Blogpost block
 *
 * @package ChefOehlala
 * @since 2016
 */

	use Cuisine\View\Template;
	use Cuisine\View\Loop;
	

	
	echo '<a itemscope itemtype="http://schema.org/creativeWork" itemprop="hasPart" href="'.esc_url( Loop::link() ).'" class="block block-'.esc_attr( Loop::type() ).'">';

			if( has_post_thumbnail() ){

				echo '<div class="thumbnail">';

					the_post_thumbnail('full', $attr = 'itemprop=image');
					
				echo '</div>';

			}
			
		echo '<div class="content-wrapper">';

			echo '<h2 itemprop="name">'.esc_html( Loop::title() ).'</h2>';
			echo '<div class="description" itemprop="description">'.get_the_excerpt().'</div>';

		echo '</div>';

		echo '<meta itemprop="url" content="'.esc_url( Loop::link() ).'"></meta>';

	echo '</a>';
