<?php
namespace ChefSections\View;

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
	private $files;


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
	public function column( $column, $section ){



		return $this;
	}



	/**
	 * Get the template for a section
	 * 
	 * @param  \ChefSections\Sections\Section 	$section
	 * @return \ChefSections\View\Template ( chainable )
	 */
	public function section( $section ){



		return $this;
	}



	/**
	 * Include the found files
	 * 
	 * @return void
	 */
	public function display(){

//		add_action( 'chef_sections_before_'.$this->type.'_template', $this->templateName );

//			include( $located );

//		add_action( 'chef_sections_after_'.$this->type.'_template', $this->templateName );


	}



	/**
	 * Return an array of files
	 * 
	 * @return array
	 */
	public function getFiles(){

	}


}