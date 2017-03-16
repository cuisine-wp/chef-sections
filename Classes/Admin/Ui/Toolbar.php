<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Admin\Managers\SectionManager;
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

			echo '<div class="section-wrapper dotted-bg" id="section-builder-ui">';

				( new SectionManager() )->buildButton();

				//container dropdown:
				( new ContainerManager() )->buildDropdown();

				//template dropdown:
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