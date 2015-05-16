<?php
namespace ChefSections\Columns;

use Cuisine\Wrappers\Field;
use Cuisine\View\Excerpt;

/**
 * Content column.
 * @package ChefSections\Columns
 */
class ContentColumn extends DefaultColumn{

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type = 'content';


	/**
	 * Create the preview for this column
	 * 
	 * @return string (html,echoed)
	 */
	public function buildPreview(){

		echo '<h2>'.$this->getField( 'title' ).'</h2>';
		echo '<p>'.Excerpt::get( $this->getField( 'content' ), 300, ' ', '' ).'...</p>';

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
	 * @return [type] [description]
	 */
	private function getFields(){


		$fields = array(
			'title' => Field::text( 
				'title', 
				'',
				array(
					'label' 				=> false,
					'placeholder' 			=> 'Titel',
					'defaultValue'			=> $this->getField( 'title' ),
				)
			),
			'editor' => Field::editor( 
				'content_'.$this->fullId, //this needs a unique id 
				'', 
				array(
					'label'				=> false,
					'defaultValue' 		=> $this->getField( 'content' )
				)
			)
		);

		$fields = apply_filters( 'chef_sections_content_column_fields', $fields );
		return $fields;

	}


}