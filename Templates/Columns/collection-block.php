<a itemscope itemtype="http://schema.org/CreativeWork" itemprop="exampleOfWork" href="<?php the_permalink();?>" class="block block-<?php echo get_post_type();?>">
	<?php echo '<div itemscope itemtype="http://schema.org/Thing" itemprop="image" class="thumbnail">'.get_the_post_thumbnail('medium').'</div>'; ?>
	<h2 itemscope itemtype="http://schema.org/Thing" itemprop="name"><?php the_title();?></h2>
	<div itemscope itemtype="http://schema.org/Thing" itemprop="about"><?php the_excerpt();?></div>
</a>

<?php
/**
 * Blogpost block
 *
 * @package ChefOehlala
 * @since 2016
 */

	use Cuisine\View\Template;
	use Cuisine\View\Loop;
	

	
	echo '<a itemscope itemtype="http://schema.org/creativeWork" itemprop="hasPart" href="'.Loop::link().'" class="block block-'.Loop::type().'">';

			if( has_post_thumbnail() ){

				echo '<div class="thumbnail">';

					the_post_thumbnail('full', $attr = 'itemprop=image');
					
				echo '</div>';

			}
			
		echo '<div class="content-wrapper">';

			echo '<h2 itemprop="name">'.Loop::title().'</h2>';
			echo '<div class="description" itemprop="description">'.get_the_excerpt().'</div>';

		echo '</div>';

		echo '<meta itemprop="url" content="'.Loop::link().'"></meta>';

	echo '</a>';
