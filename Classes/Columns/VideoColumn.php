<?php
namespace ChefSections\Columns;

use Cuisine\Wrappers\Field;

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
	 * Generate a graphic depiction of the collection
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildPreview(){

		$still = $this->getField( 'still' );

		if( $this->getField( 'title' ) )	
			echo '<strong>'.$this->getField( 'title' ).'</strong>';

		if( $still['thumb'] != '' ){

			echo '<div class="img-wrapper">';
				echo '<img src="'.$still['thumb'].'"/>';
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
				'Titel',
				array(
					'label' 				=> false,
					'placeholder' 			=> 'Titel',
					'defaultValue'			=> $this->getField( 'title' ),
				)
			),
			'url'	=> Field::text(
				'url',
				'Video url',
				array(
					'label' 				=> false,
					'placeholder' 			=> 'Video url',
					'defaultValue'			=> $this->getField( 'url' ),
				)
			),
			'still' => Field::image(
				'still',
				'Video still',
				array(
					'defaultValue'			=> $this->getField( 'still' ),
				)
			)
		);

		return $fields;
	}
}