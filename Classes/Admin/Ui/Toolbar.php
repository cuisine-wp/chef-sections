<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Admin\Managers\ContainerManager;
	use ChefSections\Admin\Managers\TemplateManager;

	class Toolbar{


		/**
		 * Build the toolbar
		 * 
		 * @return string (html, echoed)
		 */
		public static function build()
		{

			$postId = Session::postId();
			$args = array( 'multiple' => true, 'dropdown' => true );
			//$templates = ReferenceBuilder::getTemplates( $args );

			echo '<div class="section-wrapper dotted-bg" id="section-builder-ui">';

				echo '<div class="add-section-btn" data-action="createSection" data-post_id="'.$postId.'">';
					_e( 'Add Section', 'chefsections' );
				echo '</div>';

				echo '<em>'.__( 'Or', 'chefsections' ).'</em>';

				//( new ContainerManager() )->buildDropdown();

				echo '<em>'.__( 'Or', 'chefsections' ).'</em>';

				( new TemplateManager() )->buildDropdown();


				echo '<span class="update-btn-wrapper">';
					
					echo '<span class="update-btn" id="updatePost">';
						_e( 'Update' );
					echo '</span>';

				echo '</span>';

				//loader
				echo '<span class="spinner"></span>';


			echo '</div>';
		}

	}