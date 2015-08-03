<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;
use ChefSections\Wrappers\SectionsBuilder;
use ChefSections\Wrappers\Template;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\User;

/**
 * Admin Section-meta
 * @package ChefSections\Admin
 */
class Section {

	/**
	 * Unique Id for this section, prefixed by the post_id
	 * 
	 * @var string
	 */
	public $id = '';

	/**
	 * Position of this section
	 * 
	 * @var integer
	 */
	private $position = 0;

	/**
	 * The post ID this section is tied to
	 * 
	 * @var integer
	 */
	public $post_id;

	/**
	 * Title of this section
	 * 
	 * @var string
	 */
	public $title = '';

	/**
	 * Name (used in code) of this section
	 * 
	 * @var string
	 */
	public $name = '';


	/**
	 * Viewmode of this section
	 * 
	 * @var string
	 */
	public $view = '';


	/**
	 * Template prefix
	 * 
	 * @var string
	 */
	public $template = '';


	/**
	 * All columns in this section
	 * 
	 * @var array
	 */
	public $columns = array();


	/**
	 * Simple boolean to display or not display a section title
	 *
	 * @var boolean
	 */
	public $hide_title;


	/**
	 * Boolean to display or not display the section container
	 * 
	 * @var boolean
	 */
	public $hide_container;


	/**
	 * Array containing all properties of this section
	 * 
	 * @var array
	 */
	public $properties;



	function __construct( $args ){
		

		$this->id = $args['id'];

		//get the post object based on the given post_id
		$this->post_id = $args['post_id'];
		$post = get_post( $this->post_id );

		$this->position = $args['position'];

		$this->title = $args['title'];

		$this->view = $args['view'];

		$this->name = $this->getName( $args );

		$this->hide_title = $args['hide_title'];

		$this->hide_container = $args['hide_container'];

		//cuisine_dump( $args );

		$this->properties = $args;

		$this->columns = $this->getColumns( $args['columns'] );

		$name = 'page-';
		if( isset( $post->post_name ) )
			$name = $post->post_name.'-';

		$this->template = $name;

	}
	
	/*=============================================================*/
	/**             Template                                       */
	/*=============================================================*/

	/**
	 * This function runs just before the template
	 * 
	 * @return void
	 */
	public function beforeTemplate(){

		//add a hook
		add_action( 'section_before_template', $this );


		$class = 'section';
		$class .= ' '.$this->name;

		$class = apply_filters( 'chef_section_classes', $class, $this );

		echo '<div class="'.$class.'" id="'.$this->name.'">';

	}

	/**
	 * This function runs just after the template
	 * 
	 * @return void
	 */
	public function afterTemplate(){

		echo '</div>';

		//add a hook
		add_action( 'section_after_template', $this );

	}



	/*=============================================================*/
	/**             Backend                                        */
	/*=============================================================*/


	/**
	 * Build this Section
	 * 
	 * @return String (html, echoed)
	 */
	public function build(){

		if( is_admin() ){

			echo '<div class="section-wrapper ui-state-default section-'.$this->id.'" ';
				echo 'id="'.$this->id.'" ';
				$this->buildIds();
			echo '>';

				if( User::hasRole( 'administrator' ) )
					$this->buildControls();

				echo '<div class="section-columns '.$this->view.'">';
	
				foreach( $this->columns as $column ){
	
					echo $column->build();
	
				}

				echo '<div class="clearfix"></div>';
				echo '</div>';

				if( User::hasRole( 'administrator' ) ){
					$this->bottomControls();
					$this->buildSettingsPanel();
				}
			
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}
	}

	/**
	 * Build the top of this Section
	 * 
	 * @return String ( html, echoed )
	 */
	private function buildControls(){

		$fields = $this->getControlFields();


		echo '<div class="section-controls">';

			echo '<span class="button section-settings-btn">';
				echo '<span class="dashicons dashicons-admin-settings"></span>';
				_e( 'Instellingen', 'chef-sections' );
			echo '</span>';
			
			foreach( $fields as $field ){

				$field->render();

			}

			echo '<span class="dashicons dashicons-sort pin"></span>';

		echo '</div>';
	
		echo '<div class="clearfix"></div>';
	}


	/**
	 * Create the controls on the bottom
	 * 
	 * @return string (html, echoed)
	 */
	private function bottomControls(){

		echo '<div class="section-footer">';
			echo '<p class="delete-section">';
				echo '<span class="dashicons dashicons-trash"></span>';
			echo __( 'Verwijder', 'chefsections' ).'</p>';

			do_action( 'chef_sections_bottom_controls' );

			$this->buildTemplateSnitch();
			$this->buildCodeSnitch();

		echo '</div>';

	}


