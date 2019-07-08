<?php
namespace Mackilanu\Autho;
include 'Exceptions.php';
use Mackilanu\Autho\ValidationException as Exception;

/**
 * Class Autho
 * @package Mackilanu\Autho
 *
 * A lighweight lbrary for handling authentication.
 *
 * @author Marcus Andersson
 */
class Autho {

    /**
     * @var string
     * The privided usrrname
     */
    private static $username;
    /**
     * @var string
     * The provided password
     */
    private static $password;
    /**
     * @var string
     * The provided Email adress
     */
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

    public static function registerUser(array $fields,
                                        string $table = 'users',
                                        string $hash  = 'bcrypt',
                                        bool $usePassword = true)
    {
        $hash_algo = "";
        switch ($hash) {
            case $hash == 'bcrypt':
                $hash_algo = 'PASSWORD_BCRYPT';
                break;
            case $hash = 'default':
                $hash_algo = 'PASSWORD_DEFAULT';
                break;
            case $hash = 'argon2i':
                $hash_algo = 'PASSWORD_ARGON2I';
                break;
            case $hash = 'argon2id':
                $hash_algo = 'PASSWORD_ARGON2ID';
                break;
        }
        if($usePassword) {
            try {
                if (!isset($fields['password'])) {
                    throw new Exception('Please provide field \'password\'.');
                }

            } catch (Exception $e) {
                return $e;
            }
            $fields['password'] = password_hash($fields['password'], $hash_algo);
        }


        foreach ($fields as $fieldName => $value) {

        }
    }

    public static function authenticate()
    {

    }
}
