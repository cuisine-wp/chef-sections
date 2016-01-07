<?php
namespace ChefSections\Columns;

use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\Script;
use ChefSections\Wrappers\Template;
use Cuisine\Utilities\Url;
use Cuisine\Utilities\Sort;
use WP_Query;

/**
 * Collection column.
 * @package ChefSections\Columns
 */
class CollectionColumn extends DefaultColumn{

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type = 'collection';

	/**
	 * Current page number
	 *
	 * @var int
	 */
	public $page = 1;


	/**
	 * Cache queries made for this column
	 * 
	 * @var boolean/WP_Query
	 */
	private $query = false;


	/**
	 * Cache the post global to prevent conflicts
	 * 
	 * @var Object
	 */
	private $globalPost = '';


	/*=============================================================*/
	/**             Query                                          */
	/*=============================================================*/

	/**
	 * Get the query for this collection 
	 * 
	 * @return WP_Query
	 */
	public function getQuery(){

		global $wp_the_query;

		//return the cached query if it exists:
		if( $this->query && ( !defined('DOING_AJAX') || DOING_AJAX === false ) )
			return $this->query;


		//if our nav property is set, get pagination info:
		if( $this->getField( 'nav', 'none' ) != 'none' ){

			//get the paged variable from the original global query, else default to 0.
			$this->page = ( isset( $wp_the_query->query_vars['paged'] ) ? $wp_the_query->query_vars['paged'] : 0 );
			
		}else{
			
			$this->page = 0;

		}


		//else, create a new query
		$args = array(
					'paged'				=> $this->page,
					'post_type'			=> $this->getField( 'post_type', 'post' ),
					'posts_per_page'	=> $this->getField( 'posts_per_page', 4 ),
					'orderby'			=> $this->getField( 'orderby', 'date' ),
		);


		if( $this->getField( 'orderby', 'date' ) == 'title' )
			$args['order'] = 'ASC';


		$category = $this->getField( 'category', 'all' );
		if( $category && $category !== 'all' )
			$args['category_name'] = $category;


		$args = apply_filters( 'chef_sections_collection_query', $args, $this );
		
		$this->query = new WP_Query( $args );

		return $this->query;
	}
	


	/*=============================================================*/
	/**             Template                                       */
	/*=============================================================*/


	/**
	 * Start the collection wrapper
	 * 
	 * @return string ( html, echoed )
	 */
	public function beforeTemplate(){

		//cache the post global to prevent conflicts:
		global $post;
		$this->globalPost = $post;


		$nav = $this->getField( 'nav', 'pagination' );
		$datas = $this->getDatas();

		//get the class:
		$class = 'collection ';
		$class .= $this->getField( 'view', 'blocks' ).' ';
		$class .= $this->getField( 'grid', 'grid' );

		if( $nav == 'autoload' )
			$class .= ' autoload';

		if( $nav !== 'autoload' || $this->page == 1 )
			echo '<div id="collection_'.$this->fullId.'" class="'.$class.'" '.$datas.'>';

	}



	/**
	 * Add javascripts to the footer, before the template
	 * and close the div wrapper
	 * 
	 * @return string ( html, echoed )
	 */
	public function afterTemplate(){

		$url = Url::plugin( 'chef-sections', true ).'Assets/js/collections/';
		$grid = $this->getField( 'grid', 'grid' );
		$nav = $this->getField( 'nav', 'pagination' );

		if( $grid == 'masonry' )
			Script::register( 'masonry_blocks', $url.'masonry', true );	
					

		if( $nav == 'autoload' )
			Script::register( 'autoload_blocks', $url.'autoload', true );

	
		if( $nav !== 'autoload' || $this->page == 1 ){

			if( $nav === 'autoload' )
				Template::element( 'loader' )->display();

			//closing div:
			echo '</div>';
		}

		//reset the post global to prevent conflicts:
		global $post;
		$post = $this->globalPost;
		setup_postdata( $this->globalPost );
	}


	/**
	 * Get the data attributes for this column
	 * 
	 * @return string
	 */
	public function getDatas(){

		global $post;

		$post_type = $this->getField( 'post_type', 'post' );
		$types = $this->getPostTypes();
		$amount = $this->getField( 'posts_per_page', 4 );

		$type = ( !is_array( $post_type ) ? $types[ $post_type ] : 'berichten' );

		$msg = 'Geen '.strtolower( $type ).' meer gevonden';
		$msg = apply_filters( 'chef_sections_autoload_message', $msg, $this );

		$html = '';

		$html .= 'data-id="'.$this->id.'" ';
		$html .= 'data-section_id="'.$this->section_id.'" ';
		$html .= 'data-page="'.$this->page.'" ';
		$html .= 'data-post="'.$post->ID.'" ';
		$html .= 'data-msg="'.$msg.'" ';

		return $html;
	}


	/*=============================================================*/
	/**             Backend                                        */
	/*=============================================================*/


