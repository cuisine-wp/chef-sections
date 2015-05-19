<a href="<?php the_permalink();?>" class="block block-<?php echo get_post_type();?>">
	<?php the_post_thumbnail( 'medium' );?>
	<h2><?php the_title();?></h2>
	<?php the_excerpt();?>
</a>