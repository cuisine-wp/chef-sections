<?php
namespace ChefSections\Columns;

use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\Script;
use Cuisine\Utilities\Url;

/**
 * Video column.
 * @package ChefSections\Columns
 */
class VideoColumn extends DefaultColumn{

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

		$url = Url::plugin( 'chef-sections', true ).'Assets/js/collections/';
		Script::register( 'video_column', $url.'video', true );	

	}


	/**
	 * Generate a graphic depiction of the collection
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildPreview(){

		$still = $this->getField( 'still' );

		if( $this->getField( 'title' ) )	
			echo '<strong>'.esc_html( $this->getField( 'title' ) ).'</strong>';

		if( $still['thumb'] != '' ){

			echo '<div class="img-wrapper">';
				echo '<img src="'.esc_attr( $still['thumb'] ).'"/>';
			echo '</div>';
			
		}
	}


	/**
	 * Build the contents of the lightbox for this column
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildLightbox(){

		$fields = $this->getFields();

		echo '<div class="main-content">';
		
			foreach( $fields as $field ){

				$field->render();

			}

		echo '</div>';
		echo '<div class="side-content">';

			$this->saveButton();

		echo '</div>';
	}


	/**
	 * Get the fields for this column
	 * 
	 * @return array
	 */
	private function getFields(){

		$fields = array(

			'title' => Field::text( 
				'title', 
				__('Title','chefsections'),
				array(
					'label' 				=> false,
					'placeholder' 			=> __('Title','chefsections'),
					'defaultValue'			=> $this->getField( 'title' ),
				)
			),
			'url'	=> Field::text(
				'url',
				__('Video url','chefsections'),
				array(
					'label' 				=> false,
					'placeholder' 			=> __('Video url','chefsections'),
					'defaultValue'			=> $this->getField( 'url' ),
				)
			),
			'still' => Field::image(
				'still',
				__('Video still','chefsections'),
				array(
					'defaultValue'			=> $this->getField( 'still' ),
				)
			)
		);

		return $fields;
	}
}