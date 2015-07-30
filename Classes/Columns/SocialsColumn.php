<?php
namespace ChefSections\Columns;

use Cuisine\Wrappers\Field;

/**
 * Gallery column.
 * @package ChefSections\Columns
 */
class SocialsColumn extends DefaultColumn{

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type = 'socials';


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

			Field::text( 
				'title', 
				'Titel'
			),
			Field::text(
				'fb',
				'Facebook link'
			),
			Field::text(
				'tw',
				'Twitter link'
			),
			Field::text(
				'in',
				'LinkedIn link'
			),
			Field::text(
				'gp',
				'Google Plus link'
			),
			Field::text(
				'pin',
				'Pinterest link'
			),
			Field::text(
				'is',
				'Instagram link'
			)
		);

		return $fields;
	}
}