<?php
	
	namespace ChefSections\Generators;

	use Cuisine\Utilities\Fluent;
	use ChefSections\Wrappers\Generators;

	class SectionGenerator extends BaseGenerator{


		/**
		 * Default post type to generate
		 * 
		 * @var string
		 */
		protected $defaultPostType = 'section-template';


		/**
		 * Build this actual section
		 * 
		 * @return array
		 */
		public function build()
		{
			$data = parent::build();
			update_post_meta( $this->postId, 'sections', $data );

			//build columns:
			if( !is_null( $this->get( 'columns' ) ) && !empty( $this->get( 'columns' ) ) ){

				//set the columns up for this particular section, 
				//and build 'em:
				foreach( $this->columns() as $column ){
					$column->build();
				}
			}

			return $this;
		}


		/**
		 * Build this object into a section (and it's corresponding post)
		 * 
		 * @return void
		 */
		public function generate( $meta = array() )
		{
			//build and add the postId if not set:
			$attributes = parent::generate();
			$meta[ $this->id ] = $this->sanitizeAttributes( $attributes, $meta );
			
			return $meta;
		}


		/****************************************************/
		/**            Handle columns:                      */
		/****************************************************/


		/**
		 * Add a column
		 * 
		 * @param  String $type  
		 * @param  Array $attributes
		 * 
		 * @return ColumnGenerator
		 */
		public function column( $type, $attributes = array() )
		{
			//$column = new ColumnGenerator( array_merge( compact( 'type' ), $parameters ) );
			$column = new ColumnGenerator( array_merge( compact( 'type' ), $attributes ) );
			$count = 1;
			if( isset( $this->attributes['columns'] ) )
				$count = count( $this->attributes['columns'] ) + 1;
			
			$this->attributes['columns'][ $count ] = $column;
			return $column;
				
		}

		/**
		 * Set the columns variable
		 * 
		 * @return ChefSections\Generators\SectionGenerator
		 */
		public function columns( $parameters = array() )
		{
			if( !is_array( $parameters ) )
				$parameters = [ $parameters ];

			if( empty( $parameters ) )
				$parameters = $this->columns;

			$i = 1;
			$columns = [];
			foreach( $parameters as $column ){

				$columns[ $i ] = $column->id( $i )
										 ->position( $i )
										 ->postId( $this->postId )
										 ->sectionId( $this->id );

				$i++;
			}

			$this->attributes[ 'columns' ] = $columns;
			$this->columns = $columns;


        	return $columns;
		}


		/**
		 * Sanitize the attributes of this section
		 * 
		 * @param  Array $attributes
		 * 
		 * @return Array
		 */
		public function sanitizeAttributes( $attributes, $meta )
		{
			//create the ID
			if( is_null( $this->id ) ){
				$id = ( count( $meta ) + 1 );
				$this->id( $id );
				$attributes['id'] = $id;
				$attributes['position'] = $id;
			}

			//set container ID
			if( !is_null( $this->get( 'containerId' ) ) ){
				$attributes['container_id'] = $attributes['containerId'];
				unset( $attributes['containerId'] );
			}


			//check columns:
			if( !is_null( $this->columns ) && !empty( $this->columns ) ){
				$attributes['columns'] = [];

				$i = 1;
				foreach( $this->columns as $column ){
					$attributes[ 'columns' ][ $i ] = $column->get( 'type', 'content' );
					$i++;
				}
			}

			//reset post ID:
			$attributes['post_id'] = $this->postId;
			unset( $attributes['postId'] );


			return $attributes;
		}


		/**
		 * Get default attributes for this class 
		 * 
		 * @return array
		 */
		public function getDefaultAttributes()
		{
			return [
				'title' => [ 'text' => $this->get( 'name' ), 'type' => 'h2' ],
				'view' => 'fullwidth',
				'name' => sanitize_title( $this->get( 'name' ) ),
				'classes' => [],
				'hide_container' => 'false',
				'type' => 'section',
				'columns' => [ 'content' ] 
			];
		}

	}