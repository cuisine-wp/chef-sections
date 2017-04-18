<?php

	namespace ChefSections\Admin\Generators;

	use ChefSections\Contracts\Generator as GeneratorContract;

	class BaseGenerator implements GeneratorContract{


		/**
		 * Post ID for the current request
		 * 
		 * @var int
		 */
		protected $postId;


		/**
		 * Data we're generating
		 * 
		 * @var array
		 */
		protected $data;


		/**
		 * Constructor for this class
		 *
		 * @param int $postId
		 *
		 * @return ChefSections\Admin\Generators\BaseGenerator
		 */
		public function __construct( $postId )
		{
			$this->postId = $postId; 
		}



		/*****************************************************/
		/**              Setting and sanitizing data         */
		/*****************************************************/

		/**
		 * Set the data for this generator
		 * 
		 * @param Array $data
		 *
		 * @return ChefSections\Generators\BaseGenerator
		 */
		public function set( $data )
		{
			$this->$data = $this->sanitizeData( $data );
			return $this;
		}


		/**
		 * Clear any data that needs to be cleared
		 * 
		 * @param  Array  $data
		 * 
		 * @return Array
		 */
		public function sanitizeData( $data )
		{
			return $data;
		}


		/*****************************************************/
		/**            Base Generator functions              */
		/*****************************************************/


		/**
		 * Maybe generate a new post-type post out of a blueprint
		 * 
		 * @return bool
		 */
		public function maybeGenerate()
		{
			if( $this->check() && !$this->ran() )
				return $this->generate();

			return false;
		}


		/**
		 * Checks if this generator should be run
		 * 
		 * @return bool
		 */
		public function check()
		{
			if( !empty( $this->data ) && !is_null( $this->data ) )
				return true;
		
			return false;
		}

		/**
		 * Check wether or not this generator ran already
		 * 
		 * @return bool
		 */
		public function ran()
		{
			return false;
		}


		/**
		 * Generate what needs to be generated
		 * 
		 * @return bool
		 */
		public function generate()
		{
			return true;	
		}

	}