<?php

	namespace CuisineSections\Admin\Handlers;

	use Cuisine\Utilities\Session;

	class BaseHandler{


		/**
		 * ID of the current post
		 * 
		 * @var int
		 */
		protected $postId;


		/**
		 * The collection of this manager
		 * 
		 * @var CuisineSections\Collections\BaseCollection
		 */
		protected $collection;


		/**
		 * Construct this class
		 * 
		 */
		public function __construct()
		{
			$this->postId = Session::postId();
			$this->setCollection();

			return $this;
		}

		/**
		 * Ajax response
		 * 
		 * @return string (json, echoed)
		 */
		public function response( $response )
		{
			if( !is_array( $response ) )
				$response = [ 'html' => $response ];
			
			if( !isset( $response['error'] ) )
				$response['error']  = false;

			echo json_encode( $response );
		}

	}