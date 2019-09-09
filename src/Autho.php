<?php
namespace Mackilanu\Autho;
require_once '../config/bootstrap.php';
require_once 'Exceptions.php';

use Mackilanu\Autho\ValidationException as Exception;
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
            if(strlen($data['password']) < $pw_len)
                throw new ValidationException("The provided password is too short. The minimum length is {$pw_len}.");
        } catch (ValidationException $e) {
            return $e;
        }
        $fields['password'] = password_hash($data['password'], getenv('HASHING_ALGORITHM'));
            try {
                $user = new User;
                foreach ($fields as $key => $val) {
                    $user->$key = $val;
                }
                $user->save();
                return $user;
            }catch (PDOException $e) {
                echo $e;
            }
    }

    public static function authenticate()
    {

    }
}
