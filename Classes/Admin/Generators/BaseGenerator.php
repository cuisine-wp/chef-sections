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


		/**
		 * Maybe generate a new post-type post out of a blueprint
		 * 
		 * @return bool
		 */
		public function maybeGenerate()
		{
			if( $this->check() )
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

			global $pagenow, $post;
			$status = get_post_status( $this->postId );

			if( $status == 'auto-draft' && $pagenow == 'post-new.php' )
				return true;

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