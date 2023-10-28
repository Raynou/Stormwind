<?php

namespace Stormwind;

class QueryHandler
{
    public function __construct($path) {
        $dotenv = Dotenv\Dotenv::createImmutable($path);
        $dotenv->load();

        $dbDialect = $_ENV['DB_DIALECT'];
        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPass = $_ENV['DB_PASSWORD'];

        try {
            $this->conn = new PDO("$dbDialect:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function getUserPicture($email)
    {
        $stmt = $this->conn->prepare("SELECT picture FROM mdl_user WHERE email like ?");
        $stmt->execute([$email]);
        $res = $stmt->fetch();
        return $res ? $res['picture'] : null;
    }

    function getUserId($email) 
    {
        $stmt = $this->conn->prepare("SELECT id FROM mdl_user WHERE email like ?");
        $stmt->execute([$email]);
        $res = $stmt->fetch();
        return $res ? $res['id'] : null;
    }

    function getUserPassword($email) 
    {
        $stmt = $this->conn->prepare("SELECT password FROM mdl_user WHERE email like ?");
        $stmt->execute([$email]);
        $res = $stmt->fetch();
        return $res ? $res['password'] : null;
    }

}