	/**
	 * Generate a graphic depiction of the collection
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildPreview(){

		$view = $this->getField( 'view', 'blocks' );
		$grid = $this->getField( 'grid', 'stretch' );

		if( $this->getField( 'title' ) )	
			echo '<strong>'.$this->getField( 'title' ).'</strong>';

		switch( $view ){

			case 'list':
				echo '<span class="dashicons dashicons-editor-ul"></span>';
				break;

			case 'blocks':
				echo '<div class="blocks-preview">';
					echo '<span class="brick"></span>';
					echo '<span class="brick"></span>';
					echo '<span class="brick"></span>';
					echo '<span class="brick"></span>';
				echo '</div>';
				break;

			case 'overview':

				if( $grid == 'masonry' ){
					echo '<span class="dashicons dashicons-tagcloud"></span>';
				}else{
					echo '<span class="dashicons dashicons-screenoptions"></span>';
				}
				break;
		}

		$pts = $this->getField( 'post_type' );
		if( is_array( $pts ) )
			$pts = implode( ', ', $pts );

		$details = 'Post type: '.$pts.' | ';
		$details .= 'Aantal berichten: '.$this->getField( 'posts_per_page' );

		echo '<span class="details">'.$details.'</span>';
	}

	/**
	 * Build the contents of the lightbox for this column
	 * 
	 * @return string ( html, echoed )
	 */
	public function buildLightbox(){

		$fields = $this->getFields();
		$subfields = $this->getSubFields();

		echo '<div class="main-content">';
		
			foreach( $fields as $field ){

				$field->render();

			}

		echo '</div>';
		echo '<div class="side-content">';

			foreach( $subfields as $field ){

				$field->render();

			}

			$this->saveButton();

		echo '</div>';
	}


	/**
	 * Get the fields for this column
	 * 
	 * @return array
	 */
	public function getFields(){


		$orderby = array(

				'date'			=> __( 'Datum', 'chefsections' ),
				'title'			=> __( 'Titel', 'chefsections' ),
				'rand'			=> __( 'Random', 'chefsections' )

		);

		$fields = array(


			'title' => Field::text( 
				'title', 
				'',
				array(
					'label' 				=> false,
					'placeholder' 			=> 'Titel',
					'defaultValue'			=> $this->getField( 'title' ),
				)
			),

			'post_type' => Field::select( 
				'post_type', //this needs a unique id 
				__( 'Content type', 'chefsections' ),
				$this->getPostTypes(),
				array(
					'label'				=> 'top',
					'defaultValue' 		=> $this->getField( 'post_type', 'post' )
				)
			),


			'posts_per_page' => Field::number(
				'posts_per_page',
				__( 'Aantal berichten', 'chefsections' ),
				array(
					'defaultValue'		=> $this->getField( 'posts_per_page', 4 )
				)
			),


			'posts_per_row'	=> Field::number(
				'posts_per_row',
				__( 'Aantal berichten per rij', 'chefsections' ),
				array(
					'defaultValue'		=> $this->getField( 'posts_per_row', 4 )
				)
			),


			'orderby' => Field::select(
				'orderby',
				__( 'Sorteer op', 'chefsections' ),
				$orderby,
				array(
					'defaultValue'		=> $this->getField( 'orderby', 'date' )

				)
			)
		);


		if( $this->getField( 'post_type', 'post' ) === 'post' ){

			$categories = get_categories();
			$keys = Sort::pluck( $categories, 'slug' );
			$labels = Sort::pluck( $categories, 'name' );
			
			$cats = array_combine( $keys, $labels );
			$cats = array_merge( array( 'all' => 'Alles' ), $cats );

			$fields['category'] = Field::select(
					'category',
					__( 'Categorie', 'chefsections' ),
					$cats,
					array(
						'defaultValue'	=> $this->getField( 'category', 'all' )
					)
			);
		}


		$fields = apply_filters( 'chef_sections_collection_column_fields', $fields, $this );
		return $fields;

	}



	/**
	 * Get all the subfields
	 * 
	 * @return array
	 */
	private function getSubFields(){

		$view = array(
					'list' 		=> __( 'Lijst', 'chefsections' ),
					'blocks'	=> __( 'Blokken', 'chefsections' ),
					'overview'	=> __( 'Blokken met rijen', 'chefsections' )
		);

		$nav = array(
					'none'			=> __( 'Geen', 'chefsections' ),
					'pagination'	=> __( 'Paginering', 'chefsections' ),
					'autoload'		=> __( 'Endless Scroll', 'chefsections' )
		);


		$grid = array(
					'stretch'		=> __( 'Strak', 'chefsections' ),
					'grid'			=> __( 'Regulier', 'chefsections' ),
					'masonry'		=> __( 'Masonry', 'chefsections' )	
		);


		$fields = array(

			'view'	=> Field::radio(
				'view',
				__( 'Weergave', 'chefsections' ),
				$view,
				array(
					'defaultValue' => $this->getField( 'view', 'blocks' )
				)
			),


			'nav'	=> Field::radio(
				'nav',
				__( 'Navigatie', 'chefsections' ),
				$nav,
				array(
					'defaultValue'	=> $this->getField( 'nav', 'none' )
				)
			),

			'grid'	=> Field::radio(
				'grid',
				__( 'Grid Type', 'chefsections' ),
				$grid,
				array(
					'defaultValue'	=> $this->getField( 'grid', 'stretch' )
				)
			)


		);

		$fields = apply_filters( 'chef_sections_collection_side_fields', $fields, $this );
		return $fields;

	}


	/*=============================================================*/
	/**             Getters, Setters                               */
	/*=============================================================*/


	/**
	 * Set the page number
	 * 
	 * @param integer $num
	 */
	public function setPage( $num = 1 ){
		$this->page = $num;
	}


	
	/**
	 * Get post types as key / value pairs
	 * 
	 * @return array
	 */
	public function getPostTypes(){

		$pts = get_post_types( array( 'public' => true ) );
		$arr = array();
		foreach( $pts as $post_type ){
			
			$obj = get_post_type_object( $post_type );
			$arr[$post_type] = $obj->labels->name;

		}

		unset( $arr['attachment'] );
		unset( $arr['form'] );
		unset( $arr['section-template'] );

		return $arr;

	}


}