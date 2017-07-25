<?php

	namespace ChefSections\Generators;

	use Cuisine\Utilities\Fluent;
	use ChefSections\Contracts\Generator as GeneratorContract;

	class PageGenerator extends BaseGenerator implements GeneratorContract{

		/**
		 * All generated pages in an Array
		 * 
		 * @var Array
		 */
		protected $sections;

		/**
		 * All generated columns in an array
		 * 
		 * @var Array
		 */
		protected $columns;

		/**
		 * Containers
		 * 
		 * @var Array
		 */
		protected $containers;


		/*****************************************************/
		/**      Setting sections, columns and containers    */
		/*****************************************************/

		/**
		 * Add a section
		 *
		 * @param String $name
		 * @param Array $attributes
		 * 
		 * @return SectionGenerator
		 */
		public function section( $name, $attributes = array() )
		{
			$id = ( count( $this->sections ) + 1 );
			$props = [
				'id' => $id,
				'name' => $name,
				'position' => $id,
			];

			$section = new SectionGenerator( array_merge( $props, $attributes ) );
			$this->sections[] = $section;
			return $section;
		}

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
			$this->columns[] = $column;
			return $column;
				
		}


		/**
		 * Add a container
		 * 
		 * @param  String $name       
		 * @param  Array  $attributes 
		 * 
		 * @return SectionContainerGenerator             
		 */
		public function sectionContainer( $name, $attributes = array() )
		{
			//$container = new ContainerGenerator( array_merge( compact( 'name' ), $parameters ) );
			
			$id = ( count( $this->sections ) + 1 );
			$props = [
				'id' => $id,
				'name' => $name,
				'position' => $id,
			];

			$container = new ContainerGenerator( array_merge( $props, $attributes ) );
			$this->sections[] = $container;
			$this->containers[] = $container;
			return $container;	
		}


		/*****************************************************/
		/**      Setting sections, columns and containers    */
		/*****************************************************/

		/**
		 * Generating
		 * 
		 * @return void
		 */
		public function build()
		{
			//build and add the postId:
			$sectionMeta = [];
			$attributes = $this->generate();

			if( !empty( $attributes['containers' ] ) ){
				foreach( $attributes['containers'] as $container ){
					//set post ID, then build:
					$sectionMeta = $container->postId( $this->postId )->generate( $sectionMeta );
				}
			}


			$keys = array_keys( $sectionMeta );
			
			if( !empty( $attributes['sections'] ) ){
				foreach( $attributes['sections'] as $section ){

					//add section meta:
					if( !in_array( $section->get('id'), $keys ) )
						$sectionMeta = $section->postId( $this->postId )->generate( $sectionMeta );


					//check for columns
					if( !is_null( $section->get( 'columns' ) ) && !empty( $section->get( 'columns' ) ) ){

						//set the columns up for this particular section, 
						//and build 'em:
						foreach( $section->columns() as $column ){
							$column->build();
						}
					}
				}
			}

			update_post_meta( $this->postId, 'sections', $sectionMeta );

			if( !is_null( $this->get( 'applyTo' ) ) )
				update_post_meta( $this->postId, 'apply_to', $this->get( 'applyTo' ) );


			return $this;
		}

		/**
		 * Returns an array of all default attributes
		 * 
		 * @return array
		 */
		public function getDefaultAttributes()
		{
			return [

				'sections' => $this->sections,
				'columns' => $this->columns,
				'containers' => $this->containers

			];
		}
	}