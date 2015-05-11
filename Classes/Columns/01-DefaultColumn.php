<?php
namespace ChefSections\Columns;

use Cuisine\Utilities\Url;

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
	private $type;


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
	function getPreview(){

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

		$located = $this->locateTemplate();

		add_action( 'chef_sections_before_column_template', $templateName );
		add_action( 'chef_sections_before_column_template_'.$this->type, $templateName );

			include( $located );

		add_action( 'chef_sections_after_column_template', $templateName );
		add_action( 'chef_sections_after_column_template_'.$this->type, $templateName );

	}


	/**
	 * Locate a template
	 * 
	 * @return String (url)
	 */
	function locateTemplate(){

		$templatePath = 'views/';
		$defaultPath = Url::path( 'chef-sections', 'templates/columns', true );
		
		$templateName = 'column-'.$this->type;
		$specificTemplateName = 'column-'.$this->id;

		// Look within passed path within the theme - this is priority
		$path = \trailingslashit( $templatePath );

		$template = \locate_template(
			array(
				
				$path . 'column-'.$this->section->getSlug().'-'.$this->fullId,
				$path . 'column-'.$this->section->getSlug().'-'.$this->type,
				$path . $templateName,
				$templateName
			)
		);
				
				
		// Get default template
		if ( ! $template )
			$template = $defaultPath . $templateName;
				
		// Return what we found
		$template = \apply_filters( 'chef_sections_column_template', $template, $this );
		return $template;
	}


}