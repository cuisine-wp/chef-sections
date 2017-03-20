<?php


	namespace ChefSections\Front\Templates;

	use ChefSections\Contracts\TemplateContract;

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
		 * Constructor
		 * 
		 * @param Mixed $object
		 */
		public function __construct( $object )
		{
			$this->object = $object;
		}
		

		/**
		 * Locate the right template file
		 * 
		 * @return void
		 */
		public function locate()
		{
			$templates = $this->searchTheme();
			$located = locate_template( $templates );

			$located = apply_filters( 'chef_sections_located_template', $located, $this );
		
			if( !$located )
				$located = $this->default();


			return $located;
		}


		/**
		 * Return to a default template, if not applicable
		 * 
		 * @return void
		 */
		public function default()
		{
				
		}


		protected function searchTheme(){

			$templates = $this->getHierarchy();

			if( !empty( $templates ) ){
				
				//general filter:
				$templates = apply_filters( 
								'chef_sections_template_files', 
								$templates, 
								$this->object
				);

				//type based filter:
        		$templates = apply_filters( 
        						'chef_sections_'.$this->type.'_template_files', 
        						$templates, 
        						$this->object
        		);

        		//sorting
				$templates = Sort::appendValues( $templates, '.php' );

			}

			return $templates;
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
		/**             Display & Get                         */
		/******************************************************/

		/**
		 * Display the actual template
		 * 
		 * @return String (html, echoed)
		 */
		public function display()
		{
			add_action( 'chef_sections_before_'.$this->type.'_template', $this->object );

			include( $this->located );

			add_action( 'chef_sections_after_'.$this->type.'_template', $this->object );		
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
	}