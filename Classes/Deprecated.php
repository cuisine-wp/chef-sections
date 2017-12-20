<?php

    namespace CuisineSections;

    class Deprecated{

        /**
         * Array holding all deprecated filters
         *
         * @var Array
         */
        protected $filterMap;

        /**
         * Array holding all deprecated actions
         *
         * @var Array
         */
        protected $actionMap;


        public function __construct()
        {
            $this->setDeprecatedFilterMap();
            $this->setDeprecatedActionMap();

            $this->testDrive();

		    /*foreach ( $this->filterMap as $new => $old ) {
			    add_filter( $new, [ $this, 'deprecatedFilterMapping' ] );
            }
            
            foreach( $this->actionMap as $new => $old ) {
                add_action( $new, [ $this, 'deprecatedActionMapping' ] );
            }*/
        }

        /**
         * The deprecated filter map
         *
         * @return void
         */
        public function setDeprecatedFilterMap()
        {
            $this->filterMap = [
                'cuisine_sections_remove_editor' => 'chef_sections_remove_editor',
                'cuisine_sections_post_types' => 'chef_sections_post_types',
                'cuisine_sections_dont_load' => 'chef_sections_dont_load',
                'cuisine_sections_save_html_output_as_content' => 'chef_sections_save_html_output_as_content',
                'cuisine_sections_base_panel_args' => 'chef_sections_base_panel_args',
                'cuisine_sections_setting_fields' => 'chef_sections_setting_fields',
                'cuisine_sections_default_allowed_views' => 'chef_sections_default_allowed_views',
                'cuisine_sections_show_section_ui' => 'chef_sections_show_section_ui',
                'cuisine_sections_show_template_ui' => 'chef_sections_show_template_ui',
                'cuisine_sections_show_container_ui' => 'chef_sections_show_container_ui',
                'cuisine_sections_containers' => 'chef_sections_containers',
                'cuisine_sections_section_blueprint_collection_query' => 'chef_sections_section_blueprint_collection_query',
                'cuisine_sections_save_column_properties' => 'chef_sections_save_column_properties',
                'cuisine_sections_default_column_args' => 'chef_sections_default_column_args',
                'cuisine_sections_default_allowed_columns' => 'chef_sections_default_allowed_columns',
                'cuisine_sections_collection_post_status' => 'chef_sections_collection_post_status',
                'cuisine_sections_collection_query' => 'chef_sections_collection_query',
                'cuisine_sections_collection_column_fields' => 'chef_sections_collection_column_fields',
                'cuisine_sections_collection_side_fields' => 'chef_sections_collection_side_fields',
                'cuisine_sections_collection_post_types' => 'chef_sections_collection_post_types',
                'cuisine_sections_content_column_fields' => 'chef_sections_content_column_fields',
                'cuisine_sections_save_column_properties' => 'chef_sections_save_column_properties',
                'cuisine_sections_social_icons' => 'chef_sections_social_icons',
                'cuisine_sections_enable_group_container' => 'chef_sections_enable_group_container',
                'cuisine_sections_section_template_class' => 'chef_sections_section_template_class',
                'cuisine_sections_output' => 'chef_sections_output',
                'cuisine_sections_container_output' => 'chef_sections_container_output',
                'cuisine_sections_column_types' => 'chef_sections_column_types',
                'cuisine_sections_section_type_classes' => 'chef_sections_section_type_classes',
                'cuisine_sections_section_types' => 'chef_sections_section_types',
                'cuisine_sections_hide_title' => 'chef_sections_hide_title',
                'cuisine_sections_hide_container' => 'chef_sections_hide_container',
                'cuisine_sections_default_section_args' => 'chef_sections_default_section_args',
                'cuisine_sections_panel_buttons' => 'chef_sections_panel_buttons',
                'cuisine_sections_section_attributes' => 'chef_sections_section_attributes',
                'cuisine_section_opening_div' => 'chef_section_opening_div',
                'cuisine_sections_display_section_wrapper' => 'chef_sections_display_section_wrapper',
                'cuisine_sections_closing_div' => 'chef_sections_closing_div',
                'chef_section_classes' => 'chef_section_classes',
                'cuisine_sections_section_template_base' => 'chef_sections_section_template_base',
                'cuisine_sections_column_template_base' => 'chef_sections_column_template_base',
                'cuisine_sections_located_template' => 'chef_sections_located_template',
                'cuisine_sections_default_template' => 'chef_sections_default_template',
                'cuisine_sections_template_files' => 'chef_sections_template_files',
                'cuisine_sections_section_template_hierarchy' => 'chef_sections_section_template_hierarchy',
                'cuisine_sections_template_post_getter' => 'chef_sections_template_post_getter',
                'cuisine_sections_block_template_hierarchy' => 'chef_sections_block_template_hierarchy',
                'cuisine_sections_container_template_hierarchy' => 'chef_sections_container_template_hierarchy',
                'cuisine_sections_element_template_hierarchy' => 'chef_sections_element_template_hierarchy',
                'cuisine_sections_reference_template_hierarchy' => 'chef_sections_reference_template_hierarchy'
            ];
        }
        
        /**
         * The deprecated action map
         *
         * @return void
         */
        public function setDeprecatedActionMap()
        {
            $this->actionMap = [

            ];
        }

        /**
         * Map a deprecated action
         *
         * @param Mixed $data
         * @param Mixed $arg_1
         * @param Mixed $arg_2
         * @param Mixed $arg_3
         * 
         * @return $data
         */
        public function deprecatedActionMapping( $data, $arg_1 = '', $arg_2 = '', $arg_3 = '' )
        {
            $actionMap = $this->actionMap;
        
            $filter = current_filter();
            if ( isset( $actionMap[ $filter ] ) ) {
                if ( has_action( $actionMap[ $filter ] ) ) {
                    $data = do_action( $actionMap[ $filter ], $data, $arg_1, $arg_2, $arg_3 );
                    if ( ! defined( 'DOING_AJAX' ) ) {
                        _deprecated_function( 'The ' . $actionMap[ $filter ] . ' action', '', $filter );
                    }
                }
            }
            return $data;
        }

         /**
         * Map a deprecated filter
         *
         * @param Mixed $data
         * @param Mixed $arg_1
         * @param Mixed $arg_2
         * @param Mixed $arg_3
         * 
         * @return $data
         */
        public function deprecatedFilterMapping( $data, $arg_1 = '', $arg_2 = '', $arg_3 = '' )
        {
            $filterMap = $this->filterMap;
        
            $filter = current_filter();
            if ( isset( $filterMap[ $filter ] ) ) {
                if ( has_filter( $filterMap[ $filter ] ) ) {
                    $data = apply_filters( $filterMap[ $filter ], $data, $arg_1, $arg_2, $arg_3 );
                    if ( ! defined( 'DOING_AJAX' ) ) {
                        _deprecated_function( 'The ' . $filterMap[ $filter ] . ' filter', '', $filter );
                    }
                }
            }
            return $data;
        }

        public function testDrive()
        {
            /*$test = new \CuisineSections\SectionTypes\ContentSection([
                'id' => 1,
                'position' => 1,
                'post_id' => 19,
                'container_id' => 0, 
                'title' => 'Section title',
                'hide_title' => 0,
                'hide_container' => 1,
                'view' => 'fullwidth',
                'type' => 'section',
                'columns' => array(
                    1 => 'content'
                )
            ]);
            dd( $test );*/
        }
    }