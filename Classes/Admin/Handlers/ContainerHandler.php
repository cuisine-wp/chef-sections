<?php

	namespace ChefSections\Admin\Handlers;

	use ChefSections\Collections\ContainerCollection;

	class ContainerHandler extends BaseHandler{


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