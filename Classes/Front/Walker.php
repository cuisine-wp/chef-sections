<?php

	namespace ChefSections\Front;

	use \stdClass;
	use \Cuisine\Utilities\Session;
	use \ChefSections\Wrappers\Template;
	use \ChefSections\SectionTypes\ContentSection;
	use \ChefSections\Collections\SectionCollection;

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


		/**
		 * Walk through all sections, get templates
		 *
		 * @return string ( html )
		 */
		public function walk(){

			ob_start();

			foreach( $this->collection->all() as $section ){

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
		* Get a single section
		*
		* @param int $postId
		* @param int $sectionId
		* 
		* @return string (html)
		*/
		public function getSection( $postId, $sectionId ){

			$template = false;
			$sections = $this->collection;

			//check if we need to load a new collection:
			if( $post_id !== $this->postId )
				$sections = new SectionCollection( $postId );


			//if the collection isn't empty:
			if( !$sections->empty() ){

				//get the section:
				$section = $sections->get( $sectionId );

				if( !is_null( $section ) ){

					ob_start();

						$section->beforeTemplate();

						//render it's template:
						Template::section( $section )->display();

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
		* @return string (html)
		*/
		public function getSectionsTemplate( $name ){

			$args = array(
				'name' => $name,
				'post_type' => 'section-template',
				'post_status' => 'publish',
				'showposts' => 1,
			);

			$posts = get_posts($args);

			if( !$posts )
				return false;


       		$template = $posts[0];

    		$this->setCollection( $template->ID );

			return self::walk();

		}



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
			if( $this->collection->empty() )
				return false;

			return true;
		}

		/********************************************/
		/**          Deprecated function            */
		/********************************************/

		public function get_section( $postId, $sectionId  )
		{
			_deprecated_function( __METHOD__, '3.0.0', 'getSection' );
			return $this->getSection( $postId, $sectionId  );
		}

		public function get_sections_template( $name )
		{
			_deprecated_function( __METHOD__, '3.0.0', 'getSectionsTemplate' );
			return $this->getSectionsTemplate( $name );
		}

	}



