<?php
namespace ChefSections\Front;

use \ChefSections\Wrappers\Walker;
use \ChefSections\Wrappers\Column;
use \ChefSections\Wrappers\SectionsBuilder;
use \Cuisine\Utilities\Url;
use \Cuisine\Utilities\Sort;

/**
 * The Template class locates templates
 * @package ChefSections\Front
 */
class TemplateFinder {

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
	 * @return \ChefSections\Front\TemplateFinder ( chainable )
	 */
	public function column( $column ){

		$this->type = 'column';
		$this->obj = $column;

		$section_template = SectionsBuilder::getTemplateName( $column->section_id );
		$this->getFiles( $section_template );

		return $this;
	}

	/**
	 * Get the template for a single collection block
	 * 
	 * @param  \ChefSections\Columns\Column 	$column
	 * @return \ChefSections\Front\TemplateFinder ( chainable )
	 */
	public function block( $column ){

		$this->type = 'block';
		$this->obj = $column;

		$section_template = SectionsBuilder::getTemplateName( $column->section_id );
		$this->getFiles( $section_template );

		return $this;
	}


	/**
	 * Get the template for a section
	 * 
	 * @param  \ChefSections\Sections\Section 	$section
	 * @return \ChefSections\Front\TemplateFinder ( chainable )
	 */
	public function section( $section ){

		$this->type = 'section';
		$this->obj = $section;

		$this->getFiles();

		return $this;
	}


	/**
	 * Get a template for a regular element
	 * 
	 * @param  string $name name of the template
	 * @return ChefSections\Front\TemplateFinderFinder
	 */
	public function element( $name ){

		$this->type = 'element';
		$this->obj = $name;

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
		if( !$located )
			$located = $this->getDefault();


		//set vars:
		if( $this->type == 'section' ){
			$type = 'section';
			$section = $this->obj;	
		}else{
			$type = 'column';
			$column = $this->obj;
		}

		add_action( 'chef_sections_before_'.$this->type.'_template', $this->obj );

			include( $located );

		add_action( 'chef_sections_after_'.$this->type.'_template', $this->obj );

	}

	/**
	 * Get the default template:
	 * 
	 * @return void
	 */
	private function getDefault(){

		$base = Url::path( 'plugin', 'chef-sections/Templates', true );

		if( $this->type == 'column' ){

			$types = Column::getAvailableTypes();
			$col = $types[ $this->obj->type ];

			if( !isset( $col['template'] ) || $col['template'] == '' ){
				$default = $base.'Columns/'.$this->obj->type.'.php';

			}else{
				$default = $col['template'];

			}

		}else if( $this->type == 'section' ){
			$default = $base.'Sections/default.php';
	
		}else if( $this->type == 'block' ){
			$default = $base.'Columns/collection-block.php';
		
		}else if( $this->type == 'element' ){
			$default = $base.'Elements/'.$this->obj.'.php';

		}

		return $default;
	}


	/**
	 * Check the theme for these files
	 * 
	 * @return located
	 */
	private function checkTheme(){

		$templates = Sort::appendValues( $this->files, '.php' );
		$located = locate_template( $templates );

		return $located; 
	}



	/**
	 * Return an array of files
	 * 
	 * @return array
	 */
	public function getFiles( $template_prefix = false ){


		switch( $this->type ){

			case 'section':

				$base = 'views/sections/';

				if( $template_prefix )
					$base .= $template_prefix.'-';
				

				$array = array(
								$base.$this->obj->template.sanitize_title( $this->obj->title ),
								$base.$this->obj->template.$this->obj->view,
								$base.$this->obj->view,
								$base.'default'
				);

			break;

			case 'column':

				$base = 'elements/columns/';

				if( $this->obj->type == 'collection' )
					$base = 'views/collections/';


				$array = array(
								$base.$template_prefix.'-'.$this->obj->id,
								$base.$template_prefix.'-'.$this->obj->type,
								$base.$this->obj->type
				);

			break;


			case 'block':

				$base = 'elements/blocks/';

				$array = array(
							$base.$template_prefix.'-'.get_post_type(),
							$base.get_post_type()
				);
			break;

			case 'element':

				$base = 'elements/';

				$array = array(
							$base.$this->obj
				);

			break;


		}
		

		$this->files = $array;
		return $array;
	}


}