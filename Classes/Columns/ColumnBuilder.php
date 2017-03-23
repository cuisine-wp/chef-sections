<?php
namespace ChefSections\Columns;

use ChefSections\Helpers\Column as ColumnHelper;

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
	 * Return a Socials-Column instance.
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\SocialsColumn
	 */
	public function socials( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\SocialsColumn', $id, $section, $properties );

	}


	/**
	 * Return an clear column instance
	 *
	 * @param int $id The ID for this column.
	 * @param array $extras Extra column properties.
	 * @return \ChefSections\Columns\EmptyColumn
	 */
	public function clear( $id, $section, array $properties = array() ){

	    return $this->make( 'ChefSections\\Columns\\ClearColumn', $id, $section, $properties );

	}


	/**
	 * If a column doesn't exist, try to locate it.
	 *
	 * @param string $name Name of the method
	 * @param  array $attr
	 * @return self::$name(), if it exists.
	 */
	public function __call( $name, $attr ){

		$types = ColumnHelper::getAvailableTypes();
		$names = array_keys( $types );

		//if method can be found:
		if( in_array( $name, $names ) ){

			$method = $types[ $name ];
			$props = ( isset( $attr[2] ) ? $attr[2] : array() );
			return $this->make( $method['class'], $attr[0], $attr[1], $props );
		}

		return false;
	}

}
