<?php

    namespace ChefSections\Hooks;
    
    use Cuisine\Fields\DefaultField;
    use Cuisine\Fields\SelectField;
    use Cuisine\Wrappers\Field;
    use Cuisine\Wrappers\Taxonomy;
    use Cuisine\Utilities\Sort;


    class TaxonomySelect extends DefaultField{
    
        /**
         * The type of this field
         * 
         * @var String
         */
        public $type = 'taxonomySelect';




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

            $html = '<div class="taxonomy-select-field field-wrapper">';

                $i = 0;
                
                $this->makeItem( $i );
        
            $html .= '</div>';
            
            echo $html;

            return $html;
        }


        /**
         * Generate a single selector-wrapper
         * 
         * @param  int $iteration
         * @return string (html)
         */
        public function makeItem( $iteration, $tax = false ){

            $taxonomies = $this->getTaxonomies();
            /*$terms = $this->getTerms( $tax );

            $html = '';
            $prefix = 'tax['.$iteration.']';
            
            $html .= '<div class="tax-select-wrapper" id="tax-'.$iteration.'">';
                $html .= '<select name="{$prefix}[tax]">';
            
                    $html .= '<option value="'.$slug.'">'.$title.'</option>';
            
                $html .= '</select>';
            
                $html .= '<select name="{$prefix}[terms]">';
            
                    $html .= '<option value="'.$slug.'">'.$title.'</option>';
                    $html .= '<option value="'.$slug.'">'.$title.'</option>';
                    $html .= '<option value="'.$slug.'">'.$title.'</option>';
            
            
                $html .= '</select>';


                //add or remote this iteration:
                $html .= '<div class="add-remove">';

                    $html .= '<span class="add-tax add-remove-btn" data-id="'.$iteration.'">+</span>';
                    $html .= '<span class="remove-tax add-remove-btn" data-id="'.$iteration.'">-</span>';

                $html .= '</div>';

            $html .= '</div>';*/

        }


        /**
         * Returns an array of slug => label taxonomies within WordPress.
         * @return array
         */
        public function getTaxonomies(){

            $tax = array();

            $taxonomies = Taxonomy::get();

            if( $taxonomies ){

                foreach( $taxonomies as $taxonomy ){

                    cuisine_dump( $taxonomy );

                }
                    /* foreach ($taxonomies  as $taxonomy ) {
                       echo '<a>'. $taxonomy. '</a>';
                       $terms = get_terms("color");
                       $count = count($terms);
                       if ( $count > 0 ){
                           echo '<ul>';
                               foreach ( $terms as $term ) {
                                   echo "<li>" . $term->name . "</li>";
                               }
                           echo "</ul>";
                       }
                     }*/
            }

            return $tax;

        }


    }
