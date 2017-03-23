<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Collections\ContainerCollection;
	use ChefSections\Collections\SectionBlueprintCollection;


	class Toolbar{

		/**
		 * Current post id
		 * 
		 * @var int
		 */
		protected $postId;

		/**
		 * Template collection
		 * 
		 * @var ChefSections\Collections\ReferenceCollection
		 */
		protected $templates;

		/**
		 * Container collection
		 * 
		 * @var ChefSections\Collections\ContainerCollection
		 */
		protected $containers;


		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->templates = ( new SectionBlueprintCollection() )->toArray();
			$this->containers = ( new ContainerCollection() )->toArray();
			$this->postId = Session::postId();
		}


		/**
		 * Build the toolbar
		 * 
		 * @return string (html, echoed)
		 */
		public function build()
		{

			echo '<div class="section-toolbar dotted-bg" id="section-builder-ui">';

				$this->createSectionButton();

				$this->createTemplateButton();

				$this->createContainerButton();

				echo '<span class="update-btn-wrapper">';
					
					echo '<span class="update-btn" id="updatePost">';
						_e( 'Update' );
					echo '</span>';

				echo '</span>';

				//loader
				echo '<span class="spinner"></span>';

			echo '</div>';

			//add template json:
			echo '<script>';
				echo 'var SectionTemplates = '.$this->templateJson().';';
				echo 'var SectionContainers = '.$this->containerJson().';';
			echo '</script>';

		}

		/**
		 * Returns the HTML for a section button
		 * 
		 * @return string (html, echoed)
		 */
		public function createSectionButton()
		{
			echo '<div class="add-section-btn" data-action="createSection" data-post_id="'.$this->postId.'">';
				echo '<span class="dashicons dashicons-plus-alt"></span>';
				_e( 'Add Section', 'chefsections' );
			echo '</div>';

		}

		/**
		 * Returns the HTML for the template button
		 * 
		 * @return string (html, echoed)
		 */
		public function createTemplateButton()
		{

			if( !$this->templates->empty() ){

				echo '<div class="add-section-btn secondary-btn" ';
				echo 'data-post_id="'.$this->postId.'" ';
				echo 'data-action="addSectionTemplate" ';

				if( $this->templates->count() > 1 ){				
					echo 'data-type="search" ';
					echo 'data-content="SectionTemplates" ';
					echo 'data-label="'.__( 'Please select your template', 'chefsections' ).'" ';

				}else{
					$template = $this->templates->toArray()->first();
					echo 'data-template_id="'.$template['ID'].'" ';
				
				}

				echo '>';

					echo '<span class="dashicons dashicons-media-document"></span>';
					_e( 'Add a Template', 'chefsections' );

					if( $this->templates->count() > 1 )
						echo '<small><span class="dashicons dashicons-arrow-down"></span></small>';
				
				echo '</div>';

			}
		}

		/**
		 * Creates the container button
		 * 
		 * @return string (html,echoed)
		 */
		public function createContainerButton()
		{
			if( !$this->containers->empty() ){
				echo '<div class="add-section-btn secondary-btn" ';
				echo 'data-post_id="'.$this->postId.'" ';
				echo 'data-action="addSectionContainer" ';

				if( $this->containers->count() > 1 ){
					echo 'data-type="search" ';
					echo 'data-content="SectionContainers" ';
					echo 'data-label="'.__( 'Please select your container', 'chefsections' ).'" ';

				}else{
					$container = array_keys( $this->containers->all() );
					echo 'data-container_slug="'.$container[0].'" ';
				
				}
				echo '>';

					echo '<span class="dashicons dashicons-feedback"></span>';
					_e( 'Add a Container', 'chefsections' );

					if( $this->containers->count() > 1 )
						echo '<small><span class="dashicons dashicons-arrow-down"></span></small>';

				echo '</div>';
			}
		}




		/**
		 * Create the Container Json
		 * 
		 * @return string (html, echoed )
		 */
		public function containerJson()
		{
			$collection = $this->containers->all();
			$containers = [];


			foreach( $collection as $slug => $item ){
				$containers[] = [
					'id' => $slug,
					'name' => $item['label']
				];
			}

			return json_encode( $containers );

		}


		/**
		 * Get's the template JSON
		 * 
		 * @return string (json)
		 */
		public function templateJson(){
			$collection = array_values( $this->templates->all() );
			$templates = [];

			foreach( $collection as $item ){
				$templates[] = [
					'id' => $item['ID'],
					'name' => $item['post_title'],
					'type' => 'reference'
				];
			}

			return json_encode( $templates ); 
		}


	}