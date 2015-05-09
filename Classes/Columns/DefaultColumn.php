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
	 * Preview-data
	 *
	 * @var String
	 */
	private $preview;



	/**
	 * Start the column and feed it the right ID's
	 * 
	 * @param Int $id      Column ID
	 * @param Int $post_id Post ID
	 */
	function __construct( $id, $post_id = null ){

		$this->id = $id;

		if( $post_id == null && isset( $_GLOBALS['post'] ) )
			$this->id = $_GLOBALS['post']->ID;

		//get the properties of this column:
		$this->getProperties();

	}


	/**
	 * Get the column properties from the database
	 * 
	 * @return void
	 */
	function getProperties(){

		$column = get_post_meta( $this->post_id, '_column_'.$this->id, true );
		$this->properties = $column;

	}



	/**
	 * Locate a template
	 * 
	 * @return void
	 */
	function getTemplate(){

		$templatePath = 'views/';
		$defaultPath = Url::path( 'chef-sections', 'templates/columns', true );
		
		$templateName = 'column-'.$this->type;
		$specificTemplateName = 'column-'.$this->id;

		// Look within passed path within the theme - this is priority
		$template = \locate_template(
			array(
				\trailingslashit( $templatePath ) . $specificTemplateName,
				\trailingslashit( $templatePath ) . $templateName,
				$templateName
			)
		);
				
				
		// Get default template
		if ( ! $template )
			$template = $defaultPath . $templateName;
				
		// Return what we found
		$template = apply_filters( 'chef_sections_column_template', $template, $this );
		

		add_action( 'chef_sections_before_column_template', $templateName );
		add_action( 'chef_sections_before_column_template_'.$this->type, $templateName );

			include( $located );

		add_action( 'chef_sections_after_column_template', $templateName );
		add_action( 'chef_sections_after_column_template_'.$this->type, $templateName );

	}


}