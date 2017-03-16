<?php

	namespace ChefSections\Collections;


	class Collection{

		/**
		 * Post ID for this collection
		 * 
		 * @var int
		 */
		protected $postId;


		/**
		 * Array of all items
		 * 
		 * @var Array
		 */
		protected $items;

		/**
		 * Array of items, converted to objects
		 * 
		 * @var Array
		 */
		protected $objects;


		/**
		 * Highest ID
		 * 
		 * @var integer
		 */
		protected $highestId;


		/**
		 * Name of the meta-key for these items
		 * 
		 * @var string
		 */
		protected $metaName;


		/**
		 * Sets the return value for this collection
		 * 
		 * @var string
		 */
		protected $returnValue;


		/**
		 * Constructor
		 * 
		 * @param int $postId
		 */
		public function __construct( $postId )
		{
			$this->postId = $postId;
			$this->items = $this->getItems();
			$this->objects = $this->createObjects();
			$this->returnValue = 'objects';
		}



		/*=============================================================*/
		/**             Getters                                        */
		/*=============================================================*/

		/**
		 * Returns all objects
		 * 
		 * @return Array
		 */
		public function all()
		{
			return $this->getReturnValue();
		}


		/**
		 * Get a specific key
		 * 
		 * @param  String $key
		 * 
		 * @return Array | Object
		 */
		public function get( $key, $default = null )
		{
			$values = $this->getReturnValue();

			if( is_array( $values ) && isset( $values[ $key ] ) )
				return $values[ $key ];

			return $default;
		}

		/**
		 * Returns the first object in the array
		 * 
		 * @return Array | Object
		 */
		public function first()
		{
			$values = $this->getReturnValue();

			if( is_array( $values ) )
				$values = array_values( $values );

			return $values[ 0 ];
		}


		/**
		 * Returns wether this collection is empty
		 * 
		 * @return bool
		 */
		public function empty()
		{
			return ( empty( $this->items ) );	
		}


		/*=============================================================*/
		/**             Return data                                    */
		/*=============================================================*/


		/**
		 * Checks which collection to return
		 * 
		 * @return Array of objects | Array of Arrays
		 */
		public function getReturnValue()
		{
			if( $this->returnValue == 'array' )
				return $this->items;

			return $this->objects;
		}


		/**
		 * Set the return value for this collection
		 * 
		 * @return ChefSections\Collections\Collection
		 */
		public function toArray()
		{
			$this->returnValue = 'array';
			return $this;
		}

		/**
		 * Set the method for this collection
		 * 
		 * @return ChefSections\Collections\Collection
		 */
		public function toObjects()
		{
			$this->returnValue = 'objects';
			return $this;
		}


		/*=============================================================*/
		/**             Set class data:                                */
		/*=============================================================*/


		/**
		 * Returns the items of this collection
		 * 
		 * @return Array
		 */
		public function getItems()
		{
			return get_post_meta( $this->postId, $this->metaName, true );
		}


		/**
		 * Returns an array of all objects
		 * 
		 * @return Array
		 */
		public function createObjects()
		{
			return $this->items;				
		}



		/*=============================================================*/
		/**             ID increments                                  */
		/*=============================================================*/


		/**
		 * Returns the highest ID of this collection
		 * 
		 * @return int
		 */
		public function getHighestId()
		{

			if( !is_null( $this->highestId ) )
				return $this->highestId;


			$highID = 0;
			if( !$this->empty() ){

				foreach( $this->items as $item ){

					if( isset( $item['id'] ) && $item['id'] > $highID )
						$highID = $item['id'];

				}
			}

			$this->highestId = $highID;
			return $highID;
		}


		/**
		 * Returns the highest ID of this collection
		 * 
		 * @return int
		 */
		public function setHighestId( $amount = 1 )
		{
			if( is_null( $this->highestId ) )
				$this->getHighestId();

			$this->highestId = $this->highestId + $amount;

			return $this->highestId;
		}


	}