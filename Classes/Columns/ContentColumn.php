<?php
namespace ChefSections\Columns;

use Cuisine\Wrappers\Field;

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

		$title = ( isset( $this->properties['title'] ) ? $this->properties['title'] : '' );
		$editor = ( isset( $this->properties['content'] ) ? $this->properties['content'] : '' ); 

		$fields = array(
			'title' => Field::text( 
				'title', 
				'',
				array(
					'label' 		=> false,
					'placeholder' 	=> 'Titel',
					'defaultValue'	=> $title,
				)
			),
			'editor' => Field::editor( 
				'content', 
				'', 
				array(
					'label'			=> false,
					'defaultValue' 	=> $editor
				)
			)
		);

		$fields = apply_filters( 'chef_sections_content_column_fields', $fields );
		return $fields;

	}


}