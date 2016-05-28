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

            $value = $this->getValue();
            $iteration = 1;

            if( !empty( $value ) ){
                $iteration = count( $value ) - 1;
            }

            $html = '<div class="taxonomy-select-field field-wrapper" data-highest-id="'.esc_attr( $iteration ).'">';



                if( !empty( $value ) ){

                    foreach( $value as $i => $item ){
                        $html .= $this->makeItem( $i, $item );
                    }

                }else{

                    //$html .= $this->makeItem( 0 );
                    $html .= '<p>'.__( 'Currently there are no filters active', 'chefsections' ).'</p>';
                    $html .= '<span class="add-remove-btn add-tax msg-add-remove">'.__( 'Create a filter', 'chefsections' ).'</span>';

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

            $html = '';
            $prefix = $this->name.'['.$iteration.']';
            
            $html .= '<div class="tax-select-wrapper" id="tax-'.esc_attr( $iteration ).'">';


                $html .= '<select name="'.$prefix.'[tax]" class="taxonomy-selector multi">';
            
                    foreach( $this->taxonomies as $taxonomy => $terms ){

                        $html .= '<option value="'.esc_attr( $taxonomy ).'" ';
                        $html .= selected( $tax['tax'], $taxonomy, false ).'>';

                            $html.= Taxonomy::name( $taxonomy );

                        $html.= '</option>';
                    }


            
                $html .= '</select>';
            
                $html .= '<select name="'.$prefix.'[terms]" class="term-selector multi" data-placeholder="'.__( 'Select one or multiple items', 'chefsections' ).'" multiple>';
                    

                    foreach( $this->taxonomies[ $tax['tax'] ] as $term ){

                        $available = in_array( $term->slug, $tax['terms'] );
                        $html .= '<option value="'.esc_attr( $term->slug ).'" ';
                        $html .= selected( $available, true, false ).'>';

                            $html .= $term->name;

                        $html .= '</option>';

                    }

            
                $html .= '</select>';


                //add or remote this iteration:
                $html .= '<div class="add-remove">';

                    $html .= '<span class="add-tax add-remove-btn" data-id="'.esc_attr( $iteration ).'">+</span>';
                    $html .= '<span class="remove-tax add-remove-btn" data-id="'.esc_attr( $iteration ).'">-</span>';

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
                
                    $term->name = esc_html( $term->name );
                    $tax[ $term->taxonomy ][] = $term; 

                }
           
            }

            return $tax;

        }

    }
