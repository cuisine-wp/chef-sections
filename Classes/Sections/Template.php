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

		$this->getFiles( $column );

		return $this;
	}



	/**
	 * Get the template for a section
	 * 
	 * @param  \ChefSections\Sections\Section 	$section
	 * @return \ChefSections\View\Template ( chainable )
	 */
	public function section( $section ){

		$this->getFiles( $section );

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
	public function getFiles( $obj ){

		if( !isset( $obj->fullId ) ){

			$base = 'views/sections/';

			$array = array(
							$base.'section_'.$obj->id,
							$base.'section_'.sanitize_title( $obj->title ),
							$base.'section_'.$obj->view
			);


		
		}else{

			$base = 'elements/columns/';

			if( $obj->type == 'collection' )
				$base = 'views/collections/';


			$array = array(
							$base.'column_'.$obj->id,
							$base.'column_'.$obj->type
			);	
		}

		$this->files = $array;


	}


}