<?php

namespace ChefSections\SectionTypes;

use ChefSections\Wrappers\Column;

use Cuisine\Wrappers\User;

/**
 * Blueprints are full-scale section-templates tied to post-types
 */
class Blueprint extends BaseSection{


	/**
	 * Section-type "Blueprint".
	 * 
	 * @var string
	 */
	public $type = 'blueprint';



	/**
	 * The post-type for this blueprint
	 * 
	 * @var string
	 */
	public $postType = 'page';


}