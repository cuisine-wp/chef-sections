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
	private $id;

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
	 * The parent section for this column
	 * 
	 * @var ChefSections\Sections\Section
	 */
	public $section;


	/**
	 * The properties of this column
	 * 
	 * @var Array
	 */
	private $properties;


	/**
	 * The preview data of this column
	 *;0
	 * @var Array
	 */


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
	 * Generate the preview for the backend
	 * 
	 * @return String (html, echoed)
	 */
	public function build(){

		echo '<div class="column '.$this->type.'" ';
		echo $this->buildIds().'>';

			$this->buildControls();

			$this->buildPreview();


			cuisine_dump( $this->hasLightbox );

			if( $this->hasLightbox ){
				echo '<div class="lightbox lightbox-'.$this->type.'">';
					$this->buildLightbox();
				echo '</div>';
			}

		echo '</div>';

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

		$typeSelector->render();

	}

	/**
	 * Create the preview image or text for this column
	 * 
	 * @return string (html, echoed)
	 */
	private function buildPreview(){
		
		echo '<p> '.$this->type.' -- preview </p>';


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
		$string .= 'data-section_id="'.$this->section_id.'" ';
		$string .= 'data-post_id="'.$this->post_id.'" ';

		return $string;
	}




	/*=============================================================*/
	/**             Frontend                                       */
	/*=============================================================*/


	/**
	 * Render this column
	 * 
	 * @return void
	 */
	public function render(){

		Template::column( $this, $this->section )->display();

	}



	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/


	/**
	 * Returns the value of a field in this column
	 * 
	 * @param  string $name
	 * @return string / bool (returns false if this content does not exist )
	 */
	public function getField( $name ){

		if( !isset( $this->properties[ $name ] ) )
			return false;

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


	private function getColumnTypes(){




	}



}