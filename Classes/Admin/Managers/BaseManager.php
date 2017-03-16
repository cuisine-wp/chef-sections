<?php

	namespace ChefSections\Admin\Managers;

	use Cuisine\Utilities\Session;

	class BaseManager{


		/**
		 * ID of the current post
		 * 
		 * @var int
		 */
		protected $postId;


		/**
		 * The collection of this manager
		 * 
		 * @var ChefSections\Collections\BaseCollection
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

	}