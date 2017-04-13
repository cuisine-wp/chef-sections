<?php

	namespace ChefSections\Front;

	use ChefSections\Wrappers\StaticInstance;
	use Cuisine\Wrappers\PostType;
	use Cuisine\Wrappers\Field;
	use Cuisine\Utilities\Url;

	use \ChefSections\Wrappers\Generator;
	use \ChefSections\Generators\SectionGenerator;


	class EventListeners extends StaticInstance{


		/**
		 * Init admin events & vars
		 */
		public function __construct(){

			$this->listen();
			//$this->generate();

		}

		/**
		 * All admin actions
		 * 
		 * @return void
		 */
		private function listen(){


			add_action( 'init', function(){

				$args = array(

					'exclude_from_search'	=> true,
					'publicly_queryable'	=> false,
					'menu_icon' 			=> 'dashicons-schedule',
					'supports'				=> array( 'title' ),
					'menu_position'			=> 75

				);

				PostType::make( 'section-template', __( 'Templates', 'chefsections' ), __( 'Template', 'chefsections' ) )->set( $args );

				//set page-templates
				$args['menu_icon'] = 'dashicons-media-spreadsheet';
				$args['menu_position'] = 76;
				PostType::make( 'page-template', __( 'Page templates', 'chefsections'), __( 'Page template', 'chefsections' ) )->set( $args );


				//custom field type:
				add_filter( 'cuisine_field_types', function( $types ){

					$types['taxonomySelect'] = array(
								'name'		=> 'TaxonomySelect',
								'class'		=> 'ChefSections\Hooks\TaxonomySelect'
					);

					$types['title'] = array(
								'name'		=> 'TitleField',
								'class'		=> 'ChefSections\Hooks\TitleField'
					);

					return $types;

				});

			});
		}


		public function generate()
		{
			
			
			/*Generator::page( 'Home', function( $page ){

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
				/*$page->properties([

					'post_title' => 'Henk',
					'post_type' => 'product',

				]);

				$page->postId( 5 ); 
				*/
			
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

	}

	\ChefSections\Front\EventListeners::getInstance();
