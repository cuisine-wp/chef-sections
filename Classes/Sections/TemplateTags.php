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
 * @return string ( html )
 */
function get_sections(){

	return Walker::walk();

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
 * @return string ( html )
 */
function get_columns( $section ){

	return Walker::columns( $section );

}


/**
 * Needs to be called inside a loop, and needs column information.
 * 
 * @param  \ChefSections\Columns\Column $column
 * @return string ( html )
 */
function get_block_template( $column ){

	return Walker::block( $column );

}