<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Helpers\PostType;
	use ChefSections\Helpers\SectionUi as SectionUiHelper;
	use ChefSections\Collections\SectionCollection;


	class SectionsUi{
		

		/**
		 * Sections Walker
		 * 
		 * @var ChefSections\Walkers\Admin
		 */
		protected $sections;

		/**
		 * Post ID
		 *
		 * @var int
		 */
		public $postId = null;


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
				$this->sections = new SectionCollection( $this->postId );

			}

			return $this;
		}


		/**
		 * Set each of the admin-sections
		 *
		 * @return void (echoes html)
		 */
		public function build(){


			wp_nonce_field( Session::nonceAction, Session::nonceName );

			echo '<div class="section-flow section-sortables" id="main-section-container">';


				echo '<div class="no-section-msg msg hidden">';
					echo '<p>'.__('No sections yet.', 'chefsections').'</p>';
				echo '</div>';


			$sections = $this->sections->getNonContainered();


			foreach( $sections as $section ){

				//find the right section UI class and run the build command
				$instance = SectionUiHelper::getClass( $section );
                if( !is_null( $instance ) ){
                    $instance->build();
                }
			}

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

				$this->build();

			return ob_get_clean();

		}




	}
