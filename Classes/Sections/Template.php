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
	 * The object being queried
	 * 
	 * @var string
	 */
	private $obj;


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
		$this->obj = $column;

		$section_template = Walker::getTemplateName( $column->section_id );
		$this->getFiles( $section_template );

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
		$this->obj = $section;

		$this->getFiles();

		return $this;
	}




	/**
	 * Include the found files
	 * 
	 * @return void
	 */
	public function display(){

		//check if the theme contains overwrites:
		$located = $this->checkTheme();

		//fall back on own templates:
		if( !$located ){

			$base = Url::path( 'plugin', 'chef-sections/templates', true );
			if( $this->type == 'section' ){
				$located = $base.'Sections/default.php';
			}else{
				$located .= $base.'Columns/'.$this->obj->type.'.php';
			}

		}

		//set vars:
		if( $this->type == 'column' ){
			$type = 'column';
			$column = $this->obj;
		}else{
			$type = 'section';
			$section = $this->obj;
		}

		add_action( 'chef_sections_before_'.$this->type.'_template', $this->obj );

			include( $located );

		add_action( 'chef_sections_after_'.$this->type.'_template', $this->obj );

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
		$templates = Sort::appendValues( $templates, '.php' );

		$located = locate_template( $templates );

		return $located; 
	}



	/**
	 * Return an array of files
	 * 
	 * @return array
	 */
	public function getFiles( $template_prefix = false ){

		if( $this->type == 'section' ){

			$base = 'views/sections/';

			if( $template_prefix )
				$base .= $section_template.'-';
			

			$array = array(
							$base.$this->obj->template.sanitize_title( $this->obj->title ),
							$base.$this->obj->template.$this->obj->view,
							$base.$this->obj->view
			);

		
		}else{

			$base = 'elements/columns/';

			if( $this->obj->type == 'collection' )
				$base = 'views/collections/';


			$array = array(
							$base.$template_prefix.'-'.$this->obj->id,
							$base.$template_prefix.'-'.$this->obj->type,
							$base.$this->obj->type
			);	
		}

		$this->files = $array;
		return $array;
	}


}