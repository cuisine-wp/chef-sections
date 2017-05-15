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
	


		public function generate()
		{
			
			
			Generator::page( 'Home', function( $page ){

				$page->sectionContainer( 'Slide', [

					'type' => 'container',
					'slug' => 'tabs',
					'view' => 'tabbed',
					'sections' => [

						$page->section( 'Ons bedrijf', [
							'view' => 'half-half',
							'allowedColumns' => [ 'image', 'content' ],
						]),

						$page->section( 'Onze diensten', [
							'view' => 'half-half',
							'allowedColumns' => [ 'image', 'content' ]
						]),

						$page->section( 'Aanbieding', [
							'view' => 'half-half',
							'allowedColumns' => [ 'image', 'content' ]
						])
					]
				]);

				$page->section( 'Toptaken', [

						'view' => 'half-half',
						'classes' => ['toptaken'],
						'allowedViews' => [ 'fullwidth', 'half-half', 'three-columns' ],
						'allowedColumns' => [ 'toptaak' ],
						'columns' => [
							$page->column( 'toptaak' )->title( 'Instalozzie' ),
							$page->column( 'toptaak' )->title( 'Reperozzie' )
						]

				]);

				$page->section( 'Call to Action', [

					'view' => 'three-columns',
					'classes' => ['call-to-action'],
					'columns' => [
						$page->column( 'content' )->title( 'Is uw interesse gewekt?' ),
						$page->column( 'content' )->content( '[button link="/contact"]Neem contact op[/button]' )
					]
				]);

				//$page->applyTo( 'product' );
				$page->properties([

					'post_title' => 'Henk',
					'post_type' => 'product',

				]);

				$page->postId( 5 ); 
				
			
			//});




			Generator::section( 'Toptaken', function( SectionGenerator $section ){

				$section->view( 'half-half' );
				$section->classes( ['toptaken'] );
				$section->allowedViews([ 'fullwidth', 'half-half', 'three-columns' ]);
				$section->allowedColumns([ 'toptaak' ]);
				$section->columns([

					$section->column( 'toptaak', [

						'text' => [ 'type' => 'h2', 'text' => 'JAjaja' ],
						'content' => 'hank',
						'postion' => 2
					]),

					$section->column( 'toptaak' )->content( 'Instalozzie' )

				]);

			});
		}
	*/

