<?php
namespace Macilanu\Autho;
require_once 'Exceptions.php';

use Mackilanu\Autho\DBConnectionException;
use PDO;

/**
 * Class DB
 * @package Macilanu\Autho
 */
class DB {
    private static $host = 'localhost';
    private static $user = 'root';
    private static $pass = 'root';
    private static $dbname = 'default';
    private $dbh;
    public function __construct()
    {
        $dsn = 'mysql:host=' . self::$host .';dbname=' . self::$dbname;
        $pdo = new PDO($dsn, self::$user, self::$pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh = $pdo;
    }

    public function registerUser($table, array $fields)
    {

    }
}
