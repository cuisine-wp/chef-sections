<?php
namespace CuisineSections\Columns;

use Cuisine\Utilities\Url;
use Cuisine\Utilities\Sort;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\User;
use CuisineSections\Wrappers\Column;
use CuisineSections\Wrappers\Template;
use CuisineSections\Collections\SectionCollection;
use CuisineSections\Helpers\Column as ColumnHelper;
use CuisineSections\Contracts\Column as ColumnContract;

/**
 * Default column.
 * @package CuisineSections\Columns
 */
abstract class DefaultColumn implements ColumnContract{

	/**
	 * The unique number for this column, on this page
	 *
	 * @var Int
	 */
	public $id;

	/**
	 * The Id of this column prefixed by the section id
	 *
	 * @var String
	 */
	public $fullId;


	/**
	 * The post id this column is tied to
	 *
	 * @var Int
	 */
	public $post_id;


	/**
	 * The type of column
	 *
	 * @var String
	 */
	public $type;


	/**
	 * The section id for this column
	 *
	 * @var int
	 */
	public $section_id;

	/**
	 * Full section object
	 * 
	 * @var CuisineSections\Sections\BaseSection
	 */
	public $section;

	/**
	 * Column position within the section
	 *
	 * @var int;
	 */
	public $position;


	/**
	 * The properties of this column
	 *
	 * @var Array
	 */
	public $properties;



	/**
	 * Has lightbox
	 *
	 * @var Bool
	 */
	public $hasLightbox = true;



	/**
	 * Is this column in reference-mode?
	 *
	 * @var boolean
	 */
	public $referenceMode = false;


	/**
	 * Start the column and feed it the right ID's
	 *
	 * @param Int $id      Column ID
	 * @param Int $post_id \CuisineSections\Sections\Section
	 */
	function __construct( $id, $section, $props = array() ){

		global $post;

		$this->id = $id;

		$this->post_id = ( isset( $post ) ? $post->ID : null );

		//allow sections to overwrite post_id via the properties,
		//so we don't have to lean on a global:
		if( isset( $props['post_id'] ) ){
			$this->post_id = $props['post_id'];
		}


		//set the section variable:
		if( is_numeric( $section ) ){
			$collection = new SectionCollection( $this->post_id );
			$section = $collection->get( $section );
		}

		$this->section = $section;
		$this->section_id = $section->id;

		$this->fullId = $this->section_id.'_'.$this->id;


		//get the properties of this column:
		$this->getProperties();

		$this->position = $this->getField( 'position', $this->id );

	}



	/**
	 * Get the column properties from the database
	 *
	 * @return void
	 */
	private function getProperties(){


		$props = get_post_meta(
			$this->post_id,
			'_column_props_'.$this->fullId,
			true
		);



		$defaults = $this->getDefaultColumnArgs();
		$props = wp_parse_args( $props, $defaults );

		$this->properties = $props;

	}


	/*=============================================================*/
	/**             Template                                       */
	/*=============================================================*/

	/**
	 * Function runs before the template gets fired
	 *
	 * @return void
	 */
	public function beforeTemplate(){
		//defaults to empty
	}

	/**
	 * Function runs after the template gets fired
	 *
	 * @return void
	 */
	public function afterTemplate(){
		//defaults to empty
	}

	/*=============================================================*/
	/**             Saving                                         */
	/*=============================================================*/


	/**
	 * Save the properties of this column
	 *
	 * @return bool
	 */
	public function saveProperties(){

		$props = $_POST['properties'];
		$props = $this->sanitizeProperties( $props );
		$props = apply_filters( 'cuisine_sections_save_column_properties', $props, $this );

		do_action( 'cuisine_sections_before_column_save', $this );

		$saved = update_post_meta(
			$this->post_id,
			'_column_props_'.$this->fullId,
			$props
		);

		do_action( 'cuisine_sections_after_column_save', $this );

		//set the new properties in this class
		$this->properties = $props;
		return $saved;
	}

