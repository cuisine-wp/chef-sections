<?php

namespace CuisineSections\Wrappers;

class SettingsPanel extends Wrapper {

    /**
     * Return the igniter service key responsible for the SettingsPanel class.
     * The key must be the same as the one used in the assigned
     * igniter service.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'settingspanel';
    }

}
