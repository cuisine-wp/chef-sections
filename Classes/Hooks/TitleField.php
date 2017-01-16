<?php

    namespace ChefSections\Hooks;
    
    use Cuisine\Fields\DefaultField;
    use Cuisine\Wrappers\Field;


    class TitleField extends DefaultField{
    
        /**
         * The type of this field
         * 
         * @var String
         */
        public $type = 'title';


        /**
         * Build the html
         *
         * @return String;
         */
        public function build(){

            $html = '';
            $value = $this->getValue();
            $choices = [ 'h1', 'h2', 'h3' ];

            $html .= $this->buildInput();

            $html .= '<span class="type-select">';


                $html .= '<div class="type-sub-menu">';

                    foreach( $choices as $choice ){

                        echo '<label>';
                            echo '<span>'.$choice.'</span>';
                            echo '<input '.checked( $choice, $value['type'], false );
                            echo ' type="radio" name="'.$this->name.'[type]">';
                        echo '</label>';
                    }

                $html .= '</div>';

            $html .= '</span>';



            return $html;
        }


        /**
         * Create the input field
         * 
         * @return string
         */
        public function buildInput()
        {
            $html = '<input type="'.$this->type.'" ';

                $html .= 'id="'.$this->id.'" ';

                $html .= 'class="'.$this->getClass().'" ';

                $html .= 'name="'.$this->name.'[text]" ';

                $html .= $this->getValueAttr();

                $html .= $this->getPlaceholder();

                $html .= $this->getValidation();

            $html .= '/>';  

            return $html;
        }


        /**
         * Returns the value attribute
         * 
         * @return String
         */
        public function getValueAttr(){

            $val = $this->getValue();
            if( $val )
                return ' value="'.$val['text'].'"';

        }


        /**
         * Get the value of this field:
         * 
         * @return String
         */
        public function getValue(){

            global $post;
            $value = $val = false;

            if( $value && !$val )
                $val = $value;

            if( $this->properties['defaultValue'] && !$val )
                $val = $this->getDefault();


            $val = $this->parseHtml( $val );
            return $val;
        }



        /**
         * Returns the html string as an array with title type
         * 
         * @param  string $val
         * @return array
         */
        public function parseHtml( $val )
        {
                
        }
    }