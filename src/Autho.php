<?php
namespace Mackilanu\Autho;
require_once '../config/bootstrap.php';
require_once 'Exceptions.php';

use Mackilanu\Autho\ValidationException as ValidationException;
use Mackilanu\Autho\AuthenticationException as AuthenticationException;
use Mackilanu\Autho\HashingException as HashingException;
use Mackilanu\Autho\EnvException as EnvException;
use Mackilanu\Models\User as User;
use PDOException;

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
     * @var const
     *
     * The hashing algorithm used for passwords
     * available in password_hash().
     */
    private static $hashing_algo;
    /**
     * @var array
     *
     * The fields that is used for authentication.
     * Default is 'email', 'password'
     */
    private $auth_fields = [];

    public function __construct() {
        switch (getenv('HASHING_ALGORITHM')) {
            case 'bcrypy':
                self::$hashing_algo = PASSWORD_BCRYPT;
                break;
            case 'default':
                self::$hashing_algo = PASSWORD_DEFAULT;
                break;
            case 'argon2i':
                self::$hashing_algo = PASSWORD_ARGON2I;
                break;
            case 'argon2id':
                self::$hashing_algo = PASSWORD_ARGON2ID;
                break;
        }
        $this->get_auth_fields();
    }

    public static function registerUser(array $data)
    {
        $autho = new User;
        $definedFields = $autho->getDefinedFields();
        foreach ($definedFields['fillable'] as $field) {
           try {
               if(!isset($data[$field]))
                   throw new ValidationException("Field '{$field}' is not provided");
           }catch (ValidationException $e) {
               return $e;
           }
        }

        try {
            $pw_len = getenv('MIN_PASSWORD_LEN');

            if(isset($data['password'])) {
                if (strlen($data['password']) < $pw_len)
                    throw new ValidationException("The provided password is too short. The minimum length is {$pw_len}.");
            }

            if(isset($data['email'])) {
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                    throw new ValidationException("Provided email '{$data['email']}' is not correctly formatted.");
            }
        } catch (ValidationException $e) {
            return $e;
        }

        $data['password'] = password_hash($data['password'], self::$hashing_algo);
            try {
                $user = new User;
                foreach ($data as $key => $val) {
                    $user->$key = $val;
                }
                $user->save();
                return $user;
            }catch (PDOException $e) {
                echo $e;
            }
    }

    /**
     * @param $val1
     * @param $val2
     * @return bool|int
     *
     * If true, the authentication was successful.
     * If false, the authentication failed to too wrong credentials.
     * If 2, the provided first field in Env variable AUTH_FIELDS does not exist in database.
     */
    public function authenticate($val1, $val2)
    {
            $user = User::where($this->auth_fields[0], $val1)->get()[0];
            if(count($user) > 0) {
                if(isset($user->password)) {
                    if(password_verify($val2, $user->password)) {
                        return true;
                    }else {
                        return false;
                    }
                }else {
                    return 2;
                }
            }else {
                return false;
            }
    }

    private function get_auth_fields() : void {
        $unplitted_fields = getenv('AUTH_FIELDS');

        $splited_fields = explode('|', $unplitted_fields);

        try {
            if(count($splited_fields) !== 2) {
                throw new EnvException("Wrong format on Env variable AUTH_FIELDS.");
            }

            $this->auth_fields = $splited_fields;
        }catch (EnvException $e) {
            echo $e;
        }
    }
}
