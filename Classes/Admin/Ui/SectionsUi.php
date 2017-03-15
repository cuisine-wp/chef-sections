<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Helpers\PostType;
	use ChefSections\Walkers\SectionCollection;

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


			if( !PostType::isValid( $this->postId ) )
				return false;

			wp_nonce_field( Session::nonceAction, Session::nonceName );


			echo '<div class="section-container" id="section-container">';

			$sections = $this->sections->getMainSections();

			if( !empty( $sections ) ){

				foreach( $sections as $section ){

					$section->build();

				}


			}else{

				echo '<div class="section-wrapper msg">';
					echo '<p>'.__('Nog geen secties aangemaakt.', 'chefsections').'</p>';
					echo '<span class="spinner"></span>';
				echo '</div>';
			
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