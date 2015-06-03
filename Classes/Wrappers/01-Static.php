<?php

    namespace ChefSections\Wrappers;
    
    class StaticInstance {
    
        /**
         * Static bootstrapped instance.
         *
         * @var \ChefSections\Wrappers\StaticInstance
         */
        public static $instance = null;
    
    
        /**
         * Init the Assets Class
         *
         * @return \ChefSections\Admin\StaticInstance
         */
        public static function getInstance(){
    
            return static::$instance = new static();
            
        }
    
    
    } 

