<?php

namespace ChefSections\Sections;

use ChefSections\Sections\Section;

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

		if( !$this->validPostType() )
			return false;

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

		echo SectionsBuilder::addSection();

		echo '<div class="section-wrapper dotted-bg">';

			echo '<div id="addSection" class="button">';
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
	public function saveSections(){

		if( !$this->validPostType() )
			return false;


		$no_problems = true;

		foreach( $this->sections as $section ){

			if( ! $section->save() )
				$no_problems = false;

		}

		return $no_problems;
	}



	/**
	 * Add a section to this post + the builder
	 * 
	 * @param array $datas
	 * @return String (html of the new section)
	 */
	public function addSection( $datas = array() ){

		$this->highestId += 1;

		//get the defaults:
		$args = $this->getDefaultSectionArgs();
		$args = wp_parse_args( $datas, $args );


		$section = new Section( $args );

		return $section->build();
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
		$args = array(

				'id'		=> $this->highestId,
				'position'	=> ( count( $this->sections ) + 1 ),
				'post_id'	=> $post->ID,
				'title'		=> __( 'Sectie', 'chefsections' ),
				'view'		=> 'fullwidth',
				'columns'	=> array()
		);

		$args = apply_filters( 'chef_sections_default_section_args', $args );

		return $args;
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

				if( $section['id'] > $highID )
					$highID = $section['id'];

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

		$arr = array(
						'fullwidth',
						'half-half',
						'sidebar-left',
						'sidebar-right',
						'three-columns',
						'four-columns'
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
	private function validPostType(){

		$post_types = array( 'page' );
		$post_types = apply_filters( 'chef_sections_post_types', $post_types );

		return in_array( get_post_type( $this->postId ), $post_types );
	}


}


?>