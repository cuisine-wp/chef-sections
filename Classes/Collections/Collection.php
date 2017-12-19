<?php

	namespace CuisineSections\Collections;

	use Cuisine\Utilities\Collection as BaseCollection;

	class Collection extends BaseCollection{

		/**
		 * Post ID for this collection
		 * 
		 * @var int
		 */
		protected $postId;


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
		/**             Set class data:                                */
		/*=============================================================*/


		/**
		 * Returns the items of this collection
		 * 
		 * @return Array
		 */
		public function getItems()
		{
			$items = get_post_meta( $this->postId, $this->metaName, true );
			$response = [];
			
			if( !empty( $items ) ){
				foreach( $items as $item ){
					$response[ $item['id'] ] = $item;
				}
			}

			return $response;
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
			if( !$this->isEmpty() ){

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