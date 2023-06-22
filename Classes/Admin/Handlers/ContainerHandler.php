<?php

	namespace ChefSections\Admin\Handlers;

	use ChefSections\Collections\SectionCollection;
	use ChefSections\Collections\ContainerCollection;
	use ChefSections\Helpers\Section as SectionHelper;
	use ChefSections\Admin\Ui\Sections\ContainerSectionUi;

	class ContainerHandler extends BaseHandler{

		/**
		 * Collection of all registered containers
		 * 
		 * @var ChefSections\Collections\ContainerCollection;
		 */
		protected $containers;

		/**
		 * Set the collection for this manager
		 *
		 * @return void
		 */
		public function setCollection()
		{
			$this->collection = new SectionCollection( $this->postId );
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

			$slug = ( isset( $_POST['container_slug'] ) ? $_POST['container_slug'] : null );

			//check for the slug:
			if( is_null( $slug ) ){
				echo 'Error: no Container slug';
				die();
			}

			//up the highest ID:
			$this->collection->setHighestId( 1 );

			$specifics = [
				'id'				=> $this->collection->getHighestId(),
				'position'			=> ( count( $this->collection->all() ) + 1 ),
				'post_id'			=> $this->postId,
				'container_id'		=> null,
				'slug' 				=> $slug
			];

			//set the arguments:
			$args = SectionHelper::defaultContainerArgs() + $specifics;
			$args['columns'] = [];

			//save this section:
			$_sections = $this->collection->toArray()->all();
			$_sections[ $args['id'] ] = $args;
			update_post_meta( $this->postId, 'sections', $_sections );

			//return a new Container Section UI:
			$container = SectionHelper::getClass( $args );
            if( !is_null( $container ) ){
                $html = ( new ContainerSectionUi( $container ) )->get();
                $this->response( $html );
            }
		}


	}
