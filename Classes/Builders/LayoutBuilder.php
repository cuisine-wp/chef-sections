<?php

namespace ChefSections\Builders;

use ChefSections\Sections\Layout;


/**
 * Layouts get there own builder:
 */
class LayoutBuilder extends SectionsBuilder {

	/**
	 * Returns the correct Section class
	 * 
	 * @return ChefSections\Sections\Layout
	 */
	public function getSectionType( $section ){

		return new Layout( $section );

	}
}