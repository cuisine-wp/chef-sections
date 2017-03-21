<?php
namespace ChefSections\Front;

use \ChefSections\Wrappers\Walker;
use \ChefSections\Wrappers\Column;
use \ChefSections\Wrappers\SectionsBuilder;
use \Cuisine\Utilities\Url;
use \Cuisine\Utilities\Sort;
use \Cuisine\Utilities\Session;

use \ChefSections\Templates\BlockTemplate;
use \ChefSections\Templates\ColumnTemplate;
use \ChefSections\Templates\ElementTemplate;
use \ChefSections\Templates\CollectionTemplate;


/**
 * The Template class locates templates
 * @package ChefSections\Front
 */
class TemplateFinder {

	/**
	 * Get the template for a column
	 * 
	 * @param  \ChefSections\Columns\Column 	$column
	 * @return \ChefSections\Front\TemplateFinder ( chainable )
	 */
	public function column( $column ){
		
		if( $column->type == 'collection' )
			return new CollectionTemplate( $column );
		

		return new ColumnTemplate( $column );
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

		if( !isset( $classes[ $key ] ) )
			$key = 'default';

		$class = apply_filters( 'chef_sections_section_template_class', $classes[ $key ], $section );
		return new $class( $section );
	}


	/**
	 * Get a template for a regular element
	 * 
	 * @param  string $name name of the template
	 * @return ChefSections\Front\TemplateFinderFinder
	 */
	public function element( $name ){
		return new ElementTemplate( $name );
	}
}