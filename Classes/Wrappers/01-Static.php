<?php

    namespace CuisineSections\Wrappers;
    
    class StaticInstance {
    
        /**
         * Static bootstrapped instance.
         *
         * @var \CuisineSections\Wrappers\StaticInstance
         */
        public static $instance = null;
    
    
        /**
         * Init the Assets Class
         *
         * @return \CuisineSections\Admin\StaticInstance
         */
        public static function getInstance(){
    
            return static::$instance = new static();
            
        }
    
    
    } 

