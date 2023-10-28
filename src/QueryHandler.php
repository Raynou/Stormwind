<?php declare(strict_types = 1);

namespace Stormwind;

use mysqli;

final class QueryHandler
{
    public function __construct() {
        $dbDialect = $_ENV['DB_DIALECT'];
        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPass = $_ENV['DB_PASSWORD'];

        $this->conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function getUserPicture($email)
    {
        $stmt = $this->conn->prepare("SELECT picture FROM mdl_user WHERE email like ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['picture'] : null;
    }

    function getUserId($email) 
    {
        $stmt = $this->conn->prepare("SELECT id FROM mdl_user WHERE email like ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['id'] : null;
    }

    function getUserPassword($email) 
    {
        $stmt = $this->conn->prepare("SELECT password FROM mdl_user WHERE email like ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['password'] : null;
    }
}