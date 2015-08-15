<?php

namespace ChefSections\Sections;

use ChefSections\Wrappers\Column;

use Cuisine\Wrappers\User;

class Reference extends Section{

	/**
	 * This is a section reference, meaning it's a copy of a templated section
	 * 
	 * @var boolean
	 */
	public $is_reference = true;




	/**
	 * Overwrite the Build function for this reference
	 * 
	 * @return String (html, echoed)
	 */
	public function build(){

		if( is_admin() ){

			$class = 'section-wrapper ui-state-default section-'.$this->id;
			if( $this->is_reference )
				$class .= ' reference';

			echo '<div class="'.$class.'" ';
				echo 'id="'.$this->id.'" ';
				$this->buildIds();
			echo '>';

				if( User::hasRole( 'administrator' ) )
					$this->buildControls();

				echo '<div class="section-columns '.$this->view.'">';
	

				foreach( $this->columns as $column ){
	
					echo $column->build();
	
				}

				echo '<p class="template-txt">';
					printf( __( 'Dit is het sjabloon "%s." Bij het aanpassen wordt deze op iedere pagina aangepast.', 'chefsections' ), get_the_title( $this->reference_id ) );
				echo '</p>';

				echo '<a href="'.admin_url( 'post.php?post='.$this->reference_id.'&action=edit' ).'" class="button button-primary">';

					_e( 'Bewerk dit sjabloon', 'chefsections' );

				echo '</a>';

				echo '<div class="clearfix"></div>';
				echo '</div>';

				if( User::hasRole( 'administrator' ) ){
					$this->bottomControls();
					$this->buildSettingsPanel();
				}
			
			echo '<div class="loader"><span class="spinner"></span></div>';
			echo '</div>';
		}
	}


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