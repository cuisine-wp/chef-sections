<?php

namespace ChefSections\Wrappers;

class ReferenceBuilder extends Wrapper {

    /**
     * Return the igniter service key responsible for the ReferenceBuilder class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'referencebuilder';
    }

}
