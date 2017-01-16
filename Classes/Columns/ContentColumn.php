<?php
namespace ChefSections\Columns;

use Cuisine\View\Excerpt;
use Cuisine\Wrappers\Field;
use ChefSections\Contracts\Column as ColumnContract;

/**
 * Content column.
 * @package ChefSections\Columns
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
	public function theField( String $name, $default = null ){

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

		echo '<h2>'.esc_html( $this->getField( 'title' ) ).'</h2>';
		echo '<p>'.Excerpt::get( $content, 150, ' ', '' ).'...</p>';

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