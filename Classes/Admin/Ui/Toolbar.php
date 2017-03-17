<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;
	use ChefSections\Admin\Handlers\SectionHandler;
	use ChefSections\Admin\Handlers\ContainerHandler;
	use ChefSections\Admin\Handlers\TemplateHandler;


	class Toolbar{


		/**
		 * Build the toolbar
		 * 
		 * @return string (html, echoed)
		 */
		public static function build()
		{

			echo '<div class="section-wrapper dotted-bg" id="section-builder-ui">';

				( new SectionHandler() )->buildButton();

				//container dropdown:
				( new ContainerHandler() )->buildDropdown();

				//template dropdown:
				( new TemplateHandler() )->buildDropdown();


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