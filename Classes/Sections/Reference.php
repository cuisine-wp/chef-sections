<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;

class Reference extends Section{

	/**
	 * This is a section reference, meaning it's a copy of a templated section
	 * 
	 * @var boolean
	 */
	public $is_reference = true;




	/**
	 * Get the Columns in an array
	 * 
	 * @return array
	 */
	
	public function getColumns( $columns ){


		//get the parent's columns
		$parent = array_values( get_post_meta( $this->reference_id, 'sections', true ) );
		$arr = array();


		if( isset( $parent[0] ) ){

			$parent = $parent[0];
			$columns = $parent['columns'];


			//reset all other values as well:
			$this->resetSectionValues( $parent );
	
			//populate the columns array with actual column objects
			foreach( $columns as $col_key => $type ){
	
				$props = array(
					'post_id'	=>	 $this->reference_id
				);
	
				$arr[] = Column::$type( $col_key, $parent['id'], $props );
	
			}
	
		}

		return $arr;

	}

	/**
	 * Reset all other section-values that are important
	 * 
	 * @param  array $parent
	 * @return void
	 */
	public function resetSectionValues( $parent ){
		$this->title = $parent['title'];
		$this->view = $parent['view'];
		$this->name = $this->getName( $parent );
	}




}