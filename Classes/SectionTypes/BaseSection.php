<?php

	namespace ChefSections\SectionTypes;

	use Exception;
	use Cuisine\Wrappers\User;
	use Cuisine\Wrappers\Field;
	use Cuisine\Utilities\Sort;
	use ChefSections\Wrappers\Column;
	use ChefSections\Wrappers\Template;
	use ChefSections\Wrappers\SectionsBuilder;
	use ChefSections\Helpers\Section as SectionHelper;


	class BaseSection{

		/**
		 * Unique Id for this section, prefixed by the post_id
		 * 
		 * @var string
		 */
		public $id = '';

		/**
		 * Position of this section
		 * 
		 * @var integer
		 */
		public $position = 0;

		/**
		 * The post ID this section is tied to
		 * 
		 * @var integer
		 */
		public $post_id;


		/**
		 * Container id
		 * 
		 * @var integer
		 */
		public $container_id;

		/**
		 * Title of this section
		 * 
		 * @var string
		 */
		public $title = '';

		/**
		 * Name (used in code) of this section
		 * 
		 * @var string
		 */
		public $name = '';


		/**
		 * Viewmode of this section
		 * 
		 * @var string
		 */
		public $view = '';


		/**
		 * All columns in this section
		 * 
		 * @var array
		 */
		public $columns = array();


		/**
		 * Simple boolean to display or not display a section title
		 *
		 * @var boolean
		 */
		public $hide_title;


		/**
		 * Boolean to display or not display the section container
		 * 
		 * @var boolean
		 */
		public $hide_container;



		/**
		 * Array containing all properties of this section
		 * 
		 * @var array
		 */
		public $properties;



		/**
		 * Base type
		 * 
		 * @var string
		 */
		public $type = 'base';


		function __construct( $args ){
			
			$args = $this->sanitizeArgs( $args );

			//all properties in one object
			$this->properties = $args;
			$this->setAttributes( $args );

		}


		/**
		 * Sanitize the arguments this section was supplied with
		 * 
		 * @param  Array $args
		 * 
		 * @return Array
		 */
		public function sanitizeArgs( $args )
		{
			if( !isset( $args['id'] ) )
				throw new Exception( 'Section ID not found' );

			if( !isset( $args['post_id'] ) )
				throw new Exception( 'Post ID for section '.$args['id'].' not found.' );

			//title
			if( !isset( $args['title'] ) || !is_array( $args['title'] ) )
				$args['title'] = [ 'text' => $args['title'], 'type' => 'h2' ];

			if( !isset( $args['view'] ) )
				$args['view'] = 'fullwidth';

			if( !isset( $args['container_id'] ) || $args['container_id'] == '' )
				$args['container_id'] = null;

			if( !isset( $args['hide_container'] ) )
				$args['hide_container'] = 'false';

			if( !isset( $args['columns'] ) )
				$args['columns'] = [];


			//set the name	
			$args['name'] = $this->getName( $args );

			return $args;
		}


		/**
		 * Set all attributes
		 *
		 * @param Array $args
		 *
		 * @return void
		 */
		public function setAttributes( $args )
		{
			
			//all properties that we need to convert to Section attributes
			$attributes = $this->getAttributes();

			//set all specific attributes
			foreach( $attributes as $attribute ){
				
				$this->$attribute = ( isset( $args[ $attribute ] ) ? $args[ $attribute ] : null );

			}

			//columns
			$this->columns = $this->getColumns( $args['columns'] );
			
			//title:
			$this->title = $args['title']['text'];
			if( strtolower( $this->title ) == 'sectie titel' )
				$this->title = '';

			$this->hide_title = ( $this->title == '' ? true : false );

		}


		/**
		 * Returns all public attributes
		 * 
		 * @return array
		 */
		public function getAttributes()
		{
			$attributes = [ 'id', 'post_id', 'container_id', 'position', 'view', 'hide_title', 'hide_container'	];
			$attributes = apply_filters( 'chef_sections_section_attributes', $attributes );

			return $attributes;
		}

		
		/*=============================================================*/
		/**             Frontend                                       */
		/*=============================================================*/

		/**
		 * This function runs just before the template
		 * 
		 * @return void
		 */
		public function beforeTemplate(){

			//add a hook
			add_action( 'section_before_template', $this );

			$schema = $this->getSchema();
			$class = $this->getClass();
			
			//base html of a section-starting div
			$html = '<div '.$schema.' class="'.esc_attr( $class ).'"';

			//so people can add data-properties and other stuff
			$html = apply_filters( 'cuisine_section_opening_div', $html );

			echo $html.'>';

			do_action( 'chef_section_before_section_content', $this );

		}

		/**
		 * This function runs just after the template
		 * 
		 * @return void
		 */
		public function afterTemplate(){

				do_action( 'chef_section_after_section_content', $this );

			echo '</div>';

			//do something after template
			do_action( 'section_after_template', $this );

		}

		/**
		 * Get the class for this section
		 * 
		 * @return string
		 */
		public function getClass(){

			$class = 'section';
			$class .= ' '.$this->name;


			$classes = $this->getProperty( 'classes' );
			if( $classes ){

				if( is_array( $classes ) )
					$classes = explode( ' ', $classes );

				$class .= ' '.$classes;
			}

			$class = apply_filters( 'chef_section_classes', $class, $this );

			return $class;

		}

		/**
		 * Returns the schema.org declarations for this section
		 * 
		 * @return string
		 */
		private function getSchema(){

			$schema = 'itemscope ';
			$schema .= 'itemtype="http://schema.org/Collection"';

			return $schema;
		}



		/*=============================================================*/
		/**             Getters & Setters                              */
		/*=============================================================*/

		/**
		 * Return a property, or false if the property isn't found.
		 * 
		 * @param  string $name name of the property
		 * @param  mixed $default
		 * 
		 * @return mixed
		 */
		public function getProperty( $name, $default = false ){

			if( isset( $this->properties[ $name ] ) )
				return $this->properties[ $name ];

			return $default;

		}

		/**
		 * Get the Columns in an array
		 * 
		 * @return array
		 */
		public function getColumns( $columns ){

			$arr = array();

			if( !empty( $columns ) ){

				//populate the columns array with actual column objects
				foreach( $columns as $col_key => $type ){
		
					$props = array(
						'post_id'	=>	 $this->post_id
					);

					if( Column::typeExists( $type ) ){

						$arr[] = Column::$type( $col_key, $this->id, $props );
		
					}else{
						$arr[] = false;
					
					}
				}

				//sort by column position:
				$arr = Sort::byField( $arr, 'position', 'ASC' );

			}

			return $arr;

		}


		/**
		 * Get the name (used in code ) for this section
		 * 
		 * @param  array $args
		 * @return string
		 */
		public function getName( $args ){

			if( isset( $args['name'] ) && $args['name'] != '' )
				return $args['name'];

			if( isset( $args['title']['text'] ) && $args['title']['text'] != '' )
				return sanitize_title( $args['title']['text'] ).'-'.$args['id'];

			return $args['post_id'].'-'.$args['id'];
		}


		/**
		 * Get this sections's template slug
		 * 
		 * @return String
		 */
		public function getSlug(){

			global $post;
			return $post->post_name.'-'.sanitize_title( $this->title );

		}


		/**
		 * Returns the title field
		 * 
		 * @param  String       $name  
		 * @param  String 		$class 
		 * @return String
		 */
		public function getTitle()
		{
			$title = $this->getProperty( 'title', false );
			if( $title && isset( $title['text'] ) && $title['text'] != '' ){

				$string = '<'.$title['type'].' class="section-title" itemprop="name">';
					$string .= esc_html( $title['text'] ); 
				$string .= '</'.$title['type'].'>';

				return $string;
			}

			return null;
		}

		/**
		 * Echoes the output of getTitle
		 * 
		 * @param  String       $name  
		 * @param  String 		$class 
		 * @return String           
		 */
		public function theTitle()
		{
			$title = $this->getTitle();
			if( $title !== null )
				echo $title;
		}

	}