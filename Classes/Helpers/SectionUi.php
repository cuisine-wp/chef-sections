<?php

	namespace ChefSections\Helpers;


	use ChefSections\Admin\Ui\Sections\ContentSectionUi;
	use ChefSections\Admin\Ui\Sections\ContainerSectionUi;
	use ChefSections\Admin\Ui\Sections\ReferenceSectionUi;
	use ChefSections\Admin\Ui\Sections\BlueprintSectionUi;

	class SectionUi{

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

	}