<?php
namespace ChefSections\Columns;

/**
 * Video column.
 * @package ChefSections\Columns
 */
class VideoColumn extends DefaultColumn{

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