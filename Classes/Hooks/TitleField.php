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
         * Method to override to define the input type
         * that handles the value.
         *
         * @return void
         */
        protected function fieldType(){
            $this->type = 'title';
        } 


        /**
         * Build the html
         *
         * @return String;
         */
        public function build(){

            $html = '';
            $value = $this->getValue();
            $choices = [ 'h1', 'h2', 'h3' ];

            $html .= '<span class="title-wrapper">';

            $html .= $this->buildInput();

            $html .= '<span class="type-select">';

                $html .= '<span class="icon">'.$value['type'].'</span>';                

                $html .= '<div class="type-sub-menu">';


                    $subName = $this->getSubFieldName();

                    foreach( $choices as $choice ){
                        
                        $html .= '<label class="title-radio">';
                            $html .= '<input ';
                            $html .= ' type="radio" class="multi title-radio" name="'.$subName.'" data-name="'.$this->name.'[type]" value="'.$choice.'"';
                            $html .= checked( $choice, $value['type'], false );
                            $html .= '/>';
                            $html .= '<span>'.$choice.'</span>';
                        $html .= '</label>';
                    }

                $html .= '</div>';

            $html .= '</span></span>';



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

                $html .= 'class="'.$this->getClass().' multi" ';

                $html .= 'name="'.$this->name.'[text]" ';

                $html .= $this->getValueAttr();

                $html .= $this->getPlaceholder();

                $html .= $this->getValidation();

            $html .= '/>';  

            return $html;
        }


        /**
         * Returns the name for the radio-buttons
         * 
         * @return string
         */
        public function getSubFieldName()
        {
            $name = $this->getProperty( 'fieldName' );
            if( $name == '' )
                $name = uniqid();

            $name .= '[type]';

            return $name;
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

            $val = false;

            if( $this->properties['defaultValue'] )
                $val = $this->getDefault();

            if( $val && !is_array( $val ) )
                $val = [ 'text' => $val, 'type' => 'h2' ];

            if( !$val || empty( $val ) )
                $val = [ 'text' => '', 'type' => 'h2' ];


            if( !isset( $val['type'] ) )
                $val['type'] = 'h2';

            return $val;
        }

    }