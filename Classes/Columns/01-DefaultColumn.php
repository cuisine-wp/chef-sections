<?php
namespace ChefSections\Columns;

use Cuisine\Utilities\Url;
use Cuisine\Wrappers\Field;
use ChefSections\Wrappers\Column;
use ChefSections\Wrappers\Template;

/**
 * Default column.
 * @package ChefSections\Columns
 */
class DefaultColumn {

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
	private $post_id;


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
	 * The properties of this column
	 * 
	 * @var Array
	 */
	private $properties;



	/**
	 * Has lightbox
	 *
	 * @var Bool
	 */
	public $hasLightbox = true;


	/**
	 * Start the column and feed it the right ID's
	 * 
	 * @param Int $id      Column ID
	 * @param Int $post_id \ChefSections\Sections\Section
	 */
	function __construct( $id, $section_id, $props = array() ){

		global $post;

		$this->id = $id;

		$this->section_id = $section_id;

		$this->fullId = $this->section_id.'_'.$this->id;

		$this->post_id = ( isset( $post ) ? $post->ID : null );

		//get the properties of this column:
		$this->getProperties();

	}



	/**
	 * Get the column properties from the database
	 * 
	 * @return void
	 */
	private function getProperties(){

		$previewData = get_post_meta( 
			$this->post_id, 
			'_column_preview_'.$this->fullId, 
			true
		);

		$props = get_post_meta( 
			$this->post_id, 
			'_column_props_'.$this->fullId, 
			true
		);


		$this->previewData = $previewData;


		$defaults = $this->getDefaultColumnArgs();
		$props = wp_parse_args( $props, $defaults );

		$this->properties = $props;

	}


	/*=============================================================*/
	/**             Backend                                        */
	/*=============================================================*/


	/**
	 * Save the properties of this column
	 * 
	 * @return bool
	 */
	public function saveProperties(){

		$props = $_POST['properties'];
		$saved = update_post_meta( 
			$this->post_id, 
			'_column_props_'.$this->fullId, 
			$props
		);

		//set the new properties in this class
		$this->properties = $props;
		return $saved;
	}


	/**
	 * Generate the preview for the backend
	 * 
	 * @return String (html, echoed)
	 */
	public function build(){

		echo '<div class="column '.$this->type.'" ';
		echo $this->buildIds().'>';

			$this->buildControls();

			echo '<div class="preview-col">';
				$this->buildPreview();
			echo '</div>';

			$this->buildBottomControls();

			if( $this->hasLightbox ){
				echo '<div class="lightbox lightbox-'.$this->type.'">';
					$this->buildLightbox();
				echo '</div>';
			}

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
	 * Build the top controls of a column
	 * 
	 * @return string ( html, echoed )
	 */
	private function buildControls(){

		//create key - label pairs for this dropdown:
		$keys = array_keys( Column::getAvailableTypes() );
		$labels = array_column( Column::getAvailableTypes(), 'name' );

		$types = array_combine( $keys, $labels );

		$typeSelector = Field::select( 
			'_column_type_'.$this->fullId, 
			'',
			$types,
			array(
				'defaultValue' => $this->type
			)
		);

		echo '<div class="column-controls">';
			$typeSelector->render();
			$this->buildTemplateSnitch();
		echo '</div>';

	}


	/**
	 * Build the edit button
	 * 
	 * @return string (html, echoed)
	 */
	private function buildBottomControls(){
		echo '<div class="btn-row">';

			$class = 'edit-btn section-btn';
			if( !$this->hasLightbox )
				$class .= ' no-lightbox';

			echo '<button class="'.$class.'" id="lightbox-btn">'.__( 'Bewerken', 'chefsections' ).'</button>';

		echo '</div>';
	}


	/**
	 * Create the save button in the lightbox
	 * 
	 * @return string (html, echoed )
	 */
	public function saveButton(){

		echo '<a class="lightbox-modal-close" href="#">';
			echo '<span class="media-modal-icon"></span>';
		echo '</a>';

		echo '<div class="save-btn-container">';

			echo '<span class="spinner"></span>';
			echo '<button id="save-column" class="save-btn section-btn">'.$this->properties['buttonText'].'</button>';

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
	private function buildTemplateSnitch(){

		$templates = Template::column( $this )->files;
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




	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/


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
	public function theField( $name ){

		if( $this->getField( $name ) )
			echo $this->getField( $name );
	}


	/**
	 * Get the default arguments for this column
	 *
	 * @filter 'chef_sections_default_column_args'
	 * @return array
	 */
	private function getDefaultColumnArgs(){

		$args = array(

				'hasLightbox'	=>  true,
				'buttonText'	=> __( 'Kolom opslaan', 'chef_sections' )
		);

		$args = apply_filters( 'chef_sections_default_column_args', $args );

		return $args;

	}



}