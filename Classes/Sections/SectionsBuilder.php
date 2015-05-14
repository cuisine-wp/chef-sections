<?php

namespace ChefSections\Sections;

use ChefSections\Sections\Section;
use Cuisine\Utilities\Session;

/**
 * Set admin meta boxes per section.
 * @package ChefSections\Admin
 */
class SectionsBuilder {



	/**
	 * Sections array 
	 * 
	 * @var array
	 */
	private $sections = array();


	/**
	 * Post ID
	 *
	 * @var int
	 */
	private $postId;

	/**
	 * Keep the sections id's unique and get the highest
	 * 
	 * @var int;
	 */
	private $highestId;



	/**
	 * Call the methods on construct
	 *
	 * @return \ChefSections\Admin
	 */
	function __construct(){

		$this->init();
	}


	/**
	 * Initiate this class
	 * 
	 * @param  int $post_id
	 * @return void
	 */
	function init(){
		global $post;

		$this->postId = $post->ID;
		$this->sections = $this->getSections();
		$this->highestId = $this->getHighestId();
	}


	/*=============================================================*/
	/**             Metabox functions                              */
	/*=============================================================*/

	/**
	 * Set each of the admin-sections
	 *
	 * @return void (echoes html)
	 */
	public function build(){


		if( !$this->validPostType( $this->postId ) )
			return false;

		wp_nonce_field( Session::nonceAction, Session::nonceName );


		if( !empty( $this->sections ) ){

			echo '<div class="section-container" id="section-container">';

			foreach( $this->sections as $section ){

				$section->build();
			}

			echo '</div>';

		}else{

			echo '<div class="section-wrapper msg">';
				echo '<p>'.__('Nog geen secties aangemaakt.', 'chefsections').'</p>';
			echo '</div>';
		
		}

		$this->addSectionButton();
	}


	/**
	 * Add a button at the bottom:
	 *
	 * @return void
	 */
	private function addSectionButton(){

		echo '<div class="section-wrapper dotted-bg">';

			echo '<div id="addSection" class="button" data-post_id="'.$this->postId.'">';
				_e( 'Sectie toevoegen', 'chefsections' );
			echo '</div>';

		echo '</div>';
	}




	/*=============================================================*/
	/**             Saving                                         */
	/*=============================================================*/


	/**
	 * Loop through each section and save 'em
	 * 
	 * @return bool
	 */
	public function save( $post_id ){

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	    $nonceName = (isset($_POST[Session::nonceName])) ? $_POST[Session::nonceName] : Session::nonceName;
	    if (!wp_verify_nonce($nonceName, Session::nonceAction)) return;


		if( !$this->validPostType( $post_id ) )
			return false;


		if( isset( $_POST['section'] ) ){

			$sections = $_POST['section'];

			//save columns and types
			foreach( $sections as $section ){

				$columns = array();
				$types = $this->getViewTypes();
				$count = $types[ $section['view'] ];

				for( $i = 1; $i <= $count; $i++ ){

					$string = '_column_type_'.$section['id'].'_'.$i;

					if( isset( $_POST[$string] ) ){
						$columns[ $i ] = $_POST[$string];
					}else{
						$columns[ $i ] = 'content';
					}
				}

				$sections[ $section['id'] ]['post_id'] = $post_id;
				$sections[ $section['id'] ]['columns'] = $columns;
			}



			//save the main section meta:
			update_post_meta( $post_id, 'sections', $sections );	
		}
			

		return true;
	}



	/**
	 * Add a section to this post + the builder
	 * 
	 * @param array $datas
	 * @return String (html of the new section)
	 */
	public function addSection( $datas = array() ){

		$this->init();
		$this->highestId += 1;

		//get the defaults:
		$args = $this->getDefaultSectionArgs();
		$args = wp_parse_args( $datas, $args );

		$columns = $this->getDefaultColumns( $args['view'] );
		if( isset( $datas['columns'] ) ){
			$args['columns'] = wp_parse_args( $datas['columns'], $columns );
		}else{
			$args['columns'] = $columns;
		}

		$section = new Section( $args );

		return $section->build();
	}



	public function switchView(){

//		$sections = get_post_meta( $this->postId, 'sections', true );


	}



	/**
	 * Save the order of metaboxes
	 * 
	 * @param  Array $order 
	 * @return bool (success / no success)
	 */
	public function saveOrder( $order ){



	}


	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/

	/**
	 * Fetches the info from the database and populates it with section-objects
	 * 
	 * @return array
	 */
	private function getSections(){

		$sections = get_post_meta( $this->postId, 'sections', true );

		$array = array();

		if( $sections ){

			foreach( $sections as $section ){

				//temp $args === $section
				$args = array(

						'id'		=> $section['id'],
						'position'	=> $section['position'],
						'title'		=> $section['title'],
						'view'		=> $section['view'],
						'post_id'	=> $section['post_id'],
						'columns'	=> $section['columns']
				);

				$array[] = new Section( $args );
		
			}
		}

		return $array;
	}


	/**
	 * Returns a filterable array of default settings
	 *
     * @filter 'chef_sections_default_section_args'
	 * @return array
	 */
	private function getDefaultSectionArgs(){

		global $post;
		if( isset( $post ) )
			$post_id = $post->ID;

		$args = array(

				'id'		=> $this->highestId,
				'position'	=> ( count( $this->sections ) + 1 ),
				'post_id'	=> $post_id,
				'title'		=> __( 'Sectie titel', 'chefsections' ),
				'view'		=> 'fullwidth'
		);

		$args = apply_filters( 'chef_sections_default_section_args', $args );

		return $args;
	}

	/**
	 * Get the default columns, based on the view
	 * 
	 * @param  string $view
	 * @return array
	 */
	private function getDefaultColumns( $view ){

		$viewTypes = $this->getViewTypes();
		$colCount = $viewTypes[ $view ];

		$arr = array();

		for( $i = 0; $i < $colCount; $i++ ){

			$key = $i + 1;
			$arr[ $key ] = 'content';

		}

		return $arr;
	}


	/**
	 * Loops through all sections and brings back the highest ID,
	 * making sure all ID's are unique
	 * 
	 * @return Int
	 */
	private function getHighestId(){

		$highID = 0;

		if( !empty( $this->sections ) ){

			foreach( $this->sections as $section ){

				if( $section->id > $highID )
					$highID = $section->id;

			}

		}

		return $highID;

	}

	/**
	 * Returns an array of possible Section Views
	 * 
	 * @return array
	 */
	public function getViewTypes(){

		//name - column count
		$arr = array(
						'fullwidth' => 1,
						'half-half' => 2,
						'sidebar-left' => 2,
						'sidebar-right' => 2,
						'three-columns' => 3,
						'four-columns' => 4
		);

		$arr = apply_filters( 'chef_sections_section_types', $arr );
		return $arr;

	}



	/**
	 * Check if this is a post type where sections are allowed
	 *
	 * @filter 'chef_sections_post_types'
	 * @return bool
	 */
	private function validPostType( $post_id ){

		$post_types = array( 'page' );
		$post_types = apply_filters( 'chef_sections_post_types', $post_types );

		return in_array( get_post_type( $post_id ), $post_types );
	}


}


?>