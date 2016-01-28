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
	public function applyTemplates( $post_id ){

		$post_type = ( isset( $_GET['post_type'] ) ? $_GET['post_type'] : 'post' );
		$template = $this->getTemplates( array( 'post_type' => $post_type ) );

		if( $template ){

			$this->postId = $post_id;
			$this->loadTemplate( $template->ID );

		}

	}



	/**
	 * Load sections from a template
	 * 
	 * @return bool
	 */
	public function loadTemplate( $templateId = null ){

		//check for a template-id via POST
		if( $templateId == null && isset( $_POST['template_id'] ) )
			$templateId = $_POST['template_id'];

		//no template id? though luck.
		if( $templateId == null )
			return false;


		$_sections = get_post_meta( $templateId, 'sections', true );

		//save the column data:
		foreach( $_sections as $_section ){

			if( !empty( $_section['columns'] ) ){

				foreach( $_section['columns'] as $key => $column ){

					$fullId = $_section['id'].'_'.$key;

					//get the column properties:
					$props = get_post_meta( 
						$templateId, 
						'_column_props_'.$fullId, 
						true
					);

					//add 'em to the new column:
					update_post_meta( $this->postId, '_column_props_'.$fullId, $props );
				}

			}
		}

		//save the sections as our own:
		update_post_meta( $this->postId, 'sections', $_sections );

		return true;
	}



	/**
	 * Get an array of approved template
	 * 
	 * @return array
	 */
	public function getTemplates( $properties = array() ){

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
