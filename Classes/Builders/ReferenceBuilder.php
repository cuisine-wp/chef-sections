<?php

namespace ChefSections\Builders;

use ChefSections\Sections\Reference;
use Cuisine\Wrappers\Metabox;
use Cuisine\Wrappers\Field;
use Cuisine\Wrappers\PostType;
use WP_Query;
use stdClass;


/**
 * Stencils get there own builder:
 */
class ReferenceBuilder extends SectionsBuilder {


	/**
	 * Add a reference section
	 *
	 * @return string (html of new section)
	 */
	public function addReference( $templateId = null ){
	
		//check for a template-id via POST
		if( isset( $_POST['template_id'] ) )
			$templateId = $_POST['template_id'];
	
		//no template id? though luck.
		if( $templateId == null )
			return false;
	
	
		$this->init();
		$this->highestId += 1;
	
		//get the defaults:
		$args = $this->getDefaultSectionArgs();
		$parent = array_values( get_post_meta( $templateId, 'sections', true ) );
	
		//only if theres a section here:
		if( isset( $parent[0] ) ){
	
			$parent = $parent[0];
			$columns = $parent['columns'];
	
	
			$args['title'] = $parent['title'];
			$args['view'] = $parent['view'];
			$args['hide_title'] = $parent['hide_title'];
			$args['hide_container'] = $parent['hide_container'];
			$args['template_id'] = $templateId;
			$args['type'] = 'reference';
			$args['columns'] = $parent['columns'];
	
	
			//save this section:
			$_sections = get_post_meta( $this->postId, 'sections', true );
			$_sections[ $args['id'] ] = $args;
			update_post_meta( $this->postId, 'sections', $_sections );
	
	
			$section = new Reference( $args );
	
			return $section->build();
		}
	}



	/**
	 * Get an array of approved templates
	 * 
	 * @return array
	 */
	public function getTemplates( $properties = array() ){

		$template = false;

 		$mq = array();
 		$mq[] = array(
 			'key'		=> 'type',
 			'value'		=> 'reference'
 		);

 		//suited for this post type:
		if( isset( $properties['post_type'] ) ){

			$mq[] = array(
							'key'		=>	 	'apply_to',
							'value'		=>		$properties['post_type']
			);

		}
		
		$args = array( 
			'post_type' => 'section-template', 
			'posts_per_page' => -1,
			'meta_query' => $mq
		);

		$q = new WP_Query( $args );


		if( $q->have_posts() )
			$template = $q->posts;


		return $template;
	}
}
