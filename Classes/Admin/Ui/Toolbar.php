<?php

	namespace ChefSections\Admin\Ui;

	use Cuisine\Utilities\Session;

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

				echo '<div id="addSection" class="section-btn" data-post_id="'.$postId.'" data-type="section">';
					_e( 'Add Section', 'chefsections' );
				echo '</div>';

				echo '<div id="addSection" class="section-btn" data-post_id="'.$postId.'" data-type="container">';
					_e( 'Add Container', 'chefsections' );
				echo '</div>';

				echo '<em>'.__( 'Or', 'chefsections' ).'</em>';

				//add templates:
				/*echo '<select id="getTemplate" data-post_id="'.esc_attr( $postId ).'">';

					echo '<option value="none">';
						_e( 'Select section template', 'chefsections' );
					echo '</option>';

					foreach( $templates as $template ){

						if( $template->ID != $this->postId ){
							echo '<option value="'.esc_attr( $template->ID ).'">';
								echo $template->post_title;
							echo '</option>';
						}
					}


				echo '</select>';*/

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