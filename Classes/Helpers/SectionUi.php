<?php

	namespace ChefSections\Helpers;

	use ChefSections\Collections\ContainerCollection;
	use ChefSections\Admin\Ui\Sections\ContentSectionUi;
	use ChefSections\Admin\Ui\Sections\ContainerSectionUi;
	use ChefSections\Admin\Ui\Sections\ReferenceSectionUi;
	use ChefSections\Admin\Ui\Sections\BlueprintSectionUi;

	class SectionUi{

		/**
		 * Returns the tab html, if applicable
		 * 
		 * @return mixed
		 */
		public static function needsTab( $section )
		{
			if( 
				isset( $section->container_id ) &&
				!is_null( $section->container_id ) &&
				$section->container_id != '' 
			){

				$container = ( new ContainerCollection() )->getById( $section->container_id, $section->post_id );

				if( $container['view'] == 'tabbed' )
					return true;

			}

			return false;
		}

		/**
		 * Returns an instance of the right UI Class
		 *
		 * @param ChefSections\SectionTypes\BaseSection $section
		 * 
		 * @return ChefSections\Admin\Ui\Sections\BaseSectionUI
		 */
		public static function getClass( $section )
		{

			switch( $section->type ){

				case 'reference':

					return new ReferenceSectionUi( $section );
					break;

				case 'container':

					return new ContainerSectionUi( $section );
					break;

				case 'blueprint':

					return new BlueprintSectionUi( $section );
					break;

				default:

					return new ContentSectionUi( $section );
					break;
			}
		}


		/**
		 * Returns the right panel buttons for sections
		 * 
		 * @return Array
		 */
		public static function getPanelButtons( $section )
		{
			return apply_filters( 'chef_sections_panel_buttons', array(), $section );
		}

	}