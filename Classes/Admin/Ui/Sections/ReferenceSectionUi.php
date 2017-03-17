<?php

	namespace ChefSections\Admin\Ui\Sections;

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

				echo '<div class="section-columns '.esc_attr( $this->parent->view ).'">';
	

				foreach( $this->parent->columns as $column ){
	
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
				

			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
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