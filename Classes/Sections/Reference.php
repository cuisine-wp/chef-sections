<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;
use Cuisine\Wrappers\User;
use Cuisine\Wrappers\Field;
use ChefSections\Wrappers\SectionsBuilder;

/**
 * References are meant for use in 'regular' section-flows.
 */
class Reference extends Section{

	/**
	 * Section-type "Reference".
	 * 
	 * @var string
	 */
	public $type = 'reference';

	/**
	 * A boolean to see if this reference is in edit-mode
	 * 
	 * @var boolean
	 */
	public $in_edit_mode = false;

	/**
	 * Keep a set of the original
	 * @var string
	 */
	public $original;


	function __construct( $args ){
		

		$this->id = $args['id'];

		//get the post object based on the given post_id
		$this->post_id = $args['post_id'];
		$post = get_post( $this->post_id );

		//check if this is a section template
		$this->template_id = ( isset( $args['template_id'] ) ? $args['template_id'] : false );


		if( $post->post_type == 'section-template' ){
			$this->in_edit_mode = true;
			$this->template_id = $this->post_id;
		}		


		/**
		 * Fetch the basics from the parent
		 */
		$this->original = $this->fetchOriginalSection( $args );

		//use original as the defaults:
		$this->properties = $this->original;

		//fill in the basics
		$this->position = $args[ 'position' ]; //position is always related to post

		$this->title = $this->properties[ 'title' ];
		$this->view = $this->original['view']; //view is always original:
		$this->name = $this->getName( $this->original );

		//title & container settings
		if( strtolower( $this->title ) == 'sectie titel' )
			$this->title = '';

		$this->hide_title 		= ( $this->title == '' ? true : false );

		$this->hide_container 	= ( isset( $this->original['hide_container'] ) ? $this->original['hide_container'] : 'false' );

		//if( !empty( $this->original['columns'] ) ){
		$this->columns = $this->getColumns( $this->original['columns'] );
		//}else{
		//	$this->columns = array();
		//}
		
		$name = 'page-';
		if( isset( $post->post_name ) )
			$name = $post->post_name.'-';

		$this->template = $name;


		if( $post->post_type == 'section-template' && $this->type == 'section' ){

			$_type = get_post_meta( $this->post_id, 'type', true );
			if( $_type )
				$this->type = $_type;

		}

	}

	/**
	 * Overwrite the Build function for this reference
	 * 
	 * @return String (html, echoed)
	 */
	public function build(){

		if( is_admin() ){

			$class = 'section-wrapper ui-state-default section-'.$this->id;
			if( $this->in_edit_mode === false )
				$class .= ' reference';
			

			echo '<div class="'.esc_attr( $class ).'" ';
				echo 'id="'.esc_attr( $this->id ).'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="section-columns '.esc_attr( $this->view ).'">';
	

				foreach( $this->columns as $column ){
	
					//build column with reference-mode on:
					$refMode = true;
					if( $this->in_edit_mode )
						$refMode = false;
					
					echo $column->build( $refMode );
	
				}


				if( $this->in_edit_mode === false ){

					echo '<p class="template-txt">';
						printf( __( 'This is the template "%s." When editting this template, it get\'s changed on every page.', 'chefsections' ), get_the_title( $this->template_id ) );
					echo '</p>';


					echo '<a href="'.esc_url( admin_url( 'post.php?post='.$this->template_id.'&action=edit' ) ).'" class="button button-primary">';

						_e( 'Edit this template', 'chefsections' );

					echo '</a>';

				}


				echo '<div class="clearfix"></div>';
				echo '</div>';

				$this->bottomControls();
				$this->buildSettingsPanels();
				$this->buildHiddenFields();
				

			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}
	}


	/**
	 * Get the Columns in an array
	 * 
	 * @return array
	 */
	
	public function getColumns( $columns ){


		//get the parent's columns
		$parent = get_post_meta( $this->template_id, 'sections', true );		
		$parent = array_values( $parent );
		$arr = array();


		if( isset( $parent[0] ) ){

			$parent = $parent[0];
			$columns = $parent['columns'];


			//reset all other values as well:
			$this->resetSectionValues( $parent );
	
			//populate the columns array with actual column objects
			foreach( $columns as $col_key => $type ){
	
				$props = array(
					'post_id'	=>	 $this->template_id
				);
	
				$arr[] = Column::$type( $col_key, $parent['id'], $props );
	
			}
	
		}

		return $arr;

	}

	/**
	 * Reset all other section-values that are important
	 * 
	 * @param  array $parent
	 * @return void
	 */
	public function resetSectionValues( $parent ){
		$this->title = $parent['title'];
		$this->view = $parent['view'];
		$this->name = $this->getName( $parent );
	}

	/**
	 * Returns the original section info
	 * 
	 * @return array
	 */
	public function fetchOriginalSection( $args ){

		$original = $args; //fallback

		$meta = get_post_meta( $this->template_id, 'sections', true );
		$templateSections = array_values( $meta );
		if( isset( $templateSections[ 0 ] ) && !empty( $templateSections[ 0 ] ) ){
			$original = $templateSections[ 0 ];
		}

		return $original;
	}


}