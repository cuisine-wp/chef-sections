<?php

	namespace ChefSections\Admin\Ui\Sections;

	use Cuisine\Wrappers\Field;
	use ChefSections\Admin\Ui\Containers\TabbedUi;
	use ChefSections\Admin\Ui\Containers\GroupedUi;
	use ChefSections\Helpers\SectionUi as SectionUiHelper;

	class ContainerSectionUi extends BaseSectionUi{

		/**
		 * The container object
		 * 
		 * @var mixed
		 */
		protected $container;

		/**
		 * Constructor
		 * 
		 * @param ChefSections\SectionTypes\BaseSection $section
		 *
		 * @return void
		 */
		public function __construct( $section )
		{
			$this->section = $section;
			$this->container = $section;
		}

		/**
		 * Build the UI for this container
		 * 
		 * @return String (html, echoed)
		 */
		public function build(){

			$class = 'section-wrapper ui-state-default section-container section-'.$this->container->id;

			echo '<div class="'.esc_attr( $class ).'" ';
				echo 'id="'.esc_attr( $this->container->id ).'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="'.$this->getClass().'">';
					
					$this->buildMainUi();

					echo '<div class="clearfix"></div>';
				echo '</div>';

				$this->bottomControls();
				$this->buildSettingsPanels();
				$this->buildHiddenFields();
				
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}

		/**
		 * Fetched the right UI class for building the main UI
		 * 
		 * @return string ( html, echoed )
		 */
		public function buildMainUi()
		{
			switch( $this->container->view ){

				case 'tabbed':

					return ( new TabbedUi( $this->container ) )->build();
					break;

				default:

					return ( new GroupedUi( $this->container ) )->build();
					break;

			}
		}

		/**
		 * Returns the class for this container
		 * 
		 * @return string
		 */
		public function getClass()
		{
			$class = 'section-columns container-sections ';
			$class .= $this->container->view.'-sections ';
			$class .= $this->container->slug.'-container';
			return $class;
		}


		/**
		 * Build the top of this Section
		 * 
		 * 
		 * @return String ( html, echoed )
		 */
		public function buildControls(){

			echo '<div class="section-controls">';

				//first the title:
				$title = [ 'text' => '', 'type' => 'h2' ];
				if( !$this->section->hide_title )
					$title = $this->section->getProperty( 'title' );

				Field::title(
					'section['.$this->section->id.'][title]',
					__( 'Titel', 'chefsections' ),
					array(
						'placeholder'	=> __( 'Section title', 'chefsections' ),
						'label'			=> false,
						'defaultValue'	=> $title,
						'fieldName'		=> 'section['.$this->section->id.'][title]'
					)
				)->render();


				//add the top buttons for panels:
				echo '<div class="buttons-wrapper">';

					$buttons = SectionUiHelper::getPanelButtons( $this->section );

					foreach( $buttons as $button ){

						echo '<span class="button section-'.esc_attr( $button['name'] ).'-btn with-tooltip" data-id="'.esc_attr( $button['name'] ).'">';
							echo '<span class="dashicons '.esc_attr( $button['icon'] ).'"></span>';
							echo '<span class="tooltip">'.esc_attr( $button['label'] ).'</span>';
						echo '</span>';

					}

				echo '</div>';
				

				Field::hidden(
					'section['.$this->section->id.'][view]',
					array(
						'defaultValue' => $this->section->view
					)
				)->render();

				Field::hidden(
					'section['.$this->section->id.'][slug]',
					array(
						'defaultValue' => $this->section->slug
					)
				)->render();

				//sorting pin:
				echo '<span class="dashicons dashicons-sort pin"></span>';


			echo '</div>';
		
			echo '<div class="clearfix"></div>';
		}


		/**
		 * Create the controls on the bottom
		 * 
		 * @return string (html, echoed)
		 */
		public function bottomControls(){

			echo '<div class="section-footer container-footer">';
				echo '<p class="delete-section">';
					echo '<span class="dashicons dashicons-trash"></span>';
				echo __( 'Delete', 'chefsections' ).'</p>';

				do_action( 'chef_sections_bottom_controls' );

				$this->buildTemplateSnitch();
				$this->buildCodeSnitch();

			echo '</div>';

		}
		
	}