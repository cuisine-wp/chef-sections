<a itemscope itemtype="http://schema.org/CreativeWork" itemprop="exampleOfWork" href="<?php the_permalink();?>" class="block block-<?php echo get_post_type();?>">
	<?php echo '<div itemscope itemtype="http://schema.org/Thing" itemprop="image" class="thumbnail">'.get_the_post_thumbnail('medium').'</div>'; ?>
	<h2 itemscope itemtype="http://schema.org/Thing" itemprop="name"><?php the_title();?></h2>
	<div itemscope itemtype="http://schema.org/Thing" itemprop="about"><?php the_excerpt();?></div>
</a>