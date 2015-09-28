<?php

	use ChefSections\Wrappers\Template;
	use Cuisine\Wrappers\Pagination;

	$query = $column->getQuery();
	$maxRow = $column->getField( 'posts_per_row' );
	$maxPosts = $column->getField( 'posts_per_page' );
	$view = $column->getField( 'view', 'blocks' );
	$grid = $column->getField( 'grid', 'grid' );

	if( $query->have_posts() && $view !== 'list' ){

		$i = 0;
		$inRow = 0;
	
		while( $query->have_posts() ){
			$query->the_post();
	
			if( $inRow == 0 && $grid !== 'masonry' )
				echo '<div class="block-row column-row">';
	
					get_block_template( $column );
	
			$i++; $inRow++;
			if( ( $inRow == $maxRow || $i == $maxPosts || $i == $query->found_posts ) && $grid !== 'masonry' ){
				echo '</div>';
				$inRow = 0;
			}
	
		}

	}else if( $query->have_posts() && $view === 'list' ){
				
		echo '<div class="block-row column-row">';
			echo '<ul>';
			
			while( $query->have_posts() ){
				$query->the_post();
				echo'<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
			}
			
			echo '</ul>';

		}

	}



