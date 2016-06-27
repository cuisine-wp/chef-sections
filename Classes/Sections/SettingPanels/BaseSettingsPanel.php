<?php

	namespace ChefSections\Sections\SettingsPanels;

	use Cuisine\Wrappers\Field;
	use ChefSections\Wrappers\StaticInstance;
	use ChefSections\Wrappers\SettingsPanel as Panel;

	class BaseSettingsPanel extends StaticInstance{


		/**
		 * Init admin events & vars
		 */
		public function __construct(){

			$this->listen();

		}

		/**
		 * Listen for SettingsPanels
		 * 
		 * @return void
		 */
		private function listen(){


			add_action( 'init', function(){

				$fields = $this->getSectionSettingFields();

				Panel::make( 

					__( 'Settings', 'chefsections' ),
					'settings',
					array(
						'position' 	=> -1,
						'icon'		=> 'dashicons-admin-generic'
					)

				)->set( $fields );

			});
		}

		/**
		 * Returns an array of field objects
		 * 
		 * @return array
		 */
		private function getSectionSettingFields(){
			
			$fields = array(

				Field::text(
					'name',
					__( 'Template name', 'chefsections' )
				),

				Field::text( 
					'classes',
					__( 'CSS Classes', 'chefsections' ),
					array( 
						'placeholder'  => __( 'Seperate with commas\'s', 'chefsections' ),
					)
				),

				Field::checkbox(
					'hide_container',
					__( 'Hide Container', 'chefsections' )
				),

			);

			$fields = apply_filters( 'chef_sections_setting_fields', $fields, $this );

			return $fields;
		}

	}

	\ChefSections\Sections\SettingsPanels\BaseSettingsPanel::getInstance();
