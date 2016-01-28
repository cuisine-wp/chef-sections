<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;

use Cuisine\Wrappers\User;

/**
 * Blueprints are full-scale section-templates tied to post-types
 */
class Blueprint extends Section{


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