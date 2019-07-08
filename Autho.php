<?php

class Autho {

    private static $username;
    private static $password;
    private static $email;

    /**
     * @return string
     */
    public static function getUsername() : string
    {
        return self::$username;
    }

    /**
     * @param string $username
     * @param int $minChars
     * @return bool|Exception
     */
    public static function setUsername(string $username, $minChars = 6)
    {
        try {
            if(strlen($username) >= $minChars) {
                self::$username = $username;
                return true;
            }
            throw new Exception("Username is too short, " . $minChars . " is required");
        }catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @return string
     */
    public static function getPassword() : string
    {
        return self::$password;
    }

    public static function setPassword(string $password, $minChars = 8)
    {
        try {
            if (strlen($password) >= $minChars) {
                self::$username = $password;
                return true;
            }
            throw new Exception("Too few characters");
        }catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @return string
     */
    public static function getEmail() : string
    {
        return self::$email;
    }

    /**
     * @param string $email
     * @return bool|Exception
     */
    public static function setEmail(string $email)
    {
        try {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                self::$email = $email;
                return true;
            }
            throw new Exception("Provided email is not an email.");
        }catch (Exception $e) {
            return $e;
        }
    }
}
