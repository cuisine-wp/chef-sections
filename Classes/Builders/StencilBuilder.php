<?php

namespace ChefSections\Builders;

use ChefSections\Sections\Stencil;
use Cuisine\Wrappers\Metabox;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\PostType;
use WP_Query;
use stdClass;


/**
 * Stencils get there own builder:
 */
class StencilBuilder extends SectionsBuilder {


	/**
	 * Returns the correct Section class
	 * 
	 * @return ChefSections\Sections\Stencil
	 */
	public function getSectionType( $section ){

		return new Stencil( $section );

	}

}
