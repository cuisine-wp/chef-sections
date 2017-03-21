<?php
namespace ChefSections\Front;

use \ChefSections\Wrappers\Walker;
use \ChefSections\Wrappers\Column;
use \ChefSections\Wrappers\SectionsBuilder;
use \Cuisine\Utilities\Url;
use \Cuisine\Utilities\Sort;
use \Cuisine\Utilities\Session;

use \ChefSections\Templates\ContentSectionTemplate;

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
	public $obj;


	/**
	 * String of the name of the template found
	 * 
	 * @var string
	 */
	protected $templateName;


	/**
	 * Template type ( column or section )
	 * 
	 * @var string
	 */
	public $type;




	/**
	 * Get the template for a column
	 * 
	 * @param  \ChefSections\Columns\Column 	$column
	 * @return \ChefSections\Front\TemplateFinder ( chainable )
	 */
	public function column( $column ){

		$this->type = 'column';
		$this->obj = $column;

		$section_template = $this->getSectionPrefix( $column );
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
		return new BlockTemplate( $column );
	}


	/**
	 * Get the template for a section
	 * 
	 * @param  \ChefSections\SectionTypes\Section 	$section
	 * 
	 * @return \ChefSections\TemplateClasses/ContentSection
	 */
	public function section( $section ){

		$classes = [
			'reference' => '\ChefSections\Templates\ReferenceTemplate',
			'container' => '\ChefSections\Templates\ContainerTemplate',
			'default' 	=> '\ChefSections\Templates\ContentSectionTemplate'
		];

		$key = $section->type;
		$keys =  array_keys( $classes );

		if( !isset( $keys[ $key ] ) )
			$key = 'default';

		$class = apply_filters( 'chef_sections_section_template_class', $classes[ $key ], $section );

		return new $class( $section );
	}


	/**
	 * Return a section prefix
	 * 
	 * @param  object $column
	 * @return string
	 */
	public function getSectionPrefix( $column ){

		$name = '';
		$postId = $column->post_id;
		$template = false;

		//this column is on the main section-flow:
		if( $column->post_id != $postId )
			$postId = $column->post_id;


		$sections = get_post_meta( $postId, 'sections', true );

		foreach( $sections as $section ){

			$name = '';
			if( $section['id'] === $column->section_id ){

				if( !isset( $section['type'] ) )
					$section['type'] = 'section';


				if( $section['type'] == 'stencil' || $section['type'] == 'reference' ){

					$_templatePost = get_post( $section['template_id'] );
					$name = $section['type'].DS.$_templatePost->post_name.'-';
				}

				if( isset( $section['name'] ) && $section['name'] != '' )
					$name .= $section['name'];
				
				return $name;
			
			}
		}

		return 'section-'.$column->section_id;

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

		$default = apply_filters( 'chef_sections_default_template', $default, $this );
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

		$located = apply_filters( 'chef_sections_located_template', $located, $this );
		return $located; 
	}



	/**
	 * Return an array of files
	 * 
	 * @return array
	 */
	public function getFiles( $template_prefix = null ){


		switch( $this->type ){

			case 'section':

				$base = $this->obj->getTemplateBase();
				$base = apply_filters( 'chef_sections_section_template_base', $base.'/', $this->obj );

				if( !is_null( $template_prefix ) )
					$base .= $template_prefix.'-';
				

				$array = array(
								$base.$this->obj->template.sanitize_title( $this->obj->name ),
								$base.$this->obj->template.$this->obj->view,
								$base.$this->obj->view,
								$base.'default'
				);

			break;

			case 'column':

				$base = apply_filters( 'chef_sections_column_template_base', 'columns/' );

				if( $this->obj->type == 'collection' )
					$base = apply_filters( 'chef_sections_collection_template_base', 'collections/' );


				$array = array(
								$base.$template_prefix.'-'.$this->obj->id,
								$base.$template_prefix.'-'.$this->obj->type,
								$base.$this->obj->type
				);

			break;


			case 'block':

				$base = apply_filters( 'chef_sections_block_template_base', 'collections/blocks/' );;


				$array = array(
							$base.$template_prefix.'-'.get_post_type(),
							$base.get_post_type()
				);
			break;

			case 'element':

				$base = apply_filters( 'chef_sections_elements_template_base', 'elements/' );;
				$array = array(
							$base.$this->obj
				);

			break;


		}


		$array = apply_filters( 'chef_sections_template_files', $array, $this );
        $array = apply_filters( 'chef_sections_'.$this->type.'_template_files', $array, $this->obj );

		$this->files = $array;
		return $array;
	}


}