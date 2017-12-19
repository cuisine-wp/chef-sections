<?php

	namespace CuisineSections\Generators;

	use CuisineSections\Contracts\Generator as GeneratorContract;

	class ColumnGenerator extends BaseGenerator implements GeneratorContract{

		/**
		 * Build this column
		 * 
		 * @return void
		 */
		public function build()
		{
			$key = $this->get( 'sectionId' ).'_'.$this->get( 'id' );
			$props = $this->generate();

			update_post_meta( $this->postId, '_column_props_'.$key, $props );
			update_post_meta( $this->postId, '_column_type_'.$key, $this->get( 'type' ) );
		}

		/**
		 * Build this object
		 * 
		 * @return void
		 */
		public function generate()
		{
			$attributes = parent::generate();
			unset( $attributes['type'] );
			unset( $attributes['id'] );
			unset( $attributes['postId'] );
			unset( $attributes['sectionId'] );
			unset( $attributes['getDefaultAttributes'] );

			return $attributes;
		}
	}