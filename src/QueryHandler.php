<?php declare(strict_types = 1);

namespace Stormwind;

use mysqli;

/**
 * Utilities for querying the Moodle Database
 */

final class QueryHandler
{
    /**
     * This constructor gets the information about your Moodle Database from the enviorment variables and creates a connection
     * with `mysqli`.
     */
    public function __construct() 
    {
        $dbDialect = $_ENV['DB_DIALECT'];
        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPass = $_ENV['DB_PASSWORD'];

        $this->conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        if ($this->conn->connect_error) 
        {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    /**
     * Finds the user's profile picture value in the `mdl_user` table.
     * 
     * @param string $email The email of the user.
     * 
     * @return int The value of the user's profile image, if doesn't exists return -1.
     */
    function getUserPicture($email)
    {
        $stmt = $this->conn->prepare("SELECT picture FROM mdl_user WHERE email like ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['picture'] : -1;
    }

    /**
     * Finds the user's id value in the `mdl_user` table.
     * 
     * @param string $email The email of the user.
     * 
     * @return int The user's id value.
     */

    function getUserId($email) 
    {
        $stmt = $this->conn->prepare("SELECT id FROM mdl_user WHERE email like ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['id'] : -1;
    }

    /**
     * Finds the user's password in the `mdl_user` table.
     * 
     * @param string $email The email of the user.
     * 
     * @return string The hash that represents the user's password.
     */
    function getUserPassword($email) 
    {
        $stmt = $this->conn->prepare("SELECT password FROM mdl_user WHERE email like ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['password'] : null;
    }
}