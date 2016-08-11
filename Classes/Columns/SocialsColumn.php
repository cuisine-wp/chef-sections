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
		/**             Frontend                                        */
		/*=============================================================*/
	

		/**
		 * Custom getField method, to make this backwards compatible
		 * 
		 * @param  string $name
		 * @param  string $default (optional)
		 * @return string / bool (returns false if this content does not exist )
		 */
		public function getField( $name, $default = null ){

			//check if we're referencing an old key:
			if( in_array( $name, array_keys( $this->getOldFields() ) ) )
				return $this->getLink( $name );
		

			if( !isset( $this->properties[ $name ] ) ){
				
				if( $default !== null )
					return $default;


				return false;

			}

			return $this->properties[$name];

		}

		/**
		 * Returns a social link, by icon
		 * 
		 * @param  string $name
		 * @return string
		 */
		public function getLink( $name ){

			$old = $this->getOldFields();
			$icon = $old[ $name ];

			$socials = $this->properties[ 'socials' ];
			if( $socials ){

				foreach( $socials as $social ){

					if( $social['icon'] == $icon )
						return $social['link'];

				}

			}

			return false;

		}

		public function getOldFields(){

			$oldFields = array( 
				'fb' => 'facebook',
				'tw' => 'twitter',
				'in' => 'linkedin',
				'gp' => 'google-plus',
				'pin' => 'pinterest',
				'ins' => 'instagram',
			);

			return $oldFields;

		}


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
						if( 
							substr( $link, 0, 4 ) !== 'http' && 
							substr( $link, 0, 2 ) !== '//' &&
							strlen( $link ) > 4 
						)
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

			$icons = $this->getIcons();

			$repeaters = array(
				Field::text( 'link', __( 'Link', 'chefsections' ) ),
				Field::select( 'icon', __( 'Icon', 'chefsections' ), $icons )
			);
	
			$fields = array(
	
				Field::text( 
					'title', 
					__('Title', 'chefsections'),
					array(
						'defaultValue'	=> $this->getField( 'title' )
					)
				),
				Field::repeater(
					'socials',
					'Socials',
					$repeaters,
					array(
						'defaultValue' => $this->getField( 'socials' )
					)
				)
			);
	
			return $fields;
		}


		/**
		 * Returns an array of icon possibilities
		 * 
		 * @return array
		 */
		public function getIcons(){
	
			$icons = array(

				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'youtube' => 'YouTube',
				'instagram' => 'Instagram',
				'pinterest' => 'Pinterest',
				'google-plus' => 'Google Plus',
				'linkedin' => 'Linkedin',
				'vimeo' => 'Vimeo',
				'github' => 'Github',
				'wordpress' => 'WordPress',
				'skype' => 'Skype',
				'vine' => 'Vine',
				'slack' => 'Slack',
				'foursquare' => 'Foursquare',
				'codepen' => 'Codepen',
				'medium' => 'Medium',
				'soundcloud' => 'Soundcloud',
				'tumblr' => 'Tumblr',
				'producthunt' => 'Producthunt'

			);

			return apply_filters( 'chef_sections_social_icons', $icons );
			
		}
}