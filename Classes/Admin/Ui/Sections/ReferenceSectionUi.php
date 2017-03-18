<?php

	namespace ChefSections\Admin\Ui\Sections;

	use Cuisine\Wrappers\Field;
	use ChefSections\Collections\SectionCollection;

	class ReferenceSectionUi extends BaseSectionUi{


		/**
		 * Parent section
		 * 
		 * @var ChefSections\SectionTypes\BaseSection
		 */
		protected $parent;


		/**
		 * Check to see if we're editing the original
		 * 
		 * @var bool
		 */
		protected $edittingOriginal = false;

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
			$this->parent = $section->parent;


			//check if we're editting the original:
			if( get_post_type( $this->section->post_id ) == 'section-template' ){
			
				$this->edittingOriginal = true;
				$this->section->template_id = $this->section->post_id;
			
			}else{

				//reset all section values,
				//just in case
				$this->resetSectionValues();
			}
		}

		/**
		 * Overwrite the Build function for this reference
		 * 
		 * @return String (html, echoed)
		 */
		public function build(){

			$class = 'section-wrapper ui-state-default section-'.$this->section->id;

			if( $this->edittingOriginal === false )
				$class .= ' reference';
				

			echo '<div class="'.esc_attr( $class ).'" ';
				echo 'id="'.esc_attr( $this->section->id ).'" ';
				$this->buildIds();
			echo '>';

				$this->buildControls();

				echo '<div class="section-columns '.esc_attr( $this->section->view ).'">';
								
				foreach( $this->section->columns as $column ){
	
					//build column with reference-mode on:
					echo $column->build( ( $this->edittingOriginal ? false : true ) );
	
				}


				if( $this->edittingOriginal === false ){

					echo '<p class="template-txt">';
						printf( __( 'This is the template "%s." When editting this template, it get\'s changed on every page.', 'chefsections' ), get_the_title( $this->section->template_id ) );
					echo '</p>';


					echo '<a href="'.esc_url( admin_url( 'post.php?post='.$this->section->template_id.'&action=edit' ) ).'" class="button button-primary">';

						_e( 'Edit this template', 'chefsections' );

					echo '</a>';

				}


				echo '<div class="clearfix"></div>';
				echo '</div>';

				$this->bottomControls();
				$this->buildSettingsPanels();
				$this->buildHiddenFields();
				$this->buildTemplateFields();
				

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

					$buttons = apply_filters( 'chef_sections_panel_buttons', array() );

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

				//sorting pin:
				echo '<span class="dashicons dashicons-sort pin"></span>';


			echo '</div>';
		
			echo '<div class="clearfix"></div>';
		}

		/**
		 * Add the template fields to this reference UI
		 * 
		 * @return string (html,echoed)
		 */
		public function buildTemplateFields()
		{
			$prefix = 'section['.$this->section->id.']';
			Field::hidden(
				$prefix.'[template_id]',
				array(
					'defaultValue' => $this->section->template_id,
				)
			)->render();
		}

		/**
		 * Reset all other section-values that are important
		 * 
		 * @return void
		 */
		public function resetSectionValues(){
			
			if( !is_null( $this->parent ) ){
				$this->section->title = $this->parent->title;
				$this->section->view = $this->parent->view;
				$this->section->name = $this->parent->getName( $this->parent->properties );
			}
		}

	}