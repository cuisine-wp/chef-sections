<?php
namespace ChefSections\Columns;

/**
 * Empty column.
 * @package ChefSections\Columns
 */
class EmptyColumn extends DefaultColumn{

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type = 'empty';


	/**
	 * Lightbox boolean
	 * 
	 * @var boolean
	 */
	public $hasLightbox = false;


	/**
	 * Create the preview image
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildPreview(){

		echo '<strong>--LEGE KOLOM--</strong>';
		
	}


}