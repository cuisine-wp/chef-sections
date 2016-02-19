<?php

    namespace ChefSections\Hooks;
    
    use Cuisine\Fields\DefaultField;
    use Cuisine\Fields\SelectField;
    use Cuisine\Wrappers\Field;
    use Cuisine\Wrappers\Taxonomy;
    use Cuisine\Utilities\Sort;
    use Cuisine\Utilities\Url;


    class TaxonomySelect extends DefaultField{
    
        /**
         * The type of this field
         * 
         * @var String
         */
        public $type = 'taxonomySelect';



        public $taxonomies = array();


        /**
         * Method to override to define the input type
         * that handles the value.
         *
         * @return void
         */
        protected function fieldType(){
            $this->type = 'taxonomySelect';
        }



        /**
         * Build the html
         *
         * @return String;
         */
        public function render(){

            $this->taxonomies = $this->getTaxonomies();
            $this->setScripts();

            $value = $this->getValue();
            $iteration = 1;

            if( !empty( $value ) ){
                $iteration = count( $value );
            }

            $html = '<div class="taxonomy-select-field field-wrapper" data-iteration="'.$iteration.'">';



                if( !empty( $value ) ){

                    foreach( $value as $i => $item ){
                        $html .= $this->makeItem( $i, $item );
                    }

                }else{

                    $html .= $this->makeItem( 0 );

                }
        
            $html .= '</div>';
            
            echo $html;
            
            return $html;
        }


        /**
         * Return the template, for Javascript
         * 
         * @return String
         */
        public function renderTemplate(){

            $value = $this->getValue();
            $iteration = 1;

            if( !empty( $value ) ){
                $iteration = count( $value );
            }

            //make a clonable item, for javascript:
            $html = '<script type="text/template" id="taxonomy_select_item">';
                $html .= $this->makeItem( '<%= iteration %>' );
            $html .= '</script>';
            $html .= '<script type="text/javascript">';
                $html .= "var Taxonomies = '".json_encode( $this->taxonomies )."';";
            $html .= '</script>';

            return $html;
        }


        /**
         * Generate a single selector-wrapper
         * 
         * @param  int $iteration
         * @return string (html)
         */
        public function makeItem( $iteration, $tax = false ){

            if( $tax === false )
                $tax = array( 'tax' => 'category', 'terms' => array() );
                //$tax = $this->getTaxonomies();

            $html = '';
            $prefix = $this->name.'['.$iteration.']';
            
            $html .= '<div class="tax-select-wrapper" id="tax-'.$iteration.'">';


                $html .= '<select name="'.$prefix.'[tax]" class="taxonomy-selector multi">';
            
                    foreach( $this->taxonomies as $taxonomy => $terms ){

                        $html.= '<option value="'.$taxonomy.'" ';
                        $html.= selected( $tax['tax'], $taxonomy, false ).'>';

                            $html.= Taxonomy::name( $taxonomy );

                        $html.= '</option>';
                    }


            
                $html .= '</select>';
            
                $html .= '<select name="'.$prefix.'[terms]" class="term-selector multi" data-placeholder="'.__( 'Selecteer een of meerdere items', 'chefsections' ).'" multiple>';
                    

                    foreach( $this->taxonomies[ $tax['tax'] ] as $term ){

                        $html.= '<option value="'.$term->slug.'" ';
                        $html.= selected( in_array( $term->slug, $tax['terms'] ), true, false ).'>';

                            $html.= $term->name;

                        $html.= '</option>';

                    }

            
                $html .= '</select>';


                //add or remote this iteration:
                $html .= '<div class="add-remove">';

                    $html .= '<span class="add-tax add-remove-btn" data-id="'.$iteration.'">+</span>';
                    $html .= '<span class="remove-tax add-remove-btn" data-id="'.$iteration.'">-</span>';

                $html .= '</div>';

            $html .= '</div>';

            return $html;

        }


        /**
         * Returns an array of slug => label taxonomies within WordPress.
         * @return array
         */
        public function getTaxonomies(){

            $tax = array();

            $taxonomies = Taxonomy::get();
            $terms = get_terms( $taxonomies, array( 'hide_empty' => false ) );

            if( $terms ){

                foreach( $terms as $term ){
                
                    $tax[ $term->taxonomy ][] = $term; 

                }
           
            }

            return $tax;

        }


        /**
         * Add the scripts to the enqueue-list
         *
         * @return void
         */
        private function setScripts(){

            if( is_admin() ){

                $url = Url::plugin( 'chef-sections', true ).'Assets';

                wp_enqueue_script( 
                    'chosen', 
                    $url.'/js/libs/chosen.min.js', 
                    array( 'jquery' ) 
                );

                wp_enqueue_script(
                    'taxonomySelect',
                    $url.'/js/TaxonomySelect.js',
                    array( 'jquery', 'chosen' )
                );

            }
        }

    }
