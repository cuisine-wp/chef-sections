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
	 * @param array $colProperties The defined field properties. Muse be an associative array.
	 * @throws Exception
	 * @return object ChefSections\Columns\ColumnBuilder
	 */
	public function make( $class, $id, $section, array $colProperties ){

	    try {
	        // Return the called class.
	        $class =  new $class( $id, $section, $colProperties );

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
	public function content( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\ContentColumn', $id, $section, $properties );

	}


	/**
	 * Return a Gallery Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\GalleryColumn
	 */
	public function gallery( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\GalleryColumn', $id, $section, $properties );

	}


	/**
	 * Return an Image Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\ImageColumn
	 */
	public function image( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\ImageColumn', $id, $section, $properties );

	}


	/**
	 * Return a Video Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\VideoColumn
	 */
	public function video( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\VideoColumn', $id, $section, $properties );

	}


	/**
	 * Return a Collection Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\CollectionColumn
	 */
	public function collection( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\CollectionColumn', $id, $section, $properties );

	}


	/**
	 * Return a Related Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\RelatedColumn
	 */
	public function related( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\RelatedColumn', $id, $section, $properties );

	}



}
