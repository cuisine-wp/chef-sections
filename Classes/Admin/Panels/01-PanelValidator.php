<?php

	namespace ChefSections\Admin\Panels;

	use Cuisine\Wrapper\User;
	use ChefSections\Collections\ContainerCollection;


	class PanelValidator{

		/**
		 * Panel which rules we're validating
		 * 
		 * @var ChefSections\Admin\Panels\BasePanel
		 */
		protected $panel;

		/**
		 * Section we're checking against
		 * 
		 * @var ChefSections\SectionTypes\BaseSection;
		 */
		protected $section;


		/**
		 * All rules for this panel
		 * 
		 * @var Array
		 */
		protected $rules;

		/**
		 * All validators
		 * 
		 * @var Array
		 */
		protected $validators = [
			'sectionType',
			'sectionView',
			'inContainer',
			'containerType',
			'userCap'
		];


		/**
		 * Constructor
		 * 
		 * @param Panel $panel 
		 * @param SectionType $section
		 *
		 * @return void 
		 */
		public function __construct( $panel, $section )
		{
			$this->panel = $panel;
			$this->section = $section;
			$this->rules = $panel->options['rules'];	
		}


		/**
		 * Checks to see wether all validations are valid
		 * 
		 * @return boolean
		 */
		public function isValid()
		{
			$valid = true;

			foreach( $this->validators as $name ){

				$method = 'validate'.ucfirst( $name );
				if( !$this->{$method}() ){
					$valid = false;
					break;
				}

			}

			return $valid;
		}


		/**
		 * Check the section type
		 * 
		 * @return bool
		 */
		protected function validateSectionType()
		{
			if( !isset( $this->rules['sectionType'] ) )
				return true;

			if( $this->rules['sectionType'] == $this->section->type )
				return true;

			return false;	
		}


		/**
		 * Check the section's view settings
		 * 
		 * @return bool
		 */
		protected function validateSectionView()
		{
			if( !isset( $this->rules['sectionView'] ) )
				return true;

			if( $this->rules['sectionView'] == $this->section->view )
				return true;

			return false; 		
		}


		/**
		 * Check if this section is in a (specific) container
		 * 
		 * @return bool
		 */
		protected function validateInContainer()
		{
			//check if there are rules for inside a container
			if( !isset( $this->rules['inContainer'] ) )
				return true;

			
			//set the rules
			if( !is_array( $this->rules['inContainer'] ) )
				$this->rules['inContainer'] = [ $this->rules['inContainer'] ];

			//if no container Id; return false
			if( is_null( $this->section->container_id ) ){
				return false;
			
			//if the rule is for all containered sections, return true:
			} else if( in_array( 'all', $this->rules['inContainer'] ) ){
				return true;

			//else check specific containers:
			}else{

				$container = new ContainerCollection();
				$container = $container->getById( $this->section->container_id, $this->section->post_id );

				//check the container 
				if( in_array( $container['slug'], $this->rules['inContainer'] ) )
					return true;
			
			}
		
			return false;
		}


		/**
		 * Check the container type
		 * 
		 * @return bool
		 */
		protected function validateContainerType()
		{
			//check if there are rules for inside a container
			if( !isset( $this->rules['containerType'] ) )
				return true;

			if( $this->section->type != 'container' )
				return false;


			if( isset( $this->section->slug ) ){
	
				if( $this->section->slug == $this->rules['containerType'] )
					return true;	
			
			}else{
				$container = new ContainerCollection();
				$container = $container->getById( $this->section->id, $this->section->post_id );

				if( $container['slug'] == $this->rules['containerType'] )
					return true;

			}

			return false;

		}


		/**
		 * Validate the user capabilities
		 * 
		 * @return bool
		 */
		protected function validateUserCap(){

			if( !isset( $this->rules['userCap'] ) )
				return true;

			return User::hasCap( $this->rules['userCap'] );

		}
	}