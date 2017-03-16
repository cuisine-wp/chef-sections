<?php

	namespace ChefSections\Admin\Managers;

	use ChefSections\Collections\ContainerCollection;

	class ContainerManager extends BaseManager{


		/**
		 * Set the collection for this manager
		 *
		 * @return void
		 */
		public function setCollection()
		{
			$this->collection = new ContainerCollection();
		}


		/**************************************************/
		/**                   UI                          */
		/**************************************************/

		/**
		 * Build the dropdown to select a container
		 * 
		 * @return string (html, echoed)
		 */
		public function buildDropdown()
		{

			if( !$this->collection->empty() ){
				
				echo '<div class="section-dropdown-wrapper container-dropdown">';
					echo '<button class="primary btn container-dropdown">';
						_e( 'Select a container', 'chefsections' );
					echo '</button>';

					echo '<div class="dropdown-inner">';
						
						foreach( $this->collection->all() as $slug => $container ){

							echo '<div class="add-section-btn section-cont" ';
							echo 'data-action="addSectionContainer" ';
							echo 'data-post_id="'.$this->postId.'" ';
							echo 'data-container_type="'.$slug.'">';
								echo $container['label'];
							echo '</div>';
						}

					echo '</div>';

				echo '</div>';

			}
		}



		/**************************************************/
		/**           Handle adding a container           */
		/**************************************************/

		/**
		 * Add a container
		 *
		 * @return string (html of new section)
		 */
		public function addContainer(){

			dd( $_POST );
			$slug = ( isset( $_POST['container_type'] ) ? $_POST['container_type'] : null );

			if( is_null( $slug ) )
				return 'Error: no Container slug';


			$container = $this->collection->get( $slug );

			dd( $this->collection->all() );

		}


	}