<?php
namespace CuisineSections\Front;

use \CuisineSections\Wrappers\Walker;
use \CuisineSections\Wrappers\Column;
use \CuisineSections\Wrappers\SectionsBuilder;
use \Cuisine\Utilities\Url;
use \Cuisine\Utilities\Sort;
use \Cuisine\Utilities\Session;

use \CuisineSections\Templates\BlockTemplate;
use \CuisineSections\Templates\ColumnTemplate;
use \CuisineSections\Templates\ElementTemplate;
use \CuisineSections\Templates\CollectionTemplate;
use \CuisineSections\Templates\DynamicSectionTemplate;

/**
 * The Template class locates templates
 * @package CuisineSections\Front
 */
class TemplateFinder {

	/**
	 * Get the template for a column
	 * 
	 * @param  \CuisineSections\Columns\Column 	$column
	 * @return \CuisineSections\Front\TemplateFinder ( chainable )
	 */
	public function column( $column ){
		
		if( $column->type == 'collection' )
			return new CollectionTemplate( $column );
		

		return new ColumnTemplate( $column );
	}

	/**
	 * Get the template for a single collection block
	 * 
	 * @param  \CuisineSections\Columns\Column 	$column
	 * @return \CuisineSections\Front\TemplateFinder ( chainable )
	 */
	public function block( $column ){
		return new BlockTemplate( $column );
	}


	/**
	 * Get the template for a section
	 * 
	 * @param  \CuisineSections\SectionTypes\BaseSection 	$section
	 * 
	 * @return \CuisineSections\TemplateClasses/ContentSection
	 */
	public function section( $section ){

		$classes = [
			'reference' => '\CuisineSections\Templates\ReferenceTemplate',
			'container' => '\CuisineSections\Templates\ContainerTemplate',
			'default' 	=> '\CuisineSections\Templates\ContentSectionTemplate'
		];

		$key = $section->type;

		if( !isset( $classes[ $key ] ) )
			$key = 'default';

		$class = apply_filters( 'cuisine_sections_section_template_class', $classes[ $key ], $section );
		return new $class( $section );
	}


	/**
	 * Returns a dynamic section template
	 * 
	 * @param  \CuisineSections\SectionTypes\BaseSection $section
	 * @param  String $path
	 * 
	 * @return CuisineSections\TemplateClasses/DynamicSectionTemplate
	 */
	public function dynamic( $section, $path )
	{
		return new DynamicSectionTemplate( $section, $path );
	}


	/**
	 * Get a template for a regular element
	 * 
	 * @param  string $name name of the template
	 * @return CuisineSections\Front\TemplateFinderFinder
	 */
	public function element( $name ){
		return new ElementTemplate( $name );
	}
}