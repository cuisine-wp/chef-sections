<?php
namespace CuisineSections\Columns;

use Cuisine\View\Excerpt;
use Cuisine\Wrappers\Field;
use CuisineSections\Contracts\Column as ColumnContract;

/**
 * Content column.
 * @package CuisineSections\Columns
 */
class ContentColumn extends DefaultColumn implements ColumnContract{

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
	public function theField( $name, $default = null ){

		if( $this->getField( $name, $default ) ){

			if( $name == 'content' ){

				echo apply_filters( 'the_content', $this->getField( $name, $default ) );

			}else{

				echo $this->getField( $name, $default );

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

		echo $this->getTitle( 'title' );
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

				//if a field has a JS-template, we need to render it:
				if( method_exists( $field, 'renderTemplate' ) ){
					echo $field->renderTemplate();
				}

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
	public function getFields(){

		$content = $this->getField( 'content' );

		//remove backslashes when doing ajax updates:
		if( defined( 'DOING_AJAX' ) )
			$content = stripcslashes( $content );

		$fields = array(
			Field::title( 
				'title', 
				'',
				array(
					'label' 				=> false,
					'placeholder' 			=> __('Title','CuisineSections'),
					'defaultValue'			=> $this->getField( 'title', ['text' => '', 'type' => 'h2'] ),
				)
			),

			Field::editor( 
				'content', //this needs a unique id 
				'', 
				array(
					'label'				=> false,
					'defaultValue' 		=> $content,
					'column'			=> $this->fullId
				)
			)
		);

		$fields = apply_filters( 'cuisine_sections_content_column_fields', $fields );
		return $fields;

	}


}