	/**
	 * Generate the data-tags with id's
	 * 
	 * @return string ( html, echoed )
	 */
	private function buildIds(){

		echo 'data-section_id="'.$this->id.'" ';
		echo 'data-post_id="'.$this->post_id.'"';

	}

	/**
	 * Generate the code needed to fetch this section
	 * 
	 * @return string ( html, echoed )
	 */
	private function buildCodeSnitch(){

		echo '<span class="template-snitch code-snitch">';
			echo '<span class="dashicons dashicons-editor-code"></span>';
			echo '<span class="tooltip">';

				echo '<strong>Code:</strong><br/>';
				echo '<span class="copy">echo Loop::section( '.$this->post_id.', '.$this->id.' );</span>';

			echo '</span>';
		echo '</span>';
	}


	/**
	 * Generate the templates for this section
	 * 
	 * @return string ( html, echoed )
	 */
	private function buildTemplateSnitch(){

		$templates = Template::section( $this )->files;
		echo '<span class="template-snitch">';
			echo '<span class="dashicons dashicons-media-text"></span>';
			echo '<span class="tooltip">';

				echo '<strong>Templates:</strong>';
				foreach( $templates as $template ){

					echo '<p>'.$template.'</p>';

				}

			echo '</span>';
		echo '</span>';
	}


	private function buildSettingsPanel(){

		$fields = $this->getSettingsFields();

		echo '<div class="section-settings">';

			foreach( $fields as $field ){


				$field->render();

			}

		echo '</div>';
	}



	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/

	/**
	 * Return a property, or false if the property isn't found.
	 * 
	 * @param  string $name name of the property
	 * @return mixed
	 */
	public function getProperty( $name ){

		if( isset( $this->properties[ $name ] ) )
			return $this->properties[ $name ];

		return false;

	}

	/**
	 * Get the Columns in an array
	 * 
	 * @return array
	 */
	private function getColumns( $columns ){

		$arr = array();

		//populate the columns array with actual column objects
		foreach( $columns as $col_key => $type ){

			$props = array(
				'post_id'	=>	 $this->post_id
			);

			$arr[] = Column::$type( $col_key, $this->id, $props );

		}

		return $arr;

	}

	/**
	 * Returns the array of fields for this section
	 * 
	 * @return array
	 */
	private function getControlFields(){

		$prefix = 'section['.$this->id.']';
		$types = array_fill_keys( array_keys( SectionsBuilder::getViewTypes() ), false );
		$views = Field::radio(
			$prefix.'[view]',
			'Weergave',
			$types,
			array(
				'defaultValue' => $this->view
			)
		);

		$fields = array( $views );
		$fields = apply_filters( 'chef_sections_controls_fields', $fields );

		return $fields;
	}

	/**
	 * Returns the array of fields for the settings panel
	 * 
	 * @return array
	 */
	private function getSettingsFields(){
		
		$prefix = 'section['.$this->id.']';
		
		$title = Field::text( 
			$prefix.'[title]',
			'Sectie titel', //no label,
			array( 
				'placeholder'  => 'Titel',
				'defaultValue' => $this->title
			)
		);

		$name = Field::text(
			$prefix.'[name]',
			'Sectie naam',
			array(
				'defaultValue'	=> $this->name
			)
		);

		$check = Field::checkbox(
			$prefix.'[hide_title]',
			'Sectie title verbergen',
			array(
				'defaultValue'	=> $this->hide_title
			)
		);

		$container = Field::checkbox(
			$prefix.'[hide_container]',
			'Container verbergen',
			array(
				'defaultValue'	=> $this->hide_container
			)
		);


		$position = Field::hidden(
			$prefix.'[position]',
			array(
				'defaultValue' => $this->position,
				'class' => array( 'field', 'input-field', 'hidden-field', 'section-position' )
			)
		);

		$post_id = Field::hidden(
			$prefix.'[post_id]',
			array(
				'defaultValue' => $this->post_id
			)
		);

		$id = Field::hidden(
			$prefix.'[id]',
			array(
				'defaultValue' => $this->id
			)
		);

		$fields = array(

			$title,
			$name,
			$check,
			$container,
			$position,
			$post_id,
			$id

		);

		$fields = apply_filters( 'chef_sections_setting_fields', $fields, $this, $prefix );

		return $fields;
	}



	/**
	 * Get the name (used in code ) for this section
	 * 
	 * @param  array $args
	 * @return string
	 */
	public function getName( $args ){

		if( isset( $args['name'] ) )
			return $args['name'];

		return sanitize_title( $this->title ).'-'.$this->id;
	}


	/**
	 * Get this sections's template slug
	 * 
	 * @return String
	 */
	public function getSlug(){

		global $post;
		return $post->post_name.'-'.sanitize_title( $this->title );

	}



}

