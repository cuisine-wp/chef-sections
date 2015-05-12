<?php
namespace ChefSections\Columns;

use Cuisine\Utilities\Url;
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
	private $fullId;


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
	 *
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
	function __construct( $id, $section, $props = array() ){

		$this->id = $id;

		$this->section = $section;

		$this->post_id = $section->post_id;

		$this->fullId = $section->id.'_'.$this->id;

		//get the properties of this column:
		$this->getProperties();

		$this->hasLightbox = $this->properties['hasLightbox'];

	}


	/**
	 * Get the column properties from the database
	 * 
	 * @return void
	 */
	function getProperties(){

		$type = get_post_meta( 
			$this->post_id, 
			'_column_type_'.$this->fullId, 
			true
		);

		$previewData = get_post_meta( 
			$this->post_id, 
			'_column_preview_'.$this->fullId, 
			true
		);

		$props = get_post_meta( 
			$this->post_id, 
			'_column_props_'.$this->id, 
			true
		);


		$this->type = $type;
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
	 * @return String (html)
	 */
	function buildPreview(){

		echo '<div class="column '.$this->type.'">';
			echo '...Column inhoud...';

		echo '</div>';

	}


	/*=============================================================*/
	/**             Frontend                                       */
	/*=============================================================*/


	/**
	 * Render this column
	 * 
	 * @return void
	 */
	function render(){

		Template::column( $this, $this->section )->display();

	}



	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/

	private function getDefaultColumnArgs(){

		$args = array(

				'hasLightbox'	=>  true,
				'buttonText'	=> __( 'Bewerken', 'chef_sections' )
		);

		$args = apply_filters( 'chef_sections_default_column_args', $args );

		return $args;

	}



}