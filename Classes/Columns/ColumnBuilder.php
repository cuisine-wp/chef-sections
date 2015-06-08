<?php
namespace ChefSections\Columns;

/**
 * Column factory.
 * @package ChefSections\Columns
 */
class ColumnBuilder {


	/*=============================================================*/
	/**             Make functions                                 */
	/*=============================================================*/


	/**
	 * Call the appropriate field class.
	 *
	 * @param string $class The custom field class name.
	 * @param array $colProperties The defined field properties. Muse be an associative array.
	 * @throws Exception
	 * @return object ChefSections\Columns\ColumnBuilder
	 */
	public function make( $class, $id, $section_id, array $colProperties ){

	    try {
	        // Return the called class.
	        $class =  new $class( $id, $section_id, $colProperties );

	    } catch(\Exception $e){

	        //@TODO Implement log if class is not found

	    }

	    return $class;

	}


	/**
	 * Return a Content Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\ContentColumn
	 */
	public function content( $id, $section_id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\ContentColumn', $id, $section_id, $properties );

	}


	/**
	 * Return an Image Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\ImageColumn
	 */
	public function image( $id, $section_id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\ImageColumn', $id, $section_id, $properties );

	}


	/**
	 * Return a Video Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\VideoColumn
	 */
	public function video( $id, $section_id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\VideoColumn', $id, $section_id, $properties );

	}


	/**
	 * Return a Collection Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\CollectionColumn
	 */
	public function collection( $id, $section_id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\CollectionColumn', $id, $section_id, $properties );

	}



	/**
	 * Return a Socials-Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\RelatedColumn
	 */
	public function socials( $id, $section_id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\SocialsColumn', $id, $section_id, $properties );

	}

	/**
	 * If a column doesn't exist, try to locate it.
	 *
	 * @param string $name Name of the method
	 * @param  array $attr
	 * @return self::$name(), if it exists.
	 */
	public function __call( $name, $attr ){

		$types = $this->getAvailableTypes();
		$names = array_keys( $types );

		//if method can be found:
		if( in_array( $name, $names ) ){

			$method = $types[ $name ];
			$props = ( isset( $attr[2] ) ? $attr[2] : array() );
			return $this->make( $method['class'], $attr[0], $attr[1], $props );
		}

		return false;
	}


	/*=============================================================*/
	/**             GETTERS & SETTERS                              */
	/*=============================================================*/


	/**
	 * Returns a filterable array of column types
	 *
	 * @filter chef_sections_column_types
	 * @return array
	 */
	public function getAvailableTypes(){

		$arr = array(

			'content' 		=> array(

				'name'		=> __( 'Tekst', 'chefsections' ),
				'class' 	=> 'ChefSections\\Columns\\ContentColumn'
			),

			'image'			=> array(
				'name'		=> __( 'Afbeelding', 'chefsections' ),
				'class' 	=> 'ChefSections\\Columns\\ImageColumn',			
			),

			'video'			=> array(

				'name'		=> __( 'Video', 'chefsections' ),
				'class' 	=> 'ChefSections\\Columns\\VideoColumn',
			),

			'collection'	=> array(

				'name'		=> __( 'Collectie', 'chefsections' ),
				'class'		=> 'ChefSections\\Columns\\CollectionColumn'
			),

			'related'		=> array( 

				'name'		=> __( 'Gerelateerd', 'chefsections' ),
				'class'		=> 'ChefSections\\Columns\\RelatedColumn'
			),

			'socials' 		=> array(
				'name'		=> __( 'Social knoppen', 'chefsections' ),
				'class'		=> 'ChefSections\\Columns\\SocialsColumn'
			),
		);


		$arr = apply_filters( 'chef_sections_column_types', $arr );
		return $arr;
	}




	/*=============================================================*/
	/**             AJAX                                           */
	/*=============================================================*/



	/**
	 * Save column data, for any column
	 * 
	 * @return bool
	 */
	public function saveProperties(){

		$id = $_POST['column_id'];
		$section_id = $_POST['section_id'];	
		$type = $_POST['type'];

		$column = $this->$type( $id, $section_id, array() );
		$column->saveProperties();
		$column->build();
		die();
	}



	/**
	 * Save column type, for any column
	 * 
	 * @return string, echoed
	 */
	public function saveType(){

		global $post;

		$id = $_POST['column_id'];
		$section_id = $_POST['section_id'];
		$type = $_POST['type'];

		update_post_meta( $post->ID, '_column_type_'.$id, $type );


		$_sections = get_post_meta( $post->ID, 'sections', true );
		$_sections[ $section_id ]['columns'][ $id ] = $type;
		update_post_meta( $post->ID, 'sections', $_sections );

		$this->refreshColumn();

	}

	/**
	 * Refresh a column
	 * 
	 * @return void
	 */
	public function refreshColumn(){

		$id = $_POST['column_id'];
		$section_id = $_POST['section_id'];
		$type = $_POST['type'];

		$newColumn = $this->$type( $id, $section_id, array() );
		$newColumn->build();
	}


}
