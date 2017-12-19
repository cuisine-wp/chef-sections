<?php
/**
 * All template tags for Chef Sections
 * @package CuisineSections
 */

use CuisineSections\Wrappers\Walker;

/**
 * Echoes get_sections
 * @return void
 */
if( !function_exists( 'the_sections' ) ){

	function the_sections(){	

		if( has_sections() )
			echo Walker::walk();

	}

}


/**
 * Get current sections
 * @return CuisineSections\Front\Walker ( html )
 */
if( !function_exists( 'get_sections' ) ){

	function get_sections(){

		return Walker::walk();

	}

}


/**
 * Get a section from an external post
 * 
 * @param  int $post_id    
 * @param  int $section_id 
 * @param  string $templatePath
 * 
 * @return string (html)
 */
if( !function_exists( 'get_section' ) ){

	function get_section( $post_id, $section_id = null, $templatePath = null ){

		$section = Walker::getSection( $post_id, $section_id, $templatePath );
		return $section;
	}

}


/**
 * Get the sections from a template 
 * 
 * @param  string $name template-name
 * @param  string $templatePath
 * 
 * @return string (html)
 */
if( !function_exists( 'get_sections_template' ) ){

	function get_sections_template( $name, $templatePath = null ){

		return Walker::getSectionsTemplate( $name, $templatePath );

	}

}

/**
 * A singular fallback for the function above
 * 
 * @param  string $name template-name
 * @param  string $templatePath
 * 
 * @return string (html)
 */
if( !function_exists( 'get_section_template' ) ){

	function get_section_template( $name, $templatePath = null ){

		return Walker::getSectionsTemplate( $name, $templatePath );

	}

}


/**
 * Check if this post has sections
 * @return  bool
 */
if( !function_exists( 'has_sections' ) ){

	function has_sections(){

		return Walker::hasSections();

	}

}


/**
 * Echo the columns:
 *
 * @param \CuisineSections\SectionTypes\BaseSection
 * 
 * @return void
 */
if( !function_exists( 'the_columns' ) ){

	function the_columns( $section ){

		echo Walker::columns( $section );
	
	}

}


/**
 * Get columns in a section 
 *
 * @param  \CuisineSections\SectionTypes\BaseSection
 * 
 * @return CuisineSections\Front\Walker ( html )
 */
if( !function_exists( 'get_columns' ) ){

	function get_columns( $section ){

		return Walker::columns( $section );

	}

}


/**
 * Echo all sections inside a container
 * 
 * @param  \CuisineSections\SectionTypes\Container $section
 * 
 * @return CuisineSections\Front\Walker
 */
if( !function_exists( 'the_containered_sections' ) ){

	function the_containered_sections( $section ){

		echo Walker::sectionsInContainer( $section );

	}

}


/**
 * Returns all sections inside a container
 * 
 * @param  \CuisineSections\SectionTypes\Container $section
 * 
 * @return CuisineSections\Front\Walker
 */
if( !function_exists( 'get_containered_sections' ) ){

	function get_containered_sections( $section ){

		return Walker::sectionsInContainer( $section );

	}

}


/**
 * Needs to be called inside a loop, and needs column information.
 * 
 * @param  \CuisineSections\Columns\Column $column
 * @return CuisineSections\Front\Walker ( html )
 */
if( !function_exists('get_block_template' ) ){

	function get_block_template( $column ){

		return Walker::block( $column );

	}

}