	/**
	 * Creates the right values to save
	 * 
	 * @return array
	 */
	public function sanitizeProperties( $props )
	{
		return $props;
	}


	/*=============================================================*/
	/**             Fields                                         */
	/*=============================================================*/

	/**
	 * Returns the fields for this column
	 * 
	 * @return Array
	 */
	public function getFields()
	{
		return [];
	}


	/**
	 * Returns the value of a field in this column
	 *
	 * @param  string $name
	 * @param  string $default (optional)
	 * @return string / bool (returns false if this content does not exist )
	 */
	public function getField( $name, $default = null ){

		if( !isset( $this->properties[ $name ] ) ){

			if( $default !== null )
				return $default;


			return false;

		}

		return $this->properties[$name];
	}


	/**
	 * Simple echo function for the getField method
	 *
	 * @param  string $name
	 * @return string ( html, echoed )
	 */
	public function theField( $name, $default = null ){

		if( $this->getField( $name, $default ) )
			echo $this->getField( $name, $default );
	}


	/**
	 * Returns the title field
	 * 
	 * @param  String       $name  
	 * @param  String 		$class 
	 * @return String
	 */
	public function getTitle( $name = 'title', $class = 'column-title' )
	{
		$title = $this->getField( $name, false );
		if( $title && isset( $title['text'] ) && $title['text'] != '' ){

			//set a default type
			if( !isset( $title['type'] ) ) $title['type'] = 'h2';

			$string = '<'.$title['type'].' class="'.$class.'" itemprop="name">';
				$string .= esc_html( $title['text'] ); 
			$string .= '</'.$title['type'].'>';

			return $string;
		}

		return null;
	}

	/**
	l * Echoes the output of getTitle
	 * 
	 * @param  String       $name  
	 * @param  String 		$class 
	 * @return String           
	 */
	public function theTitle( $name = 'title', $class = 'column-title' )
	{
		$title = $this->getTitle( $name, $class );
		if( $title !== null )
			echo $title;
	}


	/*=============================================================*/
	/**             UI                                             */
	/*=============================================================*/


	/**
	 * Generate the preview for the backend
	 *
 	 * @param  bool $ref Build in reference-mode
	 * @return String (html, echoed)
	 */
	public function build( $ref = false ){

		$this->referenceMode = $ref;

		echo '<div class="column '.esc_attr( $this->type ).'" ';
		echo $this->buildIds().'>';

			$this->buildControls();

			echo '<div class="preview-col">';
				$this->buildPreview();
			echo '</div>';

			$this->buildBottomControls();

			if( $this->hasLightbox ){

				echo '<div class="lightbox lightbox-'.esc_attr( $this->type ).'">';

					$this->buildLightbox();

				echo '</div>';

			}

			Field::hidden(
				'position',
				array(
					'defaultValue' => $this->position,
					'class' => 'column-position'
				)
			)->render();

			echo '<div class="loader"><span class="spinner"></span></div>';
		echo '</div>';
	}

	/**
	 * Builds the column preview
	 *
	 * @return void
	 */
	public function buildPreview(){
		//empty, every column needs to do this on there own.
	}

	/**
	 * Builds the column lightbox
	 * 
	 * @return void
	 */
	public function buildLightbox(){

		$fields = $this->getFields();

		echo '<div class="main-content">';
		
			foreach( $fields as $field ){

				$field->render();

				if( method_exists( $field, 'renderTemplate' ) )
					echo $field->renderTemplate();

			}

		echo '</div>';
		echo '<div class="side-content">';
			
			$this->saveButton();

		echo '</div>';
	}



