<?php
namespace ChefSections\Columns;

/**
 * Collection column.
 * @package ChefSections\Columns
 */
class CollectionColumn extends DefaultColumn{

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
	private $type;


	/**
	 * Preview-data
	 *
	 * @var String
	 */
	private $preview;


}