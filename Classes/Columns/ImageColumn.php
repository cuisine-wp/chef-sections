<?php
namespace ChefSections\Columns;

use Cuisine\Wrapper\Image;
use ChefSections\Contracts\Column as ColumnContract;


/**
 * Image column.
 * @package ChefSections\Columns
 */
class ImageColumn extends DefaultColumn implements ColumnContract{

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

		$url = $this->getField( 'medium' );

		if( !$url || $url == 'false' || $url == '' )
			$url = $this->getField( 'full' );


		echo '<div class="img-wrapper">';

			echo '<img src="'.esc_attr( $url ).'"/>';

		echo '</div>';
		
	}


}