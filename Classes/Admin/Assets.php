<?php

	namespace ChefSections\Admin;

	use _WP_Editors;
	use Cuisine\Utilities\Url;
	use Cuisine\Utilities\Session;
	use ChefSections\Wrappers\StaticInstance;

	class Assets extends StaticInstance{

		/**
		 * Init admin events & vars
		 */
		public function __construct(){
			
			$this->enqueues();

		}

		/**
		 * Enqueue scripts & Styles
		 * 
		 * @return void
		 */
		private function enqueues(){

			
			add_action( 'admin_menu', function(){

				$url = Url::plugin( 'chef-sections', true ).'Assets';
				wp_enqueue_script( 
					'sections_section', 
					$url.'/js/Section.js', 
					array( 'backbone', 'media-editor' ),
					false,
				    true
				);

				wp_enqueue_script( 
					'sections_container', 
					$url.'/js/Container.js', 
					array( 'backbone', 'media-editor' ),
					false,
				    true
				);

				wp_enqueue_script( 
					'sections_column', 
					$url.'/js/Column.js', 
					array( 'backbone', 'media-editor', 'chosen' ),
					false,
				    true
				);

				wp_enqueue_script( 
					'sections_builder', 
					$url.'/js/Builder.js', 
					array( 'backbone', 'media-editor', 'jquery-ui-draggable', 'chosen' ),
					false,
				    true
				);

				wp_enqueue_script( 
				    'chosen', 
				    $url.'/js/libs/chosen.min.js', 
				    array( 'jquery' ),
				    false,
				    true
				);

				wp_enqueue_script(
				    'taxonomySelect',
				    $url.'/js/TaxonomySelect.js',
				    array( 'jquery', 'chosen' ),
				    false,
				    true
				);

				wp_localize_script(
					'sections_section',
					'ChefSections',
					array(
						'postId' => Session::postId()
					)
				);

				
				wp_enqueue_style( 'sections', $url.'/css/admin.css' );
				
			});


			add_action( 'admin_footer', function(){
			
				//always load tinymce:
			/*	$js_src = includes_url('js/tinymce/') . 'tinymce.min.js';
				$css_src = includes_url('css/') . 'editor.css';

				// wp_enqueue doesn't seem to work at all
				echo '<script src="' . $js_src . '" type="text/javascript"></script>';

				wp_register_style('tinymce_css', $css_src);
				wp_enqueue_style('tinymce_css');

				_WP_Editors::editor_settings( 'defaultEditor', array( 'quicktags' => false, '_content_editor_dfw' => false ) );*/

				echo '<div style="display:none">';
					wp_editor( '', 'defaultEditor' );
				echo '</div>';

			});
			
		}



	}
	
	if( is_admin() )
		\ChefSections\Admin\Assets::getInstance();

