<?php

	namespace CuisineSections\Helpers;

	use CuisineSections\Collections\ContainerCollection;
	use CuisineSections\Admin\Ui\Sections\ContentSectionUi;
	use CuisineSections\Admin\Ui\Sections\ContainerSectionUi;
	use CuisineSections\Admin\Ui\Sections\ReferenceSectionUi;
	use CuisineSections\Admin\Ui\Sections\BlueprintSectionUi;

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
		 * @param CuisineSections\SectionTypes\BaseSection $section
		 * 
		 * @return CuisineSections\Admin\Ui\Sections\BaseSectionUI
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
			return apply_filters( 'cuisine_sections_panel_buttons', array(), $section );
		}

	}