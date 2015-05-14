<?php
namespace ChefSections\Columns;

use Cuisine\Wrapper\Image;

/**
 * Image column.
 * @package ChefSections\Columns
 */
class ImageColumn extends DefaultColumn{

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type = 'image';


	/**
	 * Lightbox boolean
	 * 
	 * @var boolean
	 */
	public $hasLightbox = false;


}