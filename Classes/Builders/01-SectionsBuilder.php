<?php

namespace ChefSections\Builders;

use ChefSections\Sections\Section;
use ChefSections\Sections\Reference;
use ChefSections\Sections\Blueprint;
use ChefSections\Sections\Stencil;
use ChefSections\Wrappers\ReferenceBuilder;
use Cuisine\Utilities\Session;
use Cuisine\Utilities\Sort;

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
	public $sections = array();


	/**
	 * Post ID
	 *
	 * @var int
	 */
	public $postId = null;

	/**
	 * Keep the sections id's unique and get the highest
	 * 
	 * @var int;
	 */
	public $highestId;



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
	public function init(){
		global $post;

		if( isset( $post ) ){
			
			$this->postId = $post->ID;	
			
			$this->sections = $this->getSections();

			$this->highestId = $this->getHighestId();
		
		}

		return $this;
	}


	/*=============================================================*/
	/**             Metabox functions                              */
	/*=============================================================*/

	/**
	 * Set each of the admin-sections
	 *
	 * @return void (echoes html)
	 */
	public function build( $controls = true ){


		if( !$this->validPostType( $this->postId ) )
			return false;

		wp_nonce_field( Session::nonceAction, Session::nonceName );

		echo '<div class="section-container" id="section-container">';


		if( !empty( $this->sections ) ){

			
			foreach( $this->sections as $section ){

				$section->build();
			}


		}else{

			echo '<div class="section-wrapper msg">';
				echo '<p>'.__('Nog geen secties aangemaakt.', 'chefsections').'</p>';
				echo '<span class="spinner"></span>';
			echo '</div>';
		
		}

		echo '</div>';

		if( $controls )
			$this->addSectionButton();

	}


	/**
	 * Add a button at the bottom:
	 *
	 * @return void
	 */
	private function addSectionButton(){

		$args = array( 'multiple' => true, 'dropdown' => true );
		$templates = ReferenceBuilder::getTemplates( $args );


		echo '<div class="section-wrapper dotted-bg">';

			echo '<div id="addSection" class="section-btn" data-post_id="'.$this->postId.'">';
				_e( 'Sectie toevoegen', 'chefsections' );
			echo '</div>';

			echo '<em>'.__( 'Of', 'chefsections' ).'</em>';

			//add templates:
			echo '<label>'.__( 'Gebruik een sjabloon', 'chefsections' ).':</label>';
			echo '<select id="getTemplate" data-post_id="'.$this->postId.'">';

				echo '<option value="none">'.__( 'Selecteer sjabloon', 'chefsections' ).'</option>';

				foreach( $templates as $template ){

					if( $template->ID != $this->postId ){
						echo '<option value="'.$template->ID.'">';
							echo $template->post_title;
						echo '</option>';
					}
				}


			echo '</select>';

		echo '</div>';
	}


	/**
	 * Rebuild the sections builder after a template has been applied
	 * 
	 * @return string (html)
	 */
	public function rebuild(){

		//reset everything:
		$this->init();


		//build all the sections up again:
		ob_start();

			$this->build( false ); //don't add the controls:

		return ob_get_clean();

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


	/*=============================================================*/
	/**    Section create, delete & view-change                    */
	/*=============================================================*/


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

		//save this section:
		$_sections = get_post_meta( $this->postId, 'sections', true );
		$_sections[ $args['id'] ] = $args;
		update_post_meta( $this->postId, 'sections', $_sections );


		$section = new Section( $args );

		return $section->build();
	}



	/**
	 * Delete section
	 * 
	 * @return void
	 */
	public function deleteSection(){

		$section_id = $_POST['section_id'];
		$_sections = get_post_meta( $this->postId, 'sections', true );
		unset( $_sections[ $section_id ] );
		update_post_meta( $this->postId, 'sections', $_sections );
		echo 'true';
	}


	/**
	 * Get the new section view 
	 * 
	 * @return string
	 */
	public function changeView(){

		$section_id = $_POST['section_id'];
		$view = $_POST['view'];

		$_sections = get_post_meta( $this->postId, 'sections', true );
		$_sections[ $section_id ]['view'] = $view;

		//add columns if needed:
		$default = $this->getDefaultColumns( $view );
		$existing = $_sections[ $section_id ]['columns'];
		$new = array();

		foreach( $default as $key => $col ){

			if( !isset( $existing[ $key ] ) ){
				$new[ $key ] = $default[ $key ];
			}else{
				$new[ $key ] = $existing[ $key ];
			}
		}
		
		$_sections[ $section_id ]['columns'] = $new;

		update_post_meta( $this->postId, 'sections', $_sections );

		$section = new Section( $_sections[ $section_id ] );
		return $section->build();
	
	}


	/**
	 * Save the order of metaboxes
	 * 
	 * @return bool (success / no success)
	 */
	public function sortSections(){

		$ids = $_POST['section_ids'];

		//save this section:
		$_sections = get_post_meta( $this->postId, 'sections', true );
		
		$i = 1;
		foreach( $ids as $section_id ){
			$_sections[ $section_id ]['position'] = $i;
			$i++;
		}

		update_post_meta( $this->postId, 'sections', $_sections );
	}




	/*=============================================================*/
	/**             Getters & Setters                              */
	/*=============================================================*/

	/**
	 * Fetches the info from the database and populates it with section-objects
	 * 
	 * @return array
	 */
	public function getSections(){

		$sections = get_post_meta( $this->postId, 'sections', true );
		$array = array();


		if( is_array( $sections ) && !empty( $sections ) ){
		
			$sections = Sort::byField( $sections, 'position', 'ASC' );
		
			if( $sections ){

				foreach( $sections as $section ){
					
					$array[] = $this->getSectionType( $section );
				
				}
			}
		}

		return $array;
	}


	/**
	 * Returns the correct Section class
	 * 
	 * @return Section / Reference / Layout / Stencil
	 */
	public function getSectionType( $section ){

		if( !isset( $section['type'] ) )
			$section['type'] = 'section';


		switch( $section['type'] ){

			case 'reference':

				return new Reference( $section );

			break;
			case 'blueprint':

				return new Blueprint( $section );

			break;
			case 'stencil':

				return new Stencil( $section );

			break;
			default:

				return new Section( $section );

			break;


		}
	}



	/**
	 * Returns a filterable array of default settings
	 *
     * @filter 'chef_sections_default_section_args'
	 * @return array
	 */
	public function getDefaultSectionArgs(){

		global $post;
		if( isset( $post ) )
			$post_id = $post->ID;

		$args = array(

				'id'				=> $this->highestId,
				'position'			=> ( count( $this->sections ) + 1 ),
				'post_id'			=> $post_id,
				'title'				=> __( 'Sectie titel', 'chefsections' ),
				'hide_title'		=> apply_filters('chef_sections_hide_title', false ),
				'hide_container'	=> apply_filters('chef_sections_hide_container', true ),
				'view'				=> 'fullwidth',
				'type'				=> 'section'
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
	public function getHighestId(){

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
	 * Return a section template name
	 * 
	 * @param  int $section_id
	 * @return string ( template name )
	 */
	public function getTemplateName( $section_id ){

		$name = '';



		foreach( $this->sections as $section ){

			if( $section->id === $section_id ){

				if( $section->type == 'stencil' || $section->type == 'reference' ){

					$_templatePost = get_post( $section->post_id );
					$name = $section->type.DS.$_templatePost->post_name.'-';

				}

				if( isset( $section->name ) && $section->name != '' )
					$name .= $section->name;
				
				return $name;
			
			}
		}


		return 'section-'.$section_id;
	}



	/**
	 * Check if this is a post type where sections are allowed
	 *
	 * @filter 'chef_sections_post_types'
	 * @return bool
	 */
	private function validPostType( $post_id ){

		$post_types = array( 'page', 'section-template' );
		$post_types = apply_filters( 'chef_sections_post_types', $post_types );

		return in_array( get_post_type( $post_id ), $post_types );
	}


}


?>