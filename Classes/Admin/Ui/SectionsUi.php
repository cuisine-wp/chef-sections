<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Helpers\PostType;
	use ChefSections\Collections\SectionCollection;
	use ChefSections\Admin\Ui\Sections\ContentSectionUi;
	use ChefSections\Admin\Ui\Sections\ContainerSectionUi;
	use ChefSections\Admin\Ui\Sections\ReferenceSectionUi;
	use ChefSections\Admin\Ui\Sections\BlueprintSectionUi;


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

			$sections = $this->sections->getNonContainered();

			if( !empty( $sections ) ){

				foreach( $sections as $section ){

					//find the right section UI class and run the build command
					$this->getSectionUiClass( $section )->build();

				}


			}else{

				echo '<div class="section-wrapper msg">';
					echo '<p>'.__('No sections yet.', 'chefsections').'</p>';
					echo '<span class="spinner"></span>';
				echo '</div>';
			
			}

			echo '</div>';

		}


		/**
		 * Returns an instance of the right UI Class
		 *
		 * @param ChefSections\SectionTypes\BaseSection $section
		 * 
		 * @return ChefSections\Admin\Ui\Sections\BaseSectionUI
		 */
		public function getSectionUiClass( $section )
		{
			switch( $section->type ){

				case 'reference':

					return new ReferenceSectionUi( $section );
					break;

				case 'container':

					return new ContainerSectionUi( $section );
					break;

				case 'blueprint':

					return new BlueprintSectionUi( $section );
					break;

				default:

					return new ContentSectionUi( $section );
					break;
			}
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