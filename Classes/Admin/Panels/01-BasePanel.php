<?php

namespace ChefSections\Admin\Panels;

use Cuisine\Utilities\Session;
use Cuisine\Wrappers\Field;
use ChefSections\Collections\ContainerCollection;

class BasePanel{

	/**
	 * String with the title of this panel
	 * 
	 * @var string
	 */
	protected $title;

	/**
	 * String with this slug
	 * 
	 * @var string
	 */
	protected $slug;

	/**
	 * Array containing all options
	 * 
	 * @var array
	 */
	protected $options;



	/**
	 * All fields part of this panel
	 * 
	 * @var array
	 */
	public $fields;




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

		add_filter( 'chef_sections_panel_buttons', array( &$this, 'button' ), 100, 2 );
		add_action( 'chef_section_setting_panels', array( &$this, 'build' ) );

	}


	/**
	 * Build this section panel
	 * 
	 * @return String (html)
	 */
	public function build( $section ){

		if( $this->validContainer( $section ) ){

			echo '<div class="settings-panel '.sanitize_title( $this->slug ).'" id="panel-'.esc_attr( $this->slug ).'">';
				echo '<span class="arrow"></span>';
				echo '<h2>'.esc_html( $this->title ).'<i id="close-panel">&times;</i></h2>';

				foreach( $this->fields as $field ){

					//set values
					$_name = $field->name;
					$value = $section->getProperty( $_name );

					if( $value ){
						$field->properties['defaultValue'] = $value;
					}else{
						$field->properties['defaultValue'] = '';
					}

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
	}

	/**
	 * Build the button
	 * 
	 * @return array
	 */
	public function button( $buttons, $section ){

		if( $this->validContainer( $section ) ){
		
			$buttons[ $this->slug ] = array(
				'label' => $this->title,
				'name' => $this->slug,
				'icon' => $this->options['icon'],
				'position' => $this->options['position']
			);

		}

		return $buttons;
	}


	/**
	 * Check if the section has a container, and if this panel applies to it
	 *
	 * @return bool
	 */
	public function validContainer( $section )
	{

		//show, regardless:
		if( is_null( $this->options['containerSlug'] ) )
			return true;
		
		if( 
			!is_null( $this->options['containerSlug'] ) && 
			!is_null( $section->container_id )
		){
	
			if( !is_array( $this->options['containerSlug'] ) )
				$this->options['containerSlug'] = [ $this->options['containerSlug'] ];

			$container = ( new ContainerCollection() )->getById( $section->container_id, $section->post_id );

			if( in_array( $container['slug'], $this->options['containerSlug'] ) )
				return true;

		}

		return false;
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
			'icon'				=> false,
			'position'			=> 10,
			'containerSlug' 	=> null
		);


		return wp_parse_args( $options, $defaults );

	}




}
