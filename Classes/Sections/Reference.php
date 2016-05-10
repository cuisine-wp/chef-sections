<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;
use Cuisine\Wrappers\User;

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


	function __construct( $args ){
		

		$this->id = $args['id'];

		//get the post object based on the given post_id
		$this->post_id = $args['post_id'];
		$post = get_post( $this->post_id );

		$this->template_id = ( isset( $args['template_id'] ) ? $args['template_id'] : false );


		if( $post->post_type == 'section-template' ){
			$this->in_edit_mode = true;
			$this->template_id = $this->post_id;
		}		

		$this->position = $args['position'];

		$this->title = $args['title'];

		$this->view = $args['view'];

		$this->name = $this->getName( $args );

		$this->hide_title = ( isset( $args['hide_title'] ) ? $args['hide_title'] : 'false' );

		$this->hide_container = ( isset( $args['hide_container'] ) ? $args['hide_container'] : 'false' );

		$this->properties = $args;

		$this->columns = $this->getColumns( $args['columns'] );

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
			

			echo '<div class="'.$class.'" ';
				echo 'id="'.$this->id.'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="section-columns '.$this->view.'">';
	

				foreach( $this->columns as $column ){
	
					//build column with reference-mode on:
					echo $column->build( true );
	
				}


				if( $this->in_edit_mode === false ){

					echo '<p class="template-txt">';
						printf( __( 'Dit is het sjabloon "%s." Bij het aanpassen wordt deze op iedere pagina aangepast.', 'chefsections' ), get_the_title( $this->template_id ) );
					echo '</p>';


					echo '<a href="'.admin_url( 'post.php?post='.$this->template_id.'&action=edit' ).'" class="button button-primary">';

						_e( 'Bewerk dit sjabloon', 'chefsections' );

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
	//	cuisine_dump( $parent );

		
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




}