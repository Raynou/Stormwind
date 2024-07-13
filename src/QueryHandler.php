<?php

namespace Stormwind;
use Stormwind\UserNotFoundException;
use Exception;

use mysqli;

/**
 * Utilities for querying the Moodle Database
 */

final class QueryHandler
{
    /**
     * This constructor gets the information about your Moodle Database from the enviorment variables and creates a connection
     * with `mysqli`.
     * @param iterable $credentials An iterable with the credentials to connect to the database. If null, the constructor will use the enviorment variables.
     */
    public function __construct(?iterable $credentials = null) 
    {
        // Method overloading
        if($credentials == null) {
            $dbDialect = $_ENV['DB_DIALECT'];
            $dbHost = $_ENV['DB_HOST'];
            $dbName = $_ENV['DB_NAME'];
            $dbUser = $_ENV['DB_USER'];
            $dbPass = $_ENV['DB_PASSWORD'];
        } else {
            $dbDialect = $credentials['db_dialect'];
            $dbHost = $credentials['db_host'];
            $dbName = $credentials['db_name'];
            $dbUser = $credentials['db_user'];
            $dbPass = $credentials['db_password'];
        }

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

    /**
     * Gets `username`, `firstname`, `lastname` and `email` of user from the Moodle database with a given id.
     * @param int $id The user ID
     * 
     * @throws UserNotFoundException if the user with given id doesn't exist in the db. 
     * @return array An asociative array with the user information.
     */
    function getUserInfoWithID($id) 
    {   
        $stmt = $this->conn->prepare("SELECT username, firstname, lastname, email FROM mdl_user WHERE id like ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if($res === null)
        {
            throw new UserNotFoundException("User with id: " . $id ." couldn't be find");
        }

        return $res;
    }

    function insertAnalysis($analysis)
    {
        $user_id = intval($analysis->user_id);
        $timestamp = $analysis->timestamp;
        $page = $analysis->page;

        $stmt = $this->conn->prepare("INSERT INTO mdl_block_simplecamera_details (user_id, timestamp, page) VALUES (?, ?, ?)");

        $bindStatus = $stmt->bind_param("iss", $user_id, $timestamp, $page);
        if (!$bindStatus) {
            throw new Exception("Error binding SQL statements params: " . $analysis->user_id . ", " . $analysis->timestamp . ", " . $analysis->page);
        }

        $stmt->execute();

        $detailsId = $stmt->insert_id;
        $sentiment = $analysis->sentiment;

        $stmt = $this->conn->prepare("INSERT INTO mdl_block_simplecamera_analysis (sentiment, details) VALUES (?, ?)");

        $bindStatus = $stmt->bind_param("si", $sentiment, $detailsId);
        if (!$bindStatus) {
            throw new Exception("Error binding SQL statements params: " . $analysis->sentiment . ", " . $detailsId);
        }

        $stmt->execute();
    }
}