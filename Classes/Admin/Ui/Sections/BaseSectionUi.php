<?php

	namespace ChefSections\Admin\Ui\Sections;

	use Cuisine\Wrappers\Field;
	use ChefSections\Wrappers\Template;
	use ChefSections\Admin\Ui\Containers\TabbedUi;
	use ChefSections\Helpers\Section as SectionHelper;
	use ChefSections\Helpers\SectionUi as SectionUiHelper;

	class BaseSectionUi{

		/**
		 * Section object
		 * 
		 * @var ChefSections\SectionTypes\BaseSection;
		 */
		protected $section;


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
		}

		/*=============================================================*/
		/**             Building UI                                    */
		/*=============================================================*/

		/**
		 * Returns the output of the build function as a string
		 * 
		 * @return string
		 */
		public function get()
		{
			ob_start();

				$this->build();

			return ob_get_clean();
		}


		/**
		 * Build the UI for this section
		 * 
		 * @return String (html, echoed)
		 */
		public function build(){

			$class = 'section-wrapper ui-state-default section-'.$this->section->id;

			echo '<div class="'.esc_attr( $class ).'" ';
				echo 'id="'.esc_attr( $this->section->id ).'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="section-columns '.esc_attr( $this->section->view ).'">';
		

				foreach( $this->section->columns as $column ){
						
					if( $column )
						echo $column->build();
		
				}


					echo '<div class="clearfix"></div>';
				echo '</div>';

				$this->bottomControls();
				$this->buildSettingsPanels();
				$this->buildHiddenFields();
				
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
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
				

				//view-switcher:
				$types = SectionHelper::viewTypes();

				Field::radio(
					'section['.$this->section->id.'][view]',
					'Weergave',
					$types,
					array(
						'defaultValue' => $this->section->view
					)
				)->render();

				//sorting pin:
				if( !SectionUiHelper::needsTab( $this->section ) )
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

			echo '<div class="section-footer">';
				echo '<p class="delete-section">';
					echo '<span class="dashicons dashicons-trash"></span>';
				echo __( 'Delete', 'chefsections' ).'</p>';

				do_action( 'chef_sections_bottom_controls' );

				$this->buildTemplateSnitch();
				$this->buildCodeSnitch();

			echo '</div>';

		}


		/**
		 * Generate the data-tags with id's
		 * 
		 * @return string ( html, echoed )
		 */
		public function buildIds(){

			echo 'data-section_id="'.esc_attr( $this->section->id ).'" ';
			echo 'data-post_id="'.esc_attr( $this->section->post_id ).'"';

			if( SectionUiHelper::needsTab( $this->section ) )
				echo ' data-tab="tab_'.$this->section->id.'"';

		}

		/**
		 * Generate the code needed to fetch this section
		 * 
		 * @return string ( html, echoed )
		 */
		public function buildCodeSnitch(){

			echo '<span class="template-snitch code-snitch">';
				echo '<span class="dashicons dashicons-editor-code"></span>';
				echo '<span class="tooltip">';

					echo '<strong>Code:</strong><br/>';
					echo '<span class="copy">echo Loop::section( '.esc_attr( $this->section->post_id ).', '.esc_attr( $this->section->id ).' );</span>';

				echo '</span>';
			echo '</span>';
		}


		/**
		 * Generate the templates for this section
		 * 
		 * @return string ( html, echoed )
		 */
		public function buildTemplateSnitch(){

			$templates = Template::section( $this->section )->getHierarchy();
			echo '<span class="template-snitch">';
				echo '<span class="dashicons dashicons-media-text"></span>';
				echo '<span class="tooltip">';

					echo '<strong>Templates:</strong>';
					foreach( $templates as $template ){

						echo '<p>'.esc_html( $template ).'</p>';

					}

				echo '</span>';
			echo '</span>';
		}


		/**
		 * Create the settings panel with it's fields
		 * 
		 * @return string (html, echoed)
		 */
		public function buildSettingsPanels(){

			echo '<div class="section-setting-panels">';

				do_action( 'chef_section_setting_panels', $this->section );

			echo '</div>';
		}

		/**
		 * Render all hidden fields for this section
		 * 
		 * @return void
		 */
		public function buildHiddenFields(){

			$prefix = 'section['.$this->section->id.']';
			Field::hidden(
				$prefix.'[position]',
				array(
					'defaultValue' => $this->section->position,
					'class' => array( 'field', 'input-field', 'section-position' )
				)
			)->render();

			Field::hidden(
				$prefix.'[post_id]',
				array(
					'defaultValue' => $this->section->post_id
				)
			)->render();

			Field::hidden(
				$prefix.'[id]',
				array(
					'defaultValue' => $this->section->id
				)
			)->render();

			Field::hidden(
				$prefix.'[container_id]',
				array(
					'defaultValue' => $this->section->container_id
				)
			)->render();

			Field::hidden(
			
				$prefix.'[type]',
				array(
					'defaultValue' => $this->section->type
				)
			
			)->render();
		}

	
		/*=============================================================*/
		/**             Support for tabs                               */
		/*=============================================================*/


		/**
		 * Returns the tab response for this section ( if needed )
		 * 
		 * @return string
		 */
		public function getTab()
		{
			$tab = null;

			//add support for tabbed containers:
			if( SectionUiHelper::needsTab( $this->section ) )
				$tab = TabbedUi::getTab( $this->section, true );


			return $tab;
		}


			


	}