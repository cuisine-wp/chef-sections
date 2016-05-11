<?php
namespace ChefSections\Columns;

use Cuisine\Wrappers\Field;

/**
 * Gallery column.
 * @package ChefSections\Columns
 */
class SocialsColumn extends DefaultColumn{

	/**
	 * The type of column
	 * 
	 * @var String
	 */
	public $type = 'socials';



		/*=============================================================*/
		/**             Backend                                        */
		/*=============================================================*/
	
		/**
		 * Save the properties of this column
		 * 
		 * @return bool
		 */
		public function saveProperties(){

			$props = $_POST['properties'];
		
			foreach( $props as $key => $link ){

				if( $key !== 'title' && $key !== 'position' ){

					if( $link != '' ){				
						//filter the link:
						if( substr( $link, 0, 4 ) !== 'http' && substr( $link, 0, 2 ) !== '//' )
							$props[$key] = 'http://'.$link;

					}
				}
			}


			$saved = update_post_meta( 
				$this->post_id, 
				'_column_props_'.$this->fullId, 
				$props
			);

			//set the new properties in this class
			$this->properties = $props;
			return $saved;
		}


		/**
		 * Build the contents of the lightbox for this column
		 * 
		 * @return string ( html, echoed )
		 */
		public function buildLightbox(){
	
			$fields = $this->getFields();
	
			echo '<div class="main-content">';
			
				foreach( $fields as $field ){
	
					$field->render();
	
				}
	
			echo '</div>';
			echo '<div class="side-content">';
	
				$this->saveButton();
	
			echo '</div>';
		}
	
	
		/**
		 * Get the fields for this column
		 * 
		 * @return array
		 */
		private function getFields(){
	
			$fields = array(
	
				Field::text( 
					'title', 
					'Titel',
					array(
						'defaultValue'	=> $this->getField( 'title' )
					)
				),
				Field::text(
					'fb',
					'Facebook link',
					array(
						'defaultValue'	=> $this->getField( 'fb' )
					)
				),
				Field::text(
					'tw',
					'Twitter link',
					array(
						'defaultValue'	=> $this->getField( 'tw' )
					)
				),
				Field::text(
					'in',
					'LinkedIn link',
					array(
						'defaultValue'	=> $this->getField( 'in' )
					)
				),
				Field::text(
					'gp',
					'Google Plus link',
					array(
						'defaultValue'	=> $this->getField( 'gp' )
					)
				),
				Field::text(
					'pin',
					'Pinterest link',
					array(
						'defaultValue'	=> $this->getField( 'pin' )
					)
				),
				Field::text(
					'ins',
					'Instagram link',
					array(
						'defaultValue'	=> $this->getField( 'ins' )
					)
				)
			);
	
			return $fields;
		}
}