<?php
/**
 * Starts a session based on the data verified
 * @param string for cleaning
 * @return null simply sets the sessions variables
 * @author Thomas Shirley - hello@thomas-shirley.com
 * @version 0.1
 */

class startSession {
    public static function setAllSessionVars(){
        $_SESSION['priviledges_level'] = 1;
    }

}