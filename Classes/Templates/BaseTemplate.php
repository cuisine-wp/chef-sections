<?php


	namespace CuisineSections\Templates;

	use Cuisine\Utilities\Url;
	use Cuisine\Utilities\Sort;
	use CuisineSections\Contracts\Template as TemplateContract;

	class BaseTemplate implements TemplateContract{


		/**
		 * The object we're finding a template for
		 * 
		 * @var mixed
		 */
		protected $object;


		/**
		 * Get the protected type
		 * 
		 * @var string
		 */
		protected $type = 'base';


		/**
		 * Get the default base folder
		 * 
		 * @var string
		 */
		protected $baseFolder = 'base/';


		/**
		 * The path to a located template
		 * 
		 * @var String (path)
		 */
		protected $located;



		/**
		 * Constructor
		 * 
		 * @param Mixed $object
		 */
		public function __construct( $object )
		{
			$this->object = $object;
			$this->baseFolder = apply_filters( 'cuisine_sections_'.$this->type.'_template_base', $this->baseFolder );

			$this->located = $this->locate();
		}


		
		/******************************************************/
		/**             Display & Get                         */
		/******************************************************/

		/**
		 * Display the actual template
		 * 
		 * @return String (html, echoed)
		 */
		public function display()
		{

			add_action( 'cuisine_sections_before_'.$this->type.'_template', $this->object );

			include( $this->located );

			add_action( 'cuisine_sections_after_'.$this->type.'_template', $this->object );		
		}	


		/**
		 * Returns the html
		 * 
		 * @return String (html)
		 */
		public function get()
		{
			ob_start();

				$this->display();

			return ob_get_clean();
		}
		
		/******************************************************/
		/**             Locate functions                      */
		/******************************************************/


		/**
		 * Locate the right template file
		 * 
		 * @return void
		 */
		public function locate()
		{
			$templates = $this->getThemeLocations();
			$located = locate_template( $templates );
			$located = apply_filters( 'cuisine_sections_located_template', $located, $this );
		
			if( !$located )
				$located = $this->getDefault();


			return $located;
		}


		/**
		 * Return to a default template, if not applicable
		 * 
		 * @return void
		 */
		public function getDefault()
		{
			$base = $this->pluginPath();
			$default = $base.'Sections/default.php';
			$default = apply_filters( 'cuisine_sections_default_template', $default, $this->object );
			return $default;
		}


		/**
		 * Returns the default hierarchy for this template
		 * 
		 * @return Array
		 */
		public function getHierarchy()
		{
			return [];
		}


		/******************************************************/
		/**             Helper functions                      */
		/******************************************************/

		/**
		 * Construct a path
		 * 
		 * @return string
		 */
		public function constructPath()
		{
			$num = func_num_args();
			$args = func_get_args();

			$string = $this->baseFolder;

			foreach( $args as $arg ){
				if( !is_null( $arg ) && $arg != '' )
					$string .= $arg.'-';
			}

			return substr( $string, 0, -1 );
		}

		/**
		 * Get the theme locations and pass them through filters
		 * 
		 * @return Array
		 */
		protected function getThemeLocations(){

			$templates = $this->removeDuplicates( $this->getHierarchy() );

			if( !empty( $templates ) ){
				
				//general filter:
				$templates = apply_filters( 
								'cuisine_sections_template_files', 
								$templates, 
								$this->object
				);

				//type based filter:
        		$templates = apply_filters( 
        						'cuisine_sections_'.$this->type.'_template_files', 
        						$templates, 
        						$this->object
        		);

        		//sorting
				$templates = Sort::appendValues( $templates, '.php' );

			}

			return $templates;
		}


		/**
		 * Remove duplicates from array
		 * 
		 * @return Array
		 */
		protected function removeDuplicates( $templates )
		{
			$response = [];

			foreach( $templates as $template ){
				if( !in_array( $template, $response ) )
					$response[] = $template;
			}

			return $response;
		}



		/**
		 * Return the default plugin template path
		 * 
		 * @return string
		 */
		public function pluginPath()
		{
			return Url::path( 'plugin', 'cuisine-sections/Templates', true );
		}



		/**
		 * Returns the current object
		 * 
		 * @return mixed
		 */
		public function getObject()
		{
			return $this->object;
		}

		/**
		 * Returns the type of this template
		 * 
		 * @return String
		 */
		public function getType()
		{
			return $this->type;
		}

	}