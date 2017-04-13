<?php

	namespace ChefSections\Generators;

	use Closure;

	class GeneratorHandler{


		/**
		 * Generate a section blueprint
		 *
		 * @param String $type
		 * @param Closure $callback
		 * 
		 * @return void
		 */
		public function section( $name, Closure $callback )
		{
			$generator = $this->getGenerator( 'sectionBlueprint', $name );
			$generator->create();

			$callback( $generator );

			$this->execute( $generator );	
		}


		/**
		 * Generate a page blueprint
		 * 
		 * @param String $name
		 * @param Closure $callback
		 * 
		 * @return void
		 */
		public function page( $name, Closure $callback )
		{
			$generator = $this->getGenerator( 'pageBlueprint', $name );
			$generator->create();

			$callback( $generator );

			$this->execute( $generator );	
	
		}

		/**
		 * Return a post generator
		 * 
		 * @param  String  $type     
		 * @param  Closure $callback 
		 * 
		 * @return void      
		 */
		public function column( $name, Closure $callback ) 
		{
			$generator = $this->getGenerator( 'columnBlueprint', $name );
			$generator->create();

			$callback( $generator );

			$this->execute( $generator );	
		}


		/**
		 * Return a post generator
		 * 
		 * @param  String  $type     
		 * @param  Closure $callback 
		 * 
		 * @return void      
		 */
		public function post( $attributes ) 
		{
			return new PostGenerator( $attributes );	
		}




		/**
		 * Execute the generator
		 * 
		 * @return void
		 */
		public function execute( BaseGenerator $generator )
		{
			return $generator->build();
		}

		/**
		 * Returns the correct generator type
		 * 
		 * @param  String $type
		 * @param  String $subType
		 * 
		 * @return ChefSections\Generators\BaseGenerator
		 */
		public function getGenerator( $type, $name, $parameters = [] )
		{
			$attributes = array_merge( compact( 'name' ), $parameters );

			switch( $type ){

				case 'pageBlueprint':
					return new PageGenerator( $attributes );
					break;

				case 'sectionBlueprint':
					return new SectionGenerator( $attributes );
					break;

				case 'columnBlueprint':
					return new ColumnGenerator( $attributes );
					break;
			}
		}

	}