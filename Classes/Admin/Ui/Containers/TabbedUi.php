<?php

	namespace ChefSections\Admin\Ui\Containers;

	use ChefSections\Helpers\SectionUi as SectionUiHelper;

	class TabbedUi{

		/**
		 * Container class
		 * 
		 * @var ChefSections\SectionTypes\
		 */
		protected $container;


		/**
		 * Constructor
		 * 
		 * @param ChefSections\SectionTypes\Container
		 *
		 * @return void
		 */
		public function __construct( $container)
		{
			$this->container = $container;
		}


		/**
		 * Builds the UI for a tabbed container
		 * 
		 * @return string (html, echoed)
		 */
		public function build()
		{

			$this->buildNav();

			echo '<div class="tabbed-content section-data" id="tabContentFor'.$this->container->id.'">';

				if( !$this->container->sections->isEmpty() ){

					foreach( $this->container->sections->all() as $section ){

						$instance = SectionUiHelper::getClass( $section );
                        if( !is_null( $instance ) ){
                            $instance->build();
                        }
					}
				}

			echo '</div>';	
		}

		/**
		 * Build the navigation
		 * 
		 * @return string (html,echoed )
		 */
		public function buildNav()
		{
            $class = 'tab-nav';
            if( sizeof( $this->container->allowedColumns ) > 1 ){
                $class .= ' section-sortables';
            }
            
			echo '<nav class="tab-nav-container"><div class="'.$class.'" id="tabsFor'.$this->container->id.'" data-container_id="'.$this->container->id.'">';

				if( !$this->container->sections->isEmpty() ){

                    echo '';
					$i = 0;
					foreach( $this->container->sections->all() as $section ){

						echo static::getTab( $section, ( $i == 0 ) );
						$i++;
                    }

				}

            echo '</div>';
                if( sizeof( $this->container->allowedColumns ) == 1 ){
                    $this->renderAddTabButton();
                }
            echo '</nav>';
		}


        /**
         * Get a single tab
         *
         * @param Section $section
         * @param boolean $active
         * @return String (html)
         */
		public static function getTab( $section, $active = false )
		{
			$title = $section->getProperty( 'tabTitle', $section->title );

			if( substr( strtolower( $title ), 0, 7 ) == 'section' && $section->title != '' )
				$title = $section->title;

			$class = 'tab';

			if( $active )
				$class .= ' active';

			$html = '<div class="'.$class.'" id="tab_'.$section->id.'" data-id="'.$section->id.'">';
				$html .= '<div class="pin">';
					$html .= '<span class="title">'.$title.'</span>';
					$html .= '<span class="dashicons dashicons-leftright "></span>';
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}


        /**
         * Add a single tab button
         *
         * @return String
         */
        public function renderAddTabButton()
        {
            $label = apply_filters( 'chef_sections_tab_container_button_label', __('New tab', 'chefsections' ), $this->container );
            $column = array_values( $this->container->allowedColumns );
            echo '<div class="add-single-tab" data-container_id="'.esc_attr($this->container->id).'" data-post_id="'.esc_attr($this->container->post_id).'" data-column="'.esc_attr($column[0]).'">';
                echo '<span class="dashicons dashicons-plus"></span> '.$label.'</span>';
            echo '</div>';
            
        }

	}
