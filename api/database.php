<?php
    class Database {
        private const HOST = "localhost";
        private const DATABASE = "imdbapi";
        private const USERNAME = "imdbapi";
        private const PASSWORD = "imdbapi";

        private $pdo;

        public function __construct() {
            $this->pdo = new PDO("mysql:host=".self::HOST.";dbname=".self::DATABASE.";charset=utf8", self::USERNAME, self::PASSWORD);
        }

        public function query($sql, $args) {
            $q = $this->pdo->prepare($sql);
            $q->execute($args);
            return $q->fetchAll(PDO::FETCH_CLASS);
        }
    }
?>