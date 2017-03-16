<?php

	namespace ChefSections\Sections;


	use Cuisine\Utilities\Session;
	use ChefSections\Helpers\PostType;
	use ChefSections\Helpers\Section as SectionHelper;
	use ChefSections\Collections\SectionCollection;

	class Manager{


		/**
		 * Post Id
		 * 
		 * @var integer
		 */
		protected $postId;

		/**
		 * Sections Collection
		 * 
		 * @var ChefSections\Walkers\SectionCollection;
		 */
		protected $sections;


		/**
		 * Constructor
		 */
		public function __construct( $post_id )
		{
			$this->postId = $post_id;
			$this->sections = new SectionCollection( $post_id );
		}


		/*=============================================================*/
		/**             Saving                                         */
		/*=============================================================*/


		/**
		 * Loop through each section and save 'em
		 * 
		 * @return bool
		 */
		public function saveSections(){


			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		    $nonceName = ( isset( $_POST[ Session::nonceName ] ) ) ? $_POST[ Session::nonceName ] : Session::nonceName;
		    if( !wp_verify_nonce( $nonceName, Session::nonceAction ) ) return;


			if( !PostType::isValid( $this->postId ) )
				return false;


			if( isset( $_POST['section'] ) ){

				$sections = $_POST['section'];

				//save columns and types
				foreach( $sections as $section ){

					$columns = array();
					$types = SectionHelper::viewTypes();
					$count = $types[ $section['view'] ];

					for( $i = 1; $i <= $count; $i++ ){

						$string = '_column_type_'.$section['id'].'_'.$i;

						if( isset( $_POST[$string] ) ){
							$columns[ $i ] = $_POST[$string];
						}else{
							$columns[ $i ] = 'content';
						}
					}

					$sections[ $section['id'] ]['post_id'] = $this->postId;
					$sections[ $section['id'] ]['columns'] = $columns;

				}

				//save the main section meta:
				update_post_meta( $this->postId, 'sections', $sections );	
			}
				
			return true;
		}


		/*=============================================================*/
		/**    Section create, delete & view-change                    */
		/*=============================================================*/


		/**
		 * Add a section to this post + the builder
		 * 
		 * @param array $datas
		 * @return String (html of the new section)
		 */
		public function addSection( $datas = array() ){

			//up the highest ID
			$this->sections->setHighestId( 1 );


			//get the defaults:
			$args = $this->getDefaultSectionArgs();
			$args = wp_parse_args( $datas, $args );

			$columns = $this->getDefaultColumns( $args['view'] );
			if( isset( $datas['columns'] ) ){
				$args['columns'] = wp_parse_args( $datas['columns'], $columns );
			}else{
				$args['columns'] = $columns;
			}

			//save this section:
			$_sections = $this->sections->toArray()->all();

			$_sections[ $args['id'] ] = $args;
			update_post_meta( $this->postId, 'sections', $_sections );

			//if( $_POST['type'] == 'section' ){
				$section = new Section( $args );
			//}else{
			//	$section = new Container( $args );
			//}

			return $section->build();
		}



		/**
		 * Delete section
		 * 
		 * @return void
		 */
		public function deleteSection(){

			$section_id = $_POST['section_id'];
			$_sections = $this->sections->toArray()->all();

			unset( $_sections[ $section_id ] );
			update_post_meta( $this->postId, 'sections', $_sections );
			echo 'true';
		}


		/**
		 * Get the new section view 
		 * 
		 * @return string
		 */
		public function changeView(){

			$section_id = $_POST['section_id'];
			$view = $_POST['view'];

			$_sections = $this->sections->toArray()->all();
			$_sections[ $section_id ]['view'] = $view;

			//add columns if needed:
			$default = $this->getDefaultColumns( $view );
			$existing = $_sections[ $section_id ]['columns'];
			$new = array();


			foreach( $default as $key => $col ){

				if( !isset( $existing[ $key ] ) ){
					$new[ $key ] = $default[ $key ];
				}else{
					$new[ $key ] = $existing[ $key ];
				}
			}
			
			$_sections[ $section_id ]['columns'] = $new;
			
			update_post_meta( $this->postId, 'sections', $_sections );

			$section = new Section( $_sections[ $section_id ] );
			return $section->build();
		
		}


		/**
		 * Save the order of metaboxes
		 * 
		 * @return bool (success / no success)
		 */
		public function sortSections(){

			$ids = $_POST['section_ids'];

			//save this section:
			$_sections = $this->sections->toArray()->all();
			
			$i = 1;
			foreach( $ids as $section_id ){
				$_sections[ $section_id ]['position'] = $i;
				$i++;
			}

			update_post_meta( $this->postId, 'sections', $_sections );
		}

		/**
		 * Sort columns
		 * 
		 * @return bool (success / no success)
		 */
		public function sortColumns(){

			$id = $_POST['section_id'];
			$ids = $_POST['column_ids'];
			$i = 1;
			$columns = array();

			foreach( $ids as $col ){

				$key = '_column_props_'.$id.'_'.$col;

				$column = get_post_meta( $this->postId, $key, true );
				$column['position'] = $i;
				$columns[] = $column;

				//update the position:
				update_post_meta( $this->postId, $key, $column );

				$i++;
			}

			return true;
		}


		/**
		 * Returns a filterable array of default settings
		 *
	     * @filter 'chef_sections_default_section_args'
		 * @return array
		 */
		public function getDefaultSectionArgs(){

			$default = SectionHelper::defaultArgs();
			$specifics = array(
				'id'				=> $this->sections->getHighestId(),
				'position'			=> ( count( $this->sections->get() ) + 1 ),
				'post_id'			=> $this->postId,
			);

			//return the args
			return $specifics + $default;
		}


		/**
		 * Get the default columns, based on the view
		 * 
		 * @param  string $view
		 * @return array
		 */
		protected function getDefaultColumns( $view ){

			$viewTypes = SectionHelper::viewTypes();
			$colCount = $viewTypes[ $view ];

			$arr = array();

			for( $i = 0; $i < $colCount; $i++ ){

				$key = $i + 1;
				$arr[ $key ] = 'content';

			}

			return $arr;
		}


	}