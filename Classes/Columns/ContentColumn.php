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
	 * Simple echo function for the getField method
	 * 
	 * @param  string $name
	 * @return string ( html, echoed )
	 */
	public function theField( $name ){

		if( $this->getField( $name ) ){

			if( $name == 'content' ){

				echo apply_filters( 'the_content', $this->getField( $name ) );

			}else{
				
				echo $this->getField( $name );

			}
		}
	}


	/**
	 * Create the preview for this column
	 * 
	 * @return string (html,echoed)
	 */
	public function buildPreview(){

		$content = $this->getField('content');

		//remove backslashes when doing ajax updates:
		if( defined( 'DOING_AJAX' ) )
			$content = stripcslashes( $content );

		echo '<h2>'.esc_html( $this->getField( 'title' ) ).'</h2>';
		echo '<p>'.Excerpt::get( $content, 150, ' ', '' ).'...</p>';

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

		$content = $this->getField( 'content' );

		//remove backslashes when doing ajax updates:
		if( defined( 'DOING_AJAX' ) )
			$content = stripcslashes( $content );

		$fields = array(
			'title' => Field::text( 
				'title', 
				'',
				array(
					'label' 				=> false,
					'placeholder' 			=> __('Title','chefsections'),
					'defaultValue'			=> $this->getField( 'title' ),
				)
			),
			'editor' => Field::editor( 
				'content', //this needs a unique id 
				'', 
				array(
					'label'				=> false,
					'defaultValue' 		=> $content,
					'column'			=> $this->fullId
				)
			)
		);

		$fields = apply_filters( 'chef_sections_content_column_fields', $fields );
		return $fields;

	}


}