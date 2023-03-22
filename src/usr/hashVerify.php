<?php

/**
 * This class either creates a new PIN for validating mail sends or validates pins themselves.
 * @param DBConnection a PostgreSQL DB Connection passed into the constructor.
 * @param pin needs a sso that already exists within NBCUs corporate network
 * @return bool whether or not the pin & e-mail address are validated.
 * @author Thomas Shirley - hello@thomas.shirley.com
 * @version 0.1
 */
    class hashVerify {

        public string $storedHash;
        public function __construct(){
            $this->storedHash = file_get_contents('usr/hash.dat');
        }

        public function validatePassword($userInputPassword) : array{
                    if (password_verify($userInputPassword, $this->storedHash)) {
                        $validatedUser = array(
                            'result' => true
                        );
                        return $validatedUser;
                    } else {
                        //password doesn't match.
                        $validatedUser = array('result' => false);
                        return $validatedUser;
                    }
                }
                }

?>