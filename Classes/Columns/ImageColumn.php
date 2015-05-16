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


	/**
	 * Create the preview image
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildPreview(){

		echo '<div class="img-wrapper">';

			echo '<img src="'.$this->getField( 'medium' ).'"/>';

		echo '</div>';
		
	}


}