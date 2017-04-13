<?php

	namespace ChefSections\Generators;

	use Exception;
	use Cuisine\Utilities\Fluent;
	use ChefSections\Wrappers\Generator;
	use ChefSections\Contracts\Generator as GeneratorContract;

	class BaseGenerator extends Fluent implements GeneratorContract{


		/**
		 * Default post type
		 * 
		 * @var string
		 */
		protected $defaultPostType = 'page-template';


		/**
		 * Build this generator and save it:
		 * 
		 * @return void
		 */
		public function build()
		{
			$data = $this->generate();
			return $data;
		}


		/**
		 * Build this object
		 * 
		 * @return void
		 */
		public function generate()
		{
			$attributes = $this->getAttributes();

			if( !isset( $attributes['postId'] ) && $this->get( 'createPost', true ) ){
				
				$postId = $this->generatePost( $attributes );
				$this->postId( $postId );
				$attributes['postId'] = $postId;

			}

			return $attributes;
		}

		/**
		 * Returns all default values as an array
		 * 
		 * @return array
		 */
		public function getAttributes()
		{
			$defaults = $this->getDefaultAttributes();
			$attributes = wp_parse_args( $this->attributes, $defaults );
			return $attributes;
		}


		/**
		 * Generate the accompanying post for this generator
		 * 
		 * @return int
		 */
		public function generatePost( Array $attributes )
		{
			if( !isset( $attributes['post'] ) || empty( $attributes['post_type'] ) )
				$attributes['post'] = [
					'title' => $this->get( 'name' ),
					'type' => $this->defaultPostType,
				];

			$postId = Generator::post( $attributes['post'] )->generate();

			if( is_null( $postId ) )
				throw new Exception( 'Failed to create post' );

			//set the post Id attribute
			$this->postId( $postId );
			return $postId; 
		}
	}