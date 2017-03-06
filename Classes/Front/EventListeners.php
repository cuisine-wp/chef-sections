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

				PostType::make( 'section-template', __( 'Templates', 'chefsections' ), __( 'Template', 'chefsections' ) )->set( $args );



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

	}

	\ChefSections\Front\EventListeners::getInstance();
