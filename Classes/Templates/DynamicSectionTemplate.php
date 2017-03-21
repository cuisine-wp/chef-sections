<?php

	namespace ChefSections\Templates;


	class DynamicSectionTemplate extends BaseSectionTemplate{

		/**
		 * Direct path to this section-file
		 * 
		 * @var String
		 */
		protected $path;

		/**
		 * Constructor
		 * 
		 * @param Mixed $object
		 */
		public function __construct( $object, $path )
		{
			$this->object = $object;
			$this->baseFolder = $this->path =$path;
			$this->located = $this->locate();
		}


		/**
		 * Locate the right template file
		 * 
		 * @return void
		 */
		public function locate()
		{	
			if( substr( $this->path, -4 ) != '.php' )
				$this->path = $this->path.'.php';
			
			return locate_template( $this->path );
		}
	}