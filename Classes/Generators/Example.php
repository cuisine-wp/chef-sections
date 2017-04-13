<?php
/*

	Generator::page( 'Mijn Pagina', function( PageGenerator $blueprint ){

		
		$blueprint->postType( 'page' );
		$blueprint->postId( 5 );
		$blueprint->post([
			'post_title' => 'Ons Team',
		
		])
	})

/*
	Generator::section( 'blueprint', function( SectionGenerator $section ){

		$section->view( 'half-half' );
		$section->class( 'toptaak' );

		//variations are off the table
		//$section->defaultVariation( 'toptaak-1' );
		//$section->variations([ 'toptaak-1' => 'Toptaak 1', 'toptaak-2' => 'Toptaak 2' ]);

		$section->allowedColumns([ 'content', 'video' ]);
		$section->allowedViews([ 'half-half', 'fullwidth' ]);


		$columns = [
			Column::toptaak(),
			Column::toptaak()
		];


		$section->columns($columns);

	})

	Generator::section( 'content', function( Section $section ){
	
		
		$section->postId( 5 );

	})
	

	*/