<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;
use ChefSections\Wrappers\SectionsBuilder;
use Cuisine\Wrappers\Field;

/**
 * Admin Section-meta
 * @package ChefSections\Admin
 */
class Section {

	/**
	 * Unique Id for this section, prefixed by the post_id
	 * 
	 * @var string
	 */
	public $id = '';

	/**
	 * Position of this section
	 * 
	 * @var integer
	 */
	private $position = 0;

	/**
	 * The post ID this section is tied to
	 * 
	 * @var integer
	 */
	public $post_id;

	/**
	 * Title of this section
	 * 
	 * @var string
	 */
	private $title = '';


	/**
	 * Viewmode of this section
	 * 
	 * @var string
	 */
	private $view = '';


	/**
	 * All columns in this section
	 * 
	 * @var array
	 */
	private $columns = array();



	function __construct( $args ){

		$this->id = $args['id'];

		$this->post_id = $args['post_id'];

		$this->position = $args['position'];

		$this->title = $args['title'];

		$this->view = $args['view'];

		$this->columns = $this->getColumns( $args['columns'] );

	}
	

	/*=============================================================*/
	/**             Backend                                        */
	/*=============================================================*/


	/**
	 * Build this Section
	 * 
	 * @return String (html, echoed)
	 */
	public function build(){

		if( is_admin() ){

			echo '<div class="section-wrapper ui-state-default section-'.$this->id.'" ';
				echo 'id="'.$this->id.'" ';
				$this->buildIds();
			echo '>';
	
				$this->buildControls();

				echo '<div class="section-columns '.$this->view.'">';
	
				foreach( $this->columns as $column ){
	
					echo $column->build();
	
				}

				echo '<div class="clearfix"></div>';
				echo '</div>';
			
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}
	}

	/**
	 * Build the top of this Section
	 * 
	 * @return String ( html, echoed )
	 */
	private function buildControls(){

		$fields = $this->getControlFields();


		echo '<div class="section-controls">';
			
			foreach( $fields as $field ){

				$field->render();

			}

			echo '<span class="dashicons dashicons-randomize pin"></span>';

		echo '</div>';
	
		echo '<div class="clearfix"></div>';

	}


	/**
	 * Generate the data-tags with id's
	 * 
	 * @return string ( html, echoed )
	 */
	private function buildIds(){

		echo 'data-section_id="'.$this->id.'" ';
		echo 'data-post_id="'.$this->post_id.'"';

	}




	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/


	/**
	 * Get the Columns in an array
	 * 
	 * @return array
	 */
	private function getColumns( $columns ){

		$arr = array();

		//populate the columns array with actual column objects
		foreach( $columns as $col_key => $type ){

			$arr[] = Column::$type( $col_key, $this->id );

		}

		return $arr;

	}

	/**
	 * Returns the array of fields for this section
	 * 
	 * @return array
	 */
	private function getControlFields(){

		$prefix = 'section['.$this->id.']';
		$types = array_fill_keys( array_keys( SectionsBuilder::getViewTypes() ), false );


		$title = Field::text( 
			$prefix.'[title]',
			'', //no label,
			array( 
				'placeholder'  => 'Titel',
				'defaultValue' => $this->title
			)
		);

		$check = Field::checkbox(
			$prefix.'[show_title]',
			'Laat titel zien',
			array(
				'defaultValue'	=> true
			)
		);


		$views = Field::radio(
			$prefix.'[view]',
			'Weergave',
			$types,
			array(
				'defaultValue' => $this->view
			)
		);


		$position = Field::hidden(
			$prefix.'[position]',
			array(
				'defaultValue' => $this->position
			)
		);

		$post_id = Field::hidden(
			$prefix.'[post_id]',
			array(
				'defaultValue' => $this->post_id
			)
		);

		$id = Field::hidden(
			$prefix.'[id]',
			array(
				'defaultValue' => $this->id
			)
		);

		$fields = array(

			$title,
			$check,
			$views,
			$position,
			$post_id,
			$id

		);

		$fields = apply_filters( 'chef_sections_section_controls', $fields );
		return $fields;
	}


	/**
	 * Get this sections's template slug
	 * 
	 * @return String
	 */
	public function getSlug(){

		global $post;
		return $post->post_name.'-'.sanitize_title( $this->title );

	}



}

