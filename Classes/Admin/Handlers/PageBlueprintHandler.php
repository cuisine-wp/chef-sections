<?php

	namespace CuisineSections\Admin\Handlers;

	use WP_Query;
	use CuisineSections\SectionTypes\PageBlueprint as BlueprintSection;

	class PageBlueprintHandler extends BaseHandler{


		/**
		 * Array of all blueprints
		 * 
		 * @var Array
		 */
		protected $blueprints;


		/**
		 * Construct this class
		 * 
		 */
		public function __construct( $postId = null )
		{

			$this->postId = ( is_null( $postId ) ? Session::postId() : $postId );
			$this->setCollection();

			return $this;
		}

		/**
		 * Set the collection for this class
		 *
		 * @return void
		 */
		public function setCollection()
		{
			$this->collection = $this->getBlueprints();
		}

	

		/**
		 * Maybe generate a new post-type post out of a blueprint
		 * 
		 * @return bool
		 */
		public function maybeGenerate()
		{
			if( $this->check() )
				return $this->generate();

			return false;
		}



		/**
		 * Checks wether this blueprint applies to the current post
		 * 
		 * @return bool
		 */
		public function check()
		{
			if( 
				!is_null( $this->collection ) &&
				$this->checkPage() && 
				$this->validPostType()
			){
				return true;
			}

			return false;
		}

		/**
		 * Checks if this is the page this handler should be run
		 * 
		 * @return bool
		 */
		public function checkPage()
		{

			global $pagenow;
			$status = get_post_status( $this->postId );

			if( $status == 'auto-draft' && $pagenow == 'post-new.php' )
				return true;

			return false;
		}

		/**
		 * Check the validity of the post type we're applying this to
		 * 
		 * @return bool
		 */
		public function validPostType()
		{
			$postType = ( isset( $_GET['post_type'] ) ? $_GET['post_type'] : get_post_type( $this->postId ) );
			$applyTo = get_post_meta( $this->collection->ID, 'apply_to', true );

			if( $postType == $applyTo )
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
				$templateId = $this->collection->ID;

			if( $templateId == null )
				return false;


			$_sections = get_post_meta( $templateId, 'sections', true );

			//save the column data:
			foreach( $_sections as $key => $_section ){

				//change the post id at the start;
				$_sections[ $key ]['post_id'] = $this->postId;
				//$_sections[ $key ]['type'] = 'blueprint';
				

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

			if( !isset( $props['post_type'] ) )
				$props['post_type'] = ( isset( $_GET['post_type'] ) ? $_GET['post_type'] : get_post_type( $this->postId ) );

			$template = null;
			$ppp = 1;

			//set the meta-query object:
	 		$mq = array();


	 		//suited for this post type:
			if( isset( $props['post_type'] ) ){

				$mq[] = array(
								'key'		=>	 	'apply_to',
								'value'		=>		$props['post_type']
				);

			}


			$args = array( 
				'post_type' => 'page-template', 
				'posts_per_page' => 1,
				'meta_query' => $mq
			);

			$q = new WP_Query( $args );


			if( $q->have_posts() )
				$template = $q->post;


			return $template;
		}


	}