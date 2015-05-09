<?php
namespace ChefSections\Columns;

/**
 * Column factory.
 * @package ChefSections\Columns
 */
class ColumnBuilder {



	/**
	 * Call the appropriate field class.
	 *
	 * @param string $class The custom field class name.
	 * @param array $fieldProperties The defined field properties. Muse be an associative array.
	 * @throws Exception
	 * @return object ChefSections\Columns\ColumnBuilder
	 */
	public function make( $class, array $fieldProperties ){

	    try {
	        // Return the called class.
	        $class =  new $class( $fieldProperties );

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
	public function content( $id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\ContentColumn', $id, $properties );

	}


	/**
	 * Return a Gallery Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\GalleryColumn
	 */
	public function content( $id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\GalleryColumn', $id, $properties );

	}


	/**
	 * Return an Image Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\ImageColumn
	 */
	public function image( $id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\ImageColumn', $id, $properties );

	}


	/**
	 * Return a Video Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\VideoColumn
	 */
	public function content( $id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\VideoColumn', $id, $properties );

	}


	/**
	 * Return a Collection Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\CollectionColumn
	 */
	public function content( $id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\CollectionColumn', $id, $properties );

	}


	/**
	 * Return a Related Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\RelatedColumn
	 */
	public function content( $id, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\RelatedColumn', $id, $properties );

	}



}
