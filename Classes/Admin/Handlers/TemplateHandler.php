<?php

	namespace ChefSections\Admin\Handlers;

	use ChefSections\SectionTypes\Reference;
	use ChefSections\Collections\SectionCollection;
	use ChefSections\Collections\ReferenceCollection;
	use ChefSections\Helpers\Section as SectionHelper;

	class TemplateHandler extends BaseHandler{


		/**
		 * Set the collection for this manager
		 *
		 * @return void
		 */
		public function setCollection()
		{
			$this->collection = new ReferenceCollection();
		}

		/**************************************************/
		/**                   UI                          */
		/**************************************************/

		/**
		 * Build the dropdown to select a reference
		 * 
		 * @return string (html, echoed)
		 */
		public function buildDropdown()
		{
			echo '<div class="section-dropdown-wrapper template-dropdown">';
				echo '<button class="primary btn template-dropdown">';
					_e( 'Select a template', 'chefsections' );
				echo '</button>';

				echo '<div class="dropdown-inner">';

					foreach( $this->collection->toArray()->all() as $item ){

						echo '<div class="add-section-btn" ';
						echo 'data-action="addSectionTemplate" ';
						echo 'data-post_id="'.$this->postId.'" ';
						echo 'data-template_id="'.$item['ID'].'">';
							echo $item['post_title'];
						echo '</div>';
					}


				echo '</div>';

			echo '</div>';
		}



		/**************************************************/
		/**           Handle adding a template            */
		/**************************************************/

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
				'id'				=> $pageSections->getHighestId(),
				'position'			=> ( count( $pageSections->all() ) + 1 ),
				'post_id'			=> $this->postId,
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
			$section = new Reference( $args );
			return $section->build();
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