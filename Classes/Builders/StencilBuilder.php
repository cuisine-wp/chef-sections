<?php

namespace ChefSections\Builders;

use ChefSections\Sections\Stencil;
use Cuisine\Wrappers\Metabox;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\PostType;
use WP_Query;
use stdClass;


/**
 * Stencils get there own builder:
 */
class StencilBuilder extends SectionsBuilder {


	/**
	 * Returns the correct Section class
	 * 
	 * @return ChefSections\Sections\Stencil
	 */
	public function getSectionType( $section ){

		return new Stencil( $section );

	}

	/**
	 * Applies templates forcefully to new posts of
	 * a certain post-type
	 * 
	 * @return void
	 */
	public static function applyTemplates( $post_id ){

		$post_type = ( isset( $_GET['post_type'] ) ? $_GET['post_type'] : 'post' );
		$template = $this->getTemplates( array( 'post_type' => $post_type ) );

		if( $template ){

			//Ugh, WP needs a post global to do this:
			$GLOBALS['post'] = new stdClass();
			$GLOBALS['post']->ID = $post_id;

			$this->loadTemplate( $template->ID );

		}

	}



	/**
	 * Get an array of approved template
	 * 
	 * @return array
	 */
	public static function getTemplates( $properties = array() ){

		$template = false;
		$ppp = 1;
		if( isset( $properties['multiple'] ) && $properties['multiple'] == true )
			$ppp = -1;

		$args = array( 'post_type' => 'section-template', 'posts_per_page' => $ppp );

 		$mq = array();

 		//suited for this post type:
		if( isset( $properties['post_type'] ) ){

			$mq[] = array(
							'key'		=>	 	'apply_to',
							'value'		=>		$properties['post_type']
			);

		}

		//suited for use in the admin:
		if( isset( $properties['dropdown'] ) ){

			$mq[] = array(
							'key'		=> 		'show_in_admin',
							'value'		=>		'true'
			);
		}


		if( !empty( $mq ) )
			$args['meta_query'] = $mq;


		$q = new WP_Query( $args );


		if( $q->have_posts() )
		
			$template = ( $ppp === 1 ? $q->post : $q->posts );


		return $template;


	}

}
