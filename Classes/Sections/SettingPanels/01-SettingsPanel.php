<?php

namespace ChefSections\Sections\SettingsPanels;

use Cuisine\Utilities\Session;
use Cuisine\Wrappers\Field;

class SettingsPanel{

	/**
	 * String with the title of this panel
	 * 
	 * @var string
	 */
	private $title;

	/**
	 * String with this slug
	 * 
	 * @var string
	 */
	private $slug;

	/**
	 * Array containing all options
	 * 
	 * @var array
	 */
	private $options;


	/**
	 * All fields part of this panel
	 * 
	 * @var array
	 */
	public $fields;



	function __construct(){

		add_filter( 'chef_sections_panel_buttons', array( &$this, 'button' ) );


	}



	/**
	 * Make a form panel
	 * 
	 * @param  int $post_id
	 * @return ChefForms\Builders\SettingsPanel
	 */
	public function make( $title, $name, $options = array() ){
		
		$this->postId = Session::postId();

		$this->slug = $name;
		$this->title = $title;
		$this->options = $this->sanitizeOptions( $options );
		

		return $this;
	}


	/**
	 * Set a form-panel
	 * 
	 * @param [type] $fields [description]
	 */
	public function set( $fields ){

		$this->fields = $fields;

		add_action( 'chef_section_setting_panels', array( &$this, 'build' ) );

	}


	/**
	 * Build this section panel
	 * 
	 * @return String (html)
	 */
	public function build( $section ){
		
		echo '<div class="settings-panel '.sanitize_title( $this->slug ).'">';

			echo '<h2>'.$this->title.'</h2>';

			foreach( $this->fields as $field ){

				//set values
				$_name = $field->name;
				$value = $section->getProperty( $_name );

				if( $value )
					$field->properties['defaultValue'] = $value;

				$field->setName( 'section['.$section->id.']['. $_name .']' );
				$field->render();
				$field->setName( $_name );	

			}

			//render the javascript-templates seperate, to prevent doubles
			$rendered = array();
							
			foreach( $this->fields as $field ){
							
				if( method_exists( $field, 'renderTemplate' ) && !in_array( $field->name, $rendered ) ){
						
						echo $field->renderTemplate();
						$rendered[] = $field->name;
							
				}
			}	


		echo '</div>';

	}

	/**
	 * Build the button
	 * 
	 * @return array
	 */
	public function button( $buttons ){

		$buttons[ $this->slug ] = array(
			'label' => $this->title,
			'name' => $this->slug,
			'icon' => $this->options['icon'],
			'position' => $this->options['position']
		);

		return $buttons;
	}



	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/


	/**
	 * Checks if an option is set, then returns it.
	 * 
	 * @param  string $name
	 * @return mixed
	 */
	private function get( $name ){

		if( isset( $this->options[ $name ] ) )
			return $this->options[ $name ];

		return false;

	}



	/**
	 * Set the options with defaults
	 * 
	 * @param  array $options
	 * @return array
	 */
	private function sanitizeOptions( $options ){

		$defaults = array(
			'icon'		=> false,
			'position'	=> 10
		);


		return wp_parse_args( $options, $defaults );

	}




}
