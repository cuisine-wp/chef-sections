<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;
use ChefSections\Wrappers\SectionsBuilder;
use ChefSections\Wrappers\Template;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\User;

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
	public $title = '';


	/**
	 * Viewmode of this section
	 * 
	 * @var string
	 */
	public $view = '';


	/**
	 * Template prefix
	 * 
	 * @var string
	 */
	public $template = '';


	/**
	 * All columns in this section
	 * 
	 * @var array
	 */
	public $columns = array();


	/**
	 * Simple boolean to display or not display a section title
	 *
	 * @var boolean
	 */
	public $hide_title;



	function __construct( $args ){
		
		global $post;

		$this->id = $args['id'];

		$this->post_id = $args['post_id'];

		$this->position = $args['position'];

		$this->title = $args['title'];

		$this->view = $args['view'];

		$this->hide_title = ( isset( $args['hide_title'] ) ? (bool)$args['hide_title'] : false );


		$this->columns = $this->getColumns( $args['columns'] );

		$name = 'page-';
		if( isset( $post->post_name ) )
			$name = $post->post_name.'-';

		$this->template = $name;

	}
	
	/*=============================================================*/
	/**             Template                                       */
	/*=============================================================*/

	/**
	 * This function runs just before the template
	 * 
	 * @return void
	 */
	public function beforeTemplate(){

		//nothing here

	}

	/**
	 * This function runs just after the template
	 * 
	 * @return void
	 */
	public function afterTemplate(){

		//nothing here

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

				if( User::hasRole( 'administrator' ) )
					$this->buildControls();

				echo '<div class="section-columns '.$this->view.'">';
	
				foreach( $this->columns as $column ){
	
					echo $column->build();
	
				}

				echo '<div class="clearfix"></div>';
				echo '</div>';

				if( User::hasRole( 'administrator' ) )
					$this->bottomControls();
			
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

			echo '<span class="dashicons dashicons-sort pin"></span>';

		echo '</div>';
	
		echo '<div class="clearfix"></div>';
	}


	/**
	 * Create the controls on the bottom
	 * 
	 * @return string (html, echoed)
	 */
	private function bottomControls(){

		echo '<div class="section-footer">';
			echo '<p class="delete-section">';
				echo '<span class="dashicons dashicons-trash"></span>';
			echo __( 'Verwijder', 'chefsections' ).'</p>';

			do_action( 'chef_sections_bottom_controls' );

			$this->buildTemplateSnitch();
			$this->buildCodeSnitch();

		echo '</div>';

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

	/**
	 * Generate the code needed to fetch this section
	 * 
	 * @return string ( html, echoed )
	 */
	private function buildCodeSnitch(){

		echo '<span class="template-snitch code-snitch">';
			echo '<span class="dashicons dashicons-editor-code"></span>';
			echo '<span class="tooltip">';

				echo '<strong>Code:</strong><br/>';
				echo '<span class="copy">echo Loop::section( '.$this->post_id.', '.$this->id.' );</span>';

			echo '</span>';
		echo '</span>';
	}


	/**
	 * Generate the templates for this section
	 * 
	 * @return string ( html, echoed )
	 */
	private function buildTemplateSnitch(){

		$templates = Template::section( $this )->files;
		echo '<span class="template-snitch">';
			echo '<span class="dashicons dashicons-media-text"></span>';
			echo '<span class="tooltip">';

				echo '<strong>Templates:</strong>';
				foreach( $templates as $template ){

					echo '<p>'.$template.'</p>';

				}

			echo '</span>';
		echo '</span>';
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

			$props = array(
				'post_id'	=>	 $this->post_id
			);

			$arr[] = Column::$type( $col_key, $this->id, $props );

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
			$prefix.'[hide_title]',
			'Laat titel zien',
			array(
				'defaultValue'	=> $this->hide_title
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
				'defaultValue' => $this->position,
				'class' => array( 'field', 'input-field', 'hidden-field', 'section-position' )
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

