<?php
namespace ChefSections\View;

use \ChefSections\Wrappers\Walker;
use \Cuisine\Utilities\Url;
use \Cuisine\Utilities\Sort;

/**
 * The Template class locates templates
 * @package ChefSections\View
 */
class Template {

	/**
	 * Array of files to look for
	 * 
	 * @var array
	 */
	public $files;


	/**
	 * The first found file
	 * 
	 * @var string
	 */
	private $located;


	/**
	 * String of the name of the template found
	 * 
	 * @var string
	 */
	private $templateName;


	/**
	 * Template type ( column or section )
	 * 
	 * @var string
	 */
	private $type;




	/**
	 * Get the template for a column
	 * 
	 * @param  \ChefSections\Columns\Column 	$column
	 * @param  \ChefSections\Sections\Section 	$section
	 * @return \ChefSections\View\Template ( chainable )
	 */
	public function column( $column ){

		$this->type = 'column';
		$section_template = Walker::getTemplateName( $column->section_id );
		$this->getFiles( $column, $section_template );

		return $this;
	}



	/**
	 * Get the template for a section
	 * 
	 * @param  \ChefSections\Sections\Section 	$section
	 * @return \ChefSections\View\Template ( chainable )
	 */
	public function section( $section ){

		$this->type = 'section';
		$this->getFiles( $section );

		return $this;
	}




	/**
	 * Include the found files
	 * 
	 * @return void
	 */
	public function display(){

		//check if the theme contains overwrites:
		$this->checkTheme();


		//add_action( 'chef_sections_before_'.$this->type.'_template', $this->templateName );

		//	include( $located );

		//add_action( 'chef_sections_after_'.$this->type.'_template', $this->templateName );

	}



	/**
	 * Check the theme for these files
	 * 
	 * @return located
	 */
	private function checkTheme(){

		//the root path of our theme:
		$base = Url::path( 'theme' );
		$templates = Sort::prependValues( $this->files, $base );

		cuisine_dump( $templates );
		$located = false;


		return $located; 
	}



	/**
	 * Return an array of files
	 * 
	 * @return array
	 */
	public function getFiles( $obj, $template_prefix = false ){

		if( $this->type == 'section' ){

			$base = 'views/sections/';

			if( $template_prefix )
				$base .= $section_template.'-';
			

			$array = array(
							$base.$obj->template.$obj->id,
							$base.$obj->template.sanitize_title( $obj->title ),
							$base.$obj->template.$obj->view,
							$base.$obj->view
			);

		
		}else{

			$base = 'elements/columns/';

			if( $obj->type == 'collection' )
				$base = 'views/collections/';

			if( $template_prefix )
				$base .= $template_prefix.'-';


			$array = array(
							$base.$obj->id,
							$base.$obj->type
			);	
		}

		$this->files = $array;


	}


}