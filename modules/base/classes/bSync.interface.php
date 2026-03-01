<?php

/**
 * Synchronization files interface
 */
interface bSync 
{
    /**
     * Performs the synchronization
     */
    public function syncronize();
    
    /**
     * Returns an array with the base synchronization files of the specified module.
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module);
}
