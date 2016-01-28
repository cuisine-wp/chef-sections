<?php

namespace ChefSections\Wrappers;

class Layout extends Wrapper {

    /**
     * Return the igniter service key responsible for the Layout class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'layout';
    }

}
