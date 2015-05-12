<?php
namespace ChefSections\Columns;

use Cuisine\Wrapper\Image;

/**
 * Image column.
 * @package ChefSections\Columns
 */
class ImageColumn extends DefaultColumn{

	/**
	 * The unique number for this column, on this page
	 * 
	 * @var Int
	 */
	private $id;

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type;


	/**
	 * Preview-data
	 *
	 * @var String
	 */
	private $preview;


}