	/**
	 * Build the top controls of a column
	 *
	 * @return string ( html, echoed )
	 */
	private function buildControls(){

		//create key - label pairs for this dropdown:

		$name = '_column_type_'.$this->fullId;
		$types = $this->getTypes();

		if( $this->referenceMode )
			$name = 'reference_'.$this->fullId;


		if( sizeof( $types ) > 1 ){
			
			$typeSelector = Field::select(
				$name,
				'',
				$types,
				array(
					'defaultValue' => $this->type
				)
			);
			
			$class = 'column-controls column-choices-available';

		}else{
			$class = 'column-controls';
			$key = array_keys( $types );
			$typeSelector = Field::hidden( 
				$name, 
				[ 
					'defaultValue' => $this->type, 
					'class' => 'type-select'
				]
			);
		}

		echo '<div class="'.$class.'">';

			//render the dropdown:
			$typeSelector->render();
			echo '<h3 class="column-type">'.$types[ $this->type ].'</h3>';

			//sorter
			echo '<span class="sort dashicons dashicons-leftright"></span>';

		echo '</div>';

	}


	/**
	 * Build the edit button
	 *
	 * @return string (html, echoed)
	 */
	protected function buildBottomControls(){
		echo '<div class="btn-row">';

			$class = 'edit-btn section-btn';
			if( !$this->hasLightbox )
				$class .= ' no-lightbox';

			echo '<button class="'.esc_attr( $class ).'" id="lightbox-btn">';
				echo '<span class="dashicons dashicons-edit"></span>';
				_e( 'Edit', 'cuisinesections' );
			echo '</button>';

			$this->buildTemplateSnitch();

		echo '</div>';
	}


	/**
	 * Create the save button in the lightbox
	 *
	 * @return string (html, echoed )
	 */
	public function saveButton(){

		//get the properties of this column:
		if( !isset( $this->properties['buttonText'] ) )
			$this->getProperties();

		echo '<a class="lightbox-modal-close" href="#">';
			echo '<span class="media-modal-icon"></span>';
		echo '</a>';

		echo '<div class="save-btn-container">';

			echo '<span class="spinner"></span>';

			echo '<button id="save-column" class="save-btn section-btn">';
				echo esc_html( $this->properties['buttonText'] );
			echo '</button>';

		echo '</div>';

	}


	/**
	 * Add the Id's for javascript use
	 *
	 * @return string
	 */
	private function buildIds(){

		$string = 'data-id="'.$this->fullId.'" ';
		$string .= 'data-column_id="'.$this->id.'" ';
		$string .= 'data-section_id="'.$this->section_id.'" ';
		$string .= 'data-post_id="'.$this->post_id.'" ';

		return $string;
	}


	/**
	 * Generate the templates for this section
	 *
	 * @return string ( html, echoed )
	 */
	protected function buildTemplateSnitch(){

		$templates = Template::column( $this )->getHierarchy();

		if( User::hasRole( 'administrator' ) ){

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
	}


	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/


	


	/**
	 * Get the default arguments for this column
	 *
	 * @filter 'cuisine_sections_default_column_args'
	 * @return array
	 */
	private function getDefaultColumnArgs(){

		$args = array(

				'hasLightbox'	=>  true,
				'buttonText'	=> __( 'Save Column', 'cuisinesections' )
		);

		$args = apply_filters( 'cuisine_sections_default_column_args', $args );

		return $args;

	}


	/**
	 * Returns the possible column types available for this column
	 * 
	 * @return Array
	 */
	public function getTypes()
	{
		$keys = array_keys( ColumnHelper::getAvailableTypes() );
		$labels = Sort::pluck( ColumnHelper::getAvailableTypes(), 'name' );

		$response = [];
		$types = array_combine( $keys, $labels );
		$allowed = $this->section->allowedColumns;
		$allowed = apply_filters( 'cuisine_sections_default_allowed_columns', $allowed, $this, $this->section );

		foreach( $types as $key => $type ){
			if( in_array( $key, $allowed ) )
				$response[ $key ] = $type;
		}

		return $response;

	}


}