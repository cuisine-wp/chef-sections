<?php

	namespace ChefSections\Front;

	use \stdClass;
	use \Cuisine\Utilities\Session;
	use \ChefSections\Wrappers\Template;
	use \ChefSections\SectionTypes\ContentSection;
	use \ChefSections\Collections\SectionCollection;
	use \ChefSections\Collections\InContainerCollection;

	class Walker{


		/**
		 * The current post ID
		 * 
		 * @var int
		 */
		protected $postId;

		/**
		 * Section collection
		 * 
		 * @var ChefSections\Collections\SectionCollection;
		 */
		protected $collection;


		public function __construct()
		{
			$this->postId = Session::rootPostId();
			$this->setCollection( $this->postId );
		}

		/**
		 * Set the collection
		 *
		 * @return void
		 */
		public function setCollection( $postId )
		{
			$this->collection = new SectionCollection( $postId );	
		}

		/******************************************/
		/**           Section Walkers             */
		/******************************************/

		/**
		 * Walk through all sections, get templates
		 *
		 * @return string ( html )
		 */
		public function walk(){

			ob_start();

			foreach( $this->collection->getNonContainered() as $section ){

				$section->beforeTemplate();

					Template::section( $section )->display();

				$section->afterTemplate();
			}

			//reset post-data, to be sure:
			wp_reset_postdata();
			wp_reset_query();


			return apply_filters( 'chef_sections_output', ob_get_clean(), $this );
		}

		/**
		 * Get all sections in a container
		 * 
		 * @param  ChefSections\SectionTypes\Container $container
		 * 
		 * @return string (html)
		 */
		public function sectionsInContainer( $container )
		{
			
			$collection = new InContainerCollection( $this->postId, $container->id );

			ob_start();

			foreach( $collection->all() as $section ){

				$section->beforeTemplate();

					Template::section( $section )->display();

				$section->afterTemplate();
			}

			
			//reset post-data, to be sure:
			wp_reset_postdata();
			wp_reset_query();


			return apply_filters( 'chef_sections_container_output', ob_get_clean(), $this );	
		}


		/******************************************/
		/**           Section Getters             */
		/******************************************/

		/**
		* Get a single section
		*
		* @param int $postId
		* @param int $sectionId
		* @param string $path
		* 
		* @return string (html)
		*/
		public function getSection( $postId, $sectionId = null, $path = null ){

			$template = false;
			$sections = $this->collection;

			//check if we need to load a new collection:
			if( $postId !== $this->postId )
				$sections = new SectionCollection( $postId );


			//if the collection isn't empty:
			if( !$sections->isEmpty() ){

				//get the section, default the first if section Id turns out to be null
				$section = ( is_null( $sectionId ) ? $sections->first() : $sections->get( $sectionId ) );

				if( !is_null( $section ) ){

					ob_start();

						$section->beforeTemplate();

						if( is_null( $path ) ){
							Template::section( $section )->display();
						}else{
							Template::dynamic( $section, $path )->display();
						}

						$section->afterTemplate();

					$template = ob_get_clean();
				}
			}

			return $template;
		}


		/**
		* Get all sections in a template
		*
		* @param string $name of the post
		* @param string $path
		* 
		* @return string (html)
		*/
		public function getSectionsTemplate( $name, $path = null ){

			$args = array(
				'name' => $name,
				'post_type' => 'section-template',
				'post_status' => 'publish',
				'showposts' => 1,
			);

			$posts = get_posts($args);

			if( !$posts )
				return false;

       		$templatePost = $posts[0];

       		//get the first section of templatePost and send the optional path:
       		return $this->getSection( $templatePost->ID, null, $path );
		}



		/******************************************/
		/**           Column Walkers              */
		/******************************************/


		/**
		 * Walk through all columns of this section & get templates
		 *
		 * @param  \ChefSections\Columns\Column
		 * @return string ( html )
		 */
		public function columns( $section ){

			ob_start();

			foreach( $section->columns as $column ){

				$column->beforeTemplate();

				Template::column( $column )->display();

				$column->afterTemplate();

			}

			return ob_get_clean();
		}

		/**
		 * Get a template for a collection-block
		 *
		 * @param  \ChefSections\Columns\Column
		 * @return string ( html )
		 */
		public function block( $column ){

			Template::block( $column )->display();
		}


		/******************************************/
		/**           Helper functions            */
		/******************************************/

		/**
		 * Returns if this post has sections
		 *
		 * @return bool
		 */
		public function hasSections( $postId = null ){

			//set custom collection, if applicable:
			if( !is_null( $postId ) )
				$this->setCollection( $postId );

			//check if empty
			if( $this->collection->isEmpty() )
				return false;

			return true;
		}

		/**
		 * Return this walker's Post ID
		 * 
		 * @return int
		 */
		public function getPostId()
		{
			return $this->postId;
		}

		/**
		 * Set this walker's Post ID
		 * 
		 * @return void
		 */
		public function setPostId( $post_id )
		{
			$this->postId = $post_id;
		}

		/********************************************/
		/**          Deprecated function            */
		/********************************************/

		//replaced by getSection in this class
		public function get_section( $postId, $sectionId  )
		{
			_deprecated_function( __METHOD__, '3.0.0', 'getSection' );
			return $this->getSection( $postId, $sectionId  );
		}

		//replaced by getSectionsTemplate in this class
		public function get_sections_template( $name )
		{
			_deprecated_function( __METHOD__, '3.0.0', 'getSectionsTemplate' );
			return $this->getSectionsTemplate( $name );
		}

	}



