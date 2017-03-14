<?php

namespace ChefSections\Sections;


use Cuisine\Wrappers\User;
use Cuisine\Wrappers\Field;
use Cuisine\Utilities\Sort;
use ChefSections\Wrappers\Column;
use ChefSections\Wrappers\Template;
use ChefSections\Wrappers\SectionsBuilder;
use ChefSections\Helpers\Section as SectionHelper;


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
	 * Container id
	 * 
	 * @var integer
	 */
	public $container_id;

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
	 * Boolean to detirmine if this is a reference section
	 *
	 * @possible values: section - reference - stencil - layout
	 * @var boolean
	 */
	public $type = 'section';


	/**
	 * The ID for the template, if this is a template-based section
	 * 
	 * @var integer
	 */
	public $template_id = 0;


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

		//check if this is a section template
		$this->template_id = ( isset( $args['template_id'] ) ? $args['template_id'] : false );

		//check if this section is part of a container:
		$this->container_id = ( isset( $args['container_id'] ) ? $args['container_id'] : null );

		if( !is_array( $args['title'] ) )
			$args['title'] = [ 'text' => $args['title'], 'type' => 'h2' ];

		//fill in the basics
		$this->position 		= $args['position'];
		$this->title 			= $args['title']['text'];
		$this->view 			= $args['view'];
		$this->name 			= $this->getName( $args );
		$this->properties 		= $args;
		$this->columns 			= $this->getColumns( $args['columns'] );

		//title & container settings
		if( strtolower( $this->title ) == 'sectie titel' )
			$this->title = '';

		$this->hide_title 		= ( $this->title == '' ? true : false );

		$this->hide_container 	= ( isset( $args['hide_container'] ) ? $args['hide_container'] : 'false' );


		//template settings
		$name = 'page-';
		if( isset( $post->post_name ) )
			$name = $post->post_name.'-';

		$this->template = $name;

		//check for section-types when dealing with templates:
		if( $post->post_type == 'section-template' && $this->type == 'section' ){

			$_type = get_post_meta( $this->post_id, 'type', true );
			if( $_type )
				$this->type = $_type;

		}

	}
	
	/*=============================================================*/
	/**             Frontend                                       */
	/*=============================================================*/

	/**
	 * This function runs just before the template
	 * 
	 * @return void
	 */
	public function beforeTemplate(){

		//add a hook
		add_action( 'section_before_template', $this );

		$schema = $this->getSchema();
		$class = $this->getClass();
		
		//base html of a section-starting div
		$html = '<div '.$schema.' class="'.esc_attr( $class ).'"';

		//so people can add data-properties and other stuff
		$html = apply_filters( 'cuisine_section_opening_div', $html );

		echo $html.'>';

		do_action( 'chef_section_before_section_content', $this );

	}

	/**
	 * This function runs just after the template
	 * 
	 * @return void
	 */
	public function afterTemplate(){

			do_action( 'chef_section_after_section_content', $this );

		echo '</div>';

		//do something after template
		do_action( 'section_after_template', $this );

	}

	/**
	 * Get the class for this section
	 * 
	 * @return string
	 */
	public function getClass(){

		$class = 'section';
		$class .= ' '.$this->name;


		$classes = $this->getProperty( 'classes' );
		if( $classes ){

			if( is_array( $classes ) )
				$classes = explode( ' ', $classes );

			$class .= ' '.$classes;
		}

		$class = apply_filters( 'chef_section_classes', $class, $this );

		return $class;

	}

	/**
	 * Returns the schema.org declarations for this section
	 * 
	 * @return string
	 */
	private function getSchema(){

		$schema = 'itemscope ';
		$schema .= 'itemtype="http://schema.org/Collection"';

		return $schema;
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

			$class = 'section-wrapper ui-state-default section-'.$this->id;

			echo '<div class="'.esc_attr( $class ).'" ';
				echo 'id="'.esc_attr( $this->id ).'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="section-columns '.esc_attr( $this->view ).'">';
	

				foreach( $this->columns as $column ){
					
					if( $column )
						echo $column->build();
	
				}


				echo '<div class="clearfix"></div>';
				echo '</div>';

				$this->bottomControls();
				$this->buildSettingsPanels();
				$this->buildHiddenFields();
			
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}
	}



	/**
	 * Build the top of this Section
	 * 
	 * 
	 * @return String ( html, echoed )
	 */
	public function buildControls(){

		echo '<div class="section-controls">';

			//first the title:
			$title = ( $this->hide_title ? [ 'text' => '', 'type' => 'h2' ] : $this->getProperty( 'title' ) );

			Field::title(
				'section['.$this->id.'][title]',
				__( 'Titel', 'chefsections' ),
				array(
					'placeholder'	=> __( 'Section title', 'chefsections' ),
					'label'			=> false,
					'defaultValue'	=> $title
				)
			)->render();


			//add the top buttons for panels:
			echo '<div class="buttons-wrapper">';

				$buttons = apply_filters( 'chef_sections_panel_buttons', array() );

				foreach( $buttons as $button ){

					echo '<span class="button section-'.esc_attr( $button['name'] ).'-btn with-tooltip" data-id="'.esc_attr( $button['name'] ).'">';
						echo '<span class="dashicons '.esc_attr( $button['icon'] ).'"></span>';
						echo '<span class="tooltip">'.esc_attr( $button['label'] ).'</span>';
					echo '</span>';

				}

			echo '</div>';
			

			//view-switcher:
			$types = SectionHelper::viewTypes();

			Field::radio(
				'section['.$this->id.'][view]',
				'Weergave',
				$types,
				array(
					'defaultValue' => $this->view
				)
			)->render();

			//sorting pin:
			echo '<span class="dashicons dashicons-sort pin"></span>';


		echo '</div>';
	
		echo '<div class="clearfix"></div>';
	}


	/**
	 * Create the controls on the bottom
	 * 
	 * @return string (html, echoed)
	 */
	public function bottomControls(){

		echo '<div class="section-footer">';
			echo '<p class="delete-section">';
				echo '<span class="dashicons dashicons-trash"></span>';
			echo __( 'Delete', 'chefsections' ).'</p>';

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
	public function buildIds(){

		echo 'data-section_id="'.esc_attr( $this->id ).'" ';
		echo 'data-post_id="'.esc_attr( $this->post_id ).'"';

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
				echo '<span class="copy">echo Loop::section( '.esc_attr( $this->post_id ).', '.esc_attr( $this->id ).' );</span>';

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

					echo '<p>'.esc_html( $template ).'</p>';

				}

			echo '</span>';
		echo '</span>';
	}


	/**
	 * Create the settings panel with it's fields
	 * 
	 * @return string (html, echoed)
	 */
	public function buildSettingsPanels(){

		echo '<div class="section-setting-panels">';

			do_action( 'chef_section_setting_panels', $this );

		echo '</div>';
	}

	/**
	 * Render all hidden fields for this section
	 * 
	 * @return void
	 */
	public function buildHiddenFields(){

		$prefix = 'section['.$this->id.']';
		Field::hidden(
			$prefix.'[position]',
			array(
				'defaultValue' => $this->position,
				'class' => array( 'field', 'input-field', 'section-position' )
			)
		)->render();

		Field::hidden(
			$prefix.'[post_id]',
			array(
				'defaultValue' => $this->post_id
			)
		)->render();

		Field::hidden(
			$prefix.'[id]',
			array(
				'defaultValue' => $this->id
			)
		)->render();

		Field::hidden(
		
			$prefix.'[type]',
			array(
				'defaultValue' => $this->type
			)
		
		)->render();


		if( $this->template_id !== 0 ){

			Field::hidden(
			
				$prefix.'[template_id]',
				array(
					'defaultValue' => $this->template_id
				)
		
			)->render();

		}
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
	public function getColumns( $columns ){

		$arr = array();

		if( !empty( $columns ) ){

			//populate the columns array with actual column objects
			foreach( $columns as $col_key => $type ){
	
				$props = array(
					'post_id'	=>	 $this->post_id
				);

				if( Column::typeExists( $type ) ){

					$arr[] = Column::$type( $col_key, $this->id, $props );
	
				}else{
					$arr[] = false;
				
				}
			}

			//sort by column position:
			$arr = Sort::byField( $arr, 'position', 'ASC' );

		}

		return $arr;

	}


	/**
	 * Get the name (used in code ) for this section
	 * 
	 * @param  array $args
	 * @return string
	 */
	public function getName( $args ){

		if( isset( $args['name'] ) && $args['name'] != '' )
			return $args['name'];

		if( isset( $args['title']['text'] ) && $args['title']['text'] != '' )
			return sanitize_title( $this->title ).'-'.$this->id;

		return $this->post_id.'-'.$this->id;
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


	/**
	 * Returns the title field
	 * 
	 * @param  String       $name  
	 * @param  String 		$class 
	 * @return String
	 */
	public function getTitle()
	{
		$title = $this->getProperty( 'title', false );
		if( $title && isset( $title['text'] ) && $title['text'] != '' ){

			$string = '<'.$title['type'].' class="section-title" itemprop="name">';
				$string .= esc_html( $title['text'] ); 
			$string .= '</'.$title['type'].'>';

			return $string;
		}

		return null;
	}

	/**
	 * Echoes the output of getTitle
	 * 
	 * @param  String       $name  
	 * @param  String 		$class 
	 * @return String           
	 */
	public function theTitle()
	{
		$title = $this->getTitle();
		if( $title !== null )
			echo $title;
	}


	/**
	 * Checks to see if a section is containered
	 * 
	 * @return boolean
	 */
	public function isContainered()
	{
		return ( !is_null( $this->container_id ) );
	}

}

