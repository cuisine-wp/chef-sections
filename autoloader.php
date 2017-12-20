<?php
namespace CuisineSections;

class Autoloader
{

    /**
     * Load the initial static files:
     *
     * @return void
     */
    public function load()
    {
        //for the front:
        Front\Ajax::getInstance();
        Front\Assets::getInstance();
        Front\EventListeners::getInstance();

        //and the admin:
        if( is_admin() ){
            Admin\Ajax::getInstance();
            Admin\Assets::getInstance();
            Admin\EventListeners::getInstance();
            Admin\MetaboxListener::getInstance();
        }
    }


    /**
     * Register the autoloader
     *
     * @return CuisineSections\Autoloader
     */
    public function register()
    {
        spl_autoload_register(function ($class) {

            try{
                if ( stripos( $class, __NAMESPACE__ ) === 0 ) {

                    $filePath = str_replace( '\\', DS, substr( $class, strlen( __NAMESPACE__ ) ) );
                    include( __DIR__ . DS . 'Classes' . $filePath . '.php' );

                }
            }catch( Exception $e ){
                
                dd( $e->getMessage() );

            }

        });

        return $this;
    }
}