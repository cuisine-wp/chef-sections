<?php

	namespace ChefSections\Admin\Managers;

	class ContainerManager{


		/**
		 * Set the collection for this manager
		 *
		 * @return void
		 */
		public function setCollection()
		{
			$this->collection = new ContainerCollection( $this->postId );
		}


	}