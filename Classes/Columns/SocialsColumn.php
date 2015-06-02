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

			'title' => Field::text( 
				'title', 
				'Titel'
			),
			'fb'	=> Field::text(
				'fb',
				'Facebook link'
			),
			'tw'	=> Field::text(
				'tw',
				'Twitter link'
			),
			'in'	=> Field::text(
				'in',
				'LinkedIn link'
			),
			'gp'	=> Field::text(
				'gp',
				'Google Plus link'
			),
			'pin'	=> Field::text(
				'pin',
				'Pinterest link'
			)
		);

		return $fields;
	}
}