<?php

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
			if( ( $inRow == $maxRow || $i == $maxPosts ) && $grid !== 'masonry' ){
				echo '</div>';
				$inRow = 0;
			}
	
		}

	}else if( $query->have_posts() && $view === 'list' ){



	}
