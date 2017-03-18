<?php
	
	namespace ChefSections\Admin\Handlers;

	use ChefSections\Wrappers\Column;
	use ChefSections\Collections\SectionCollection;

	class ColumnHandler extends BaseHandler{


		/**
		 * Set a collection for all sections
		 *
		 * @return void
		 */
		public function setCollection()
		{
			$this->collection = new SectionCollection( $this->postId );
		}


		/**
		 * Save column data, for any column
		 * 
		 * @return bool
		 */
		public function saveProperties(){

			$id = $_POST['column_id'];
			$sectionId = $_POST['section_id'];	
			$type = $_POST['type'];

			$section = $this->collection->get( $sectionId );

			$column = Column::$type( $id, $section, array() );
			$column->saveProperties();
			$column->build();
			die();
		}



		/**
		 * Save column type, for any column
		 * 
		 * @return string, echoed
		 */
		public function saveType(){

			global $post;
			$id = $_POST['column_id'];
			$sectionId = $_POST['section_id'];
			$type = $_POST['type'];

			update_post_meta( $this->postId, '_column_type_'.$id, $type );

			$_sections = $this->collection->toArray()->all();
			$_sections[ $sectionId ]['columns'][ $id ] = $type;
			update_post_meta( $this->postId, 'sections', $_sections );

			$this->refreshColumn();

		}

		/**
		 * Refresh a column
		 * 
		 * @return void
		 */
		public function refreshColumn(){

			$id = $_POST['column_id'];
			$sectionId = $_POST['section_id'];
			$type = $_POST['type'];

			$section = $this->collection->get( $sectionId );
			$newColumn = Column::$type( $id, $section, array() );
			$newColumn->build();
		}


	}