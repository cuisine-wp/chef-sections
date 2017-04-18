<?php

	namespace ChefSections\Admin\Generators;

	use WP_Query;
	use ChefSections\SectionTypes\Blueprint as BlueprintSection;
	use ChefSections\Contracts\Generator as GeneratorContract;

	class Blueprint extends BaseGenerator implements GeneratorContract{



		/**
		 * Set the data straight
		 * 
		 * @param  Array  $data
		 * 
		 * @return Array
		 */
		public function sanitizeData( $data ){

			if( sizeof( $data['sections'] ) > 0 ){
				
				//set all sections to arrays,
				//and all column data to arrays
				foreach( $data['sections '] as $key => $section ){

					if( !is_array( $section ) )
						$data['sections'][ $key ] = $section->toArray();

					if( sizeof( $section['columns'] ) > 0 ){
						
						foreach( $section['columns'] as $colKey => $column ){
							
							$data['sections'][ $key ]['columns'][ $colKey ] = $column->toArray();

						}
					}
				}
			}

			return $data;
		}

	
		/**
		 * Load sections from a template
		 * 
		 * @return bool
		 */
		public function generate( $templateId = null ){
			
			// create blueprint here
			
		}


	}


/*
	Generator::make( 'blueprintSection', function( Section $section ){

		$section->view( 'half-half' );
		$section->class( 'toptaak' );

		//variations are off the table
		//$section->defaultVariation( 'toptaak-1' );
		//$section->variations([ 'toptaak-1' => 'Toptaak 1', 'toptaak-2' => 'Toptaak 2' ]);

		$section->allowedColumns([ 'content', 'video' ]);
		$section->allowedViews([ 'half-half', 'fullwidth' ]);

		$section = array(
			'view' => 'half-half',
			'class' => 'toptaak'
		);

		$columns = [
			Column::toptaak(),
			Column::toptaak()
		];


		$section->columns($columns);

	})*/