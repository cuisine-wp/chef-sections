<?php

	namespace ChefSections\Front;

	use ChefSections\Wrappers\StaticInstance;
	use Cuisine\Wrappers\PostType;
	use Cuisine\Wrappers\Field;
	use Cuisine\Utilities\Url;

	class EventListeners extends StaticInstance{


		/**
		 * Init admin events & vars
		 */
		public function __construct(){

			$this->listen();

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

				PostType::make( 'section-template', __( 'Sjablonen', 'chef-sections' ), __( 'Sjabloon', 'chefsections' ) )->set( $args );



				//custom field type:
				add_filter( 'cuisine_field_types', function( $types ){


					$types['taxonomySelect'] = array(
								'name'		=> 'TaxonomySelect',
								'class'		=> 'ChefSections\Hooks\TaxonomySelect'
					);

					$types['mapper'] = array(
								'name'		=> 'MapperField',
								'class'		=> 'ChefForms\Hooks\MapperField'
					);

					return $types;

				});


				Field::taxonomySelect( 'wop', 'Wap' )->render();

			});
		}

	}

	\ChefSections\Front\EventListeners::getInstance();
