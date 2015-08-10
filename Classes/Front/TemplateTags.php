<?php
/**
 * All template tags for Chef Sections
 * @package ChefSections
 */

use ChefSections\Wrappers\Walker;

/**
 * Echoes get_sections
 * @return void
 */
function the_sections(){	

	if( has_sections() )
		echo Walker::walk();

}


/**
 * Get current sections
 * @return ChefSections\Front\Walker ( html )
 */
function get_sections(){

	return Walker::walk();

}

/**
 * Get a section from an external post
 * 
 * @param  int $post_id    
 * @param  int $section_id 
 * @return string (html)
 */
function get_section( $post_id, $section_id ){

	$section = Walker::get_section( $post_id, $section_id );
	return $section;

}


/**
 * Get the sections from a template 
 * 
 * @param  string $name template-name
 * @return string (html)
 */
function get_sections_template( $name ){

	return Walker::get_sections_template( $name );

}


/**
 * A singular fallback for the function above
 * 
 * @param  string $name template-name
 * @return string (html)
 */
function get_section_template( $name ){

	return Walker::get_sections_template( $name );

}


/**
 * Check if this post has sections
 * @return  bool
 */
function has_sections(){

	return Walker::hasSections();

}


/**
 * Echo the columns:
 *
 * @param \ChefSections\Sections\Section;
 * @return void
 */
function the_columns( $section ){

	echo Walker::columns( $section );
	
}


/**
 * Get columns in a section 
 *
 * @param  \ChefSections\Sections\Section
 * @return ChefSections\Front\Walker ( html )
 */
function get_columns( $section ){

	return Walker::columns( $section );

}


/**
 * Needs to be called inside a loop, and needs column information.
 * 
 * @param  \ChefSections\Columns\Column $column
 * @return ChefSections\Front\Walker ( html )
 */
function get_block_template( $column ){

	return Walker::block( $column );

}