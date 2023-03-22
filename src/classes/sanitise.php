<?php
/**
 * Class to loop through the payload passed from the front end to sanitise the string values to remove all scripts and executable code.
 * @param string for cleaning
 * @return object containing cleaned input strings
 * @author Thomas Shirley - hello@thomas-shirley.com
 * @version 0.1
 */
class sanitise {

    

    public static function cleanUserLoginData(object $inputData): array {
        $cleanArray = array();
        foreach ($inputData as $key => $value) {
            $cleanArray[filter_var($key, FILTER_SANITIZE_ENCODED)] = filter_var($value, FILTER_SANITIZE_ENCODED);
        }
        return $cleanArray;
    }
    
    public static function cleanOrderData(object $inputData): array {
        $cleanArray = array();
        foreach ($inputData as $key => $value) {
            if (is_object($value)) {
                $cleanArray[$key] = (array) $value;
            }
            else {
                $cleanArray[$key] = filter_var($value, FILTER_SANITIZE_ENCODED);
            }
        }
        return $cleanArray;
    }
}

?>