<?php

	namespace ChefSections\Admin\Handlers;

	use ChefSections\SectionTypes\Reference;
	use ChefSections\Collections\SectionCollection;
	use ChefSections\Collections\ReferenceCollection;
	use ChefSections\Helpers\Section as SectionHelper;
	use ChefSections\Admin\Ui\Sections\ReferenceSectionUi;

	class TemplateHandler extends SectionHandler{


		/**
		 * Set the collection for this manager
		 *
		 * @return void
		 */
		public function setCollection()
		{
			$this->collection = new ReferenceCollection();
		}



		/**
		 * Add a reference section
		 *
		 * @return string (html of new section)
		 */
		public function addReference( $templateId = null )
		{

			$templateId = $this->getTemplateId( $templateId );

			if( is_null( $templateId ) )
				return false;

			//up the highest ID
			$pageSections = new SectionCollection( $this->postId );
			$pageSections->setHighestId( 1 );

			//find the parent template:
			$referenceSections = new SectionCollection( $templateId );
			$parent = $referenceSections->toArray()->first();
			
			//set the section specifics:
			$specifics = array(
				'id'			=> $pageSections->getHighestId(),
				'position'		=> ( count( $pageSections->all() ) + 1 ),
				'post_id'		=> $this->postId,
				'container_id'		=> ( isset( $_POST['container_id'] ) ? $_POST['container_id'] : null )
			);

			//get default args
			$args = SectionHelper::defaultArgs() + $specifics;

			//refill the arguments with the parent data:
			$args['title'] = $parent['title'];
			$args['view'] = $parent['view'];
			$args['hide_title'] = $parent['hide_title'];
			$args['hide_container'] = $parent['hide_container'];
			$args['template_id'] = $templateId;
			$args['type'] = 'reference';
			$args['columns'] = $parent['columns'];
	
			//save this section:
			$_sections = $pageSections->toArray()->all();
			$_sections[ $args['id'] ] = $args;
			update_post_meta( $this->postId, 'sections', $_sections );

			//create the new Reference object, and build it
			$this->sectionResponse( $args );
		}

		

		/**
		 * Returns the provided template ID
		 * 
		 * @param  int $templateId (optional)
		 * 
		 * @return int
		 */
		public function getTemplateId( $templateId = null )
		{
			//check for a template-id via POST
			if( is_null( $templateId ) && isset( $_POST['template_id'] ) )
				$templateId = $_POST['template_id'];

			//no template id? return null
			return $templateId;

		}

	}