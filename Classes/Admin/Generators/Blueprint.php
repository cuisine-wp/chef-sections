<?php

	namespace ChefSections\Admin\Generators;

	use WP_Query;
	use ChefSections\SectionTypes\Blueprint as BlueprintSection;
	use ChefSections\Contracts\Generator as GeneratorContract;

	class Blueprint extends BaseGenerator implements GeneratorContract{


		/**
		 * Array of all blueprints
		 * 
		 * @var Array
		 */
		protected $blueprints;


		/**
		 * Constructor for this class
		 *
		 * @param int $postId
		 *
		 * @return ChefSections\Admin\Generators\Blueprint
		 */
		public function __construct( $postId )
		{
			parent::__construct( $postId );
			$this->blueprints = $this->getBlueprints();
		}

	

		/**
		 * Checks wether this blueprint applies to the current post
		 * 
		 * @return bool
		 */
		public function check()
		{
			if( parent::check() && !is_null( $this->blueprints ) )
				return true;

			return false;
		}


		/**
		 * Load sections from a template
		 * 
		 * @return bool
		 */
		public function generate( $templateId = null ){

			//check for a template-id via POST
			if( $templateId == null && isset( $_POST['template_id'] ) )
				$templateId = $_POST['template_id'];

			//no template id? though luck.
			if( $templateId == null )
				$templateId = $this->blueprints->ID;

			if( $templateId == null )
				return false;


			$_sections = get_post_meta( $templateId, 'sections', true );

			//save the column data:
			foreach( $_sections as $key => $_section ){

				//change the post id at the start;
				$_sections[ $key ]['post_id'] = $this->postId;
				$_sections[ $key ]['type'] = 'blueprint';
				

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
		public function getBlueprints( $props = array() ){

			if( isset( $props['post_type'] ) )
				$props['post_type'] = ( isset( $_GET['post_type'] ) ? $_GET['post_type'] : get_post_type( $this->postId ) );

			$template = null;
			$ppp = 1;

			//set the meta-query object:
	 		$mq = array();
	 		$mq[] = array(
	 			'key'		=> 'type',
	 			'value'		=> 'blueprint'
	 		);


	 		//suited for this post type:
			if( isset( $props['post_type'] ) ){

				$mq[] = array(
								'key'		=>	 	'apply_to',
								'value'		=>		$props['post_type']
				);

			}


			$args = array( 
				'post_type' => 'section-template', 
				'posts_per_page' => 1,
				'meta_query' => $mq
			);

			$q = new WP_Query( $args );


			if( $q->have_posts() )
				$template = $q->post;


			return $template;
		}


	}