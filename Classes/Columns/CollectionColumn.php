<?php
namespace ChefSections\Columns;

use WP_Query;
use Cuisine\Utilities\Url;
use Cuisine\Utilities\Sort;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\Script;
use ChefSections\Wrappers\Template;
use ChefSections\Contracts\Column as ColumnContract;


/**
 * Collection column.
 * @package ChefSections\Columns
 */
class CollectionColumn extends DefaultColumn implements ColumnContract{

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


		//only reset the paged global if we're not in ajax-mode
		if( !defined( 'DOING_AJAX' ) ){

			//if our nav property is set, get pagination info:
			if( $this->getField( 'nav', 'none' ) != 'none' ){

				//get the paged variable from the original global query, else default to 0.
				$this->page = ( isset( $wp_the_query->query_vars['paged'] ) ? $wp_the_query->	query_vars['paged'] : 0 );

			}else{

				$this->page = 0;

			}
		}

		//force the 'publish' post-status
		$_status = apply_filters( 'chef_sections_collection_post_status', 'publish' );

		$args = array(
			'paged'				=> $this->page,
			'post_type'			=> $this->getField( 'post_type', 'post' ),
			'posts_per_page'	=> $this->getField( 'posts_per_page', 4 ),
			'orderby'			=> $this->getField( 'orderby', 'date' ),
			'post_status'		=> $_status
		);


		if( $this->getField( 'orderby', 'date' ) == 'title' )
			$args['order'] = 'ASC';


		//set the tax-query:
		if( $this->getField( 'taxonomies', false ) ){

			//get the taxonomies
			$taxonomies = $this->getField( 'taxonomies' );
			$tax_query = false;
			if( is_array( $taxonomies ) ){

				$tax_query = array( 'relation' => 'AND' );

				//reset the array to prevent problems:
				$taxonomies = array_values( $taxonomies );

				foreach( $taxonomies as $tax ){

					//add a new entry:
					$tax_query[] = array(
						'taxonomy' 	=> $tax['tax'],
						'field'		=> 'slug',
						'terms'		=> $tax['terms']
					);
				}
			}

			//if the tax_query array has been set:
			if( $tax_query )
				$args['tax_query'] = $tax_query;


		//backwards compatibility:
		}else{

			$category = $this->getField( 'category', 'all' );
			if( $category && $category !== 'all' )
				$args['category_name'] = $category;

		}

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
			echo '<div id="collection_'.esc_attr( $this->fullId ).'" class="'.esc_attr( $class ).'" '.$datas.'>';

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

		$msg = sprintf( __('No more %s found','chefsections'), strtolower( $type ) );
		$msg = apply_filters( 'chef_sections_autoload_message', $msg, $this );

		$html = '';

		$html .= 'data-id="'.esc_attr( $this->id ).'" ';
		$html .= 'data-section_id="'.esc_attr( $this->section_id ).'" ';
		$html .= 'data-page="'.esc_attr( $this->page ).'" ';
		$html .= 'data-post="'.esc_attr( $post->ID ).'" ';
		$html .= 'data-msg="'.esc_attr( $msg ).'" ';

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

		$this->theTitle();

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

				if( method_exists( $field, 'renderTemplate' ) ){
					echo $field->renderTemplate();
				}

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

			Field::title( 
				'title', 
				'',
				array(
					'label' 				=> false,
					'placeholder' 			=> 'Titel',
					'defaultValue'			=> $this->getField( 'title', ['text' => '', 'type' => 'h2']),
				)
			),

			Field::select(
				'post_type', //this needs a unique id
				__( 'Content type', 'chefsections' ),
				$this->getPostTypes(),
				array(
					'label'				=> 'top',
					'defaultValue' 		=> $this->getField( 'post_type', 'post' )
				)
			),


			Field::number(
				'posts_per_page',
				__( 'Number of posts', 'chefsections' ),
				array(
					'defaultValue'		=> $this->getField( 'posts_per_page', 4 )
				)
			),


			Field::number(
				'posts_per_row',
				__( 'Number of posts per row', 'chefsections' ),
				array(
					'defaultValue'		=> $this->getField( 'posts_per_row', 4 )
				)
			),


			Field::select(
				'orderby',
				__( 'Sort on', 'chefsections' ),
				$orderby,
				array(
					'defaultValue'		=> $this->getField( 'orderby', 'date' )

				)
			),

			Field::taxonomySelect(
				'taxonomies',
				__( 'Filter', 'chefsections' ),
				array(
					'defaultValue'		=> $this->getField( 'taxonomies', array() )
				)
			)
		);


		//make fields filterable
		$fields = apply_filters(
			'chef_sections_collection_column_fields',
			$fields,
			$this
		);

		return $fields;

	}



	/**
	 * Get all the subfields
	 *
	 * @return array
	 */
	private function getSubFields(){

		$view = array(
					'list' 		=> __( 'List', 'chefsections' ),
					'blocks'	=> __( 'Blocks', 'chefsections' ),
					'overview'	=> __( 'Blocks & rows', 'chefsections' )
		);

		$nav = array(
					'none'			=> __( 'None', 'chefsections' ),
					'pagination'	=> __( 'Pagination', 'chefsections' ),
					'autoload'		=> __( 'Endless Scroll', 'chefsections' )
		);


		$grid = array(
					'stretch'		=> __( 'Stretch', 'chefsections' ),
					'grid'			=> __( 'Regular', 'chefsections' ),
					'masonry'		=> __( 'Masonry', 'chefsections' )
		);


		$fields = array(

			'view'	=> Field::radio(
				'view',
				__( 'View', 'chefsections' ),
				$view,
				array(
					'defaultValue' => $this->getField( 'view', 'blocks' )
				)
			),


			'nav'	=> Field::radio(
				'nav',
				__( 'Navigation', 'chefsections' ),
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

		//make filterable
		$fields = apply_filters(
			'chef_sections_collection_side_fields',
			$fields,
			$this
		);

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