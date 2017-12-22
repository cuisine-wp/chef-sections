<?php
namespace CuisineSections\Columns;

use Cuisine\Utilities\Url;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\Script;
use CuisineSections\Contracts\Column as ColumnContract;

/**
 * Video column.
 * @package CuisineSections\Columns
 */
class VideoColumn extends DefaultColumn implements ColumnContract{

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type = 'video';


	/**
	 * After the video column
	 * 
	 * @return void
	 */
	public function afterTemplate(){

		$url = Url::plugin( 'cuisine-sections/Assets/js/collections/video' );
		Script::register( 'video_column', $url, true );	

	}

	/**
	 * Returns the video using Oembed
	 * 
	 * @return String
	 */
	public function getVideo()
	{
		$url = $this->getField( 'url' );
		return wp_oembed_get( $url );
	}

	/**
	 * Generate a graphic depiction of the collection
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildPreview(){

		$still = $this->getField( 'still' );

		echo $this->getTitle( 'title' );

		if( $still['thumb'] != '' ){

			echo '<div class="img-wrapper">';
				echo '<img src="'.esc_attr( $still['thumb'] ).'"/>';
			echo '</div>';
			
		}
	}


	/**
	 * Get the fields for this column
	 * 
	 * @return array
	 */
	public function getFields(){

		$fields = array(

			'title' => Field::title( 
				'title', 
				__('Title','CuisineSections'),
				array(
					'label' 				=> false,
					'placeholder' 			=> __('Title','CuisineSections'),
					'defaultValue'			=> $this->getField( 'title', ['text' => '', 'type' => 'h2'] ),
				)
			),
			'url'	=> Field::text(
				'url',
				__('Video url','CuisineSections'),
				array(
					'label' 				=> false,
					'placeholder' 			=> __('Video url','CuisineSections'),
					'defaultValue'			=> $this->getField( 'url' ),
				)
			),
			'still' => Field::image(
				'still',
				__('Video still','CuisineSections'),
				array(
					'defaultValue'			=> $this->getField( 'still' ),
				)
			)
		);

		return $fields;
	}
}