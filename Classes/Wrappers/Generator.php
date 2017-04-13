<?php

namespace ChefSections\Wrappers;

class Generator extends Wrapper {

    /**
     * Return the igniter service key responsible for the Generator class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'generator';
    }

}
