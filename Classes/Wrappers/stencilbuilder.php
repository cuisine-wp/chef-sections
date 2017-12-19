<?php

namespace CuisineSections\Wrappers;

class StencilBuilder extends Wrapper {

    /**
     * Return the igniter service key responsible for the StencilBuilder class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'stencilbuilder';
    }

}
