<?php

namespace ChefSections\Wrappers;

class SectionsUi extends Wrapper {

    /**
     * Return the igniter service key responsible for the SectionsUi class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'sections-ui';
    }

}
