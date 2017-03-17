<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Collections\ContainerCollection;
	use ChefSections\Collections\ReferenceCollection;


	class Toolbar{


		/**
		 * Build the toolbar
		 * 
		 * @return string (html, echoed)
		 */
		public static function build()
		{

			echo '<div class="section-wrapper dotted-bg" id="section-builder-ui">';

				static::buildButton();

				//container dropdown:
				static::containerDropdown();

				//template dropdown:
				static::templateDropdown();


				echo '<span class="update-btn-wrapper">';
					
					echo '<span class="update-btn" id="updatePost">';
						_e( 'Update' );
					echo '</span>';

				echo '</span>';

				//loader
				echo '<span class="spinner"></span>';


			echo '</div>';
		}


		/**
		 * Build an "Add Section"-button
		 * 
		 * @return string (html, echoed)
		 */
		public static function buildButton()
		{
			$postId = Session::postId();
			echo '<div class="add-section-btn" data-action="createSection" data-post_id="'.$postId.'">';
				_e( 'Add Section', 'chefsections' );
			echo '</div>';
		}


		/**
		 * Add the dropdown for containers
		 * 
		 * @return string (html, echoed )
		 */
		public static function containerDropdown()
		{

			$postId = Session::postId();
			$containers = new ContainerCollection();

			if( !$containers->empty() ){
				
				echo '<div class="section-dropdown-wrapper container-dropdown">';
					echo '<button class="primary btn container-dropdown">';
						_e( 'Select a container', 'chefsections' );
					echo '</button>';

					echo '<div class="dropdown-inner">';
						
						foreach( $containers->all() as $slug => $container ){

							echo '<div class="add-section-btn section-cont" ';
							echo 'data-action="addSectionContainer" ';
							echo 'data-post_id="'.$postId.'" ';
							echo 'data-container_type="'.$slug.'">';
								echo $container['label'];
							echo '</div>';
						}

					echo '</div>';

				echo '</div>';

			}
		}


		/**
		 * Adds the dropdown for templates
		 * 
		 * @return string (html, echoed)
		 */
		public static function templateDropdown()
		{

			$postId = Session::postId();
			$references = new ReferenceCollection();

			echo '<div class="section-dropdown-wrapper template-dropdown">';
				echo '<button class="primary btn template-dropdown">';
					_e( 'Select a template', 'chefsections' );
				echo '</button>';

				echo '<div class="dropdown-inner">';

					foreach( $references->toArray()->all() as $item ){

						echo '<div class="add-section-btn" ';
						echo 'data-action="addSectionTemplate" ';
						echo 'data-post_id="'.$postId.'" ';
						echo 'data-template_id="'.$item['ID'].'">';
							echo $item['post_title'];
						echo '</div>';
					}


				echo '</div>';

			echo '</div>';
		}

	}