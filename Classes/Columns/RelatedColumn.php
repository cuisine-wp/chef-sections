<?php
namespace ChefSections\Columns;

/**
 * Related column.
 * @package ChefSections\Columns
 */
class RelatedColumn extends CollectionColumn{

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