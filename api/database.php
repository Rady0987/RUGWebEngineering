<?php
    class Database {
        private const HOST = "localhost";
        private const DATABASE = "imdbapi"; // here everyone can change the name of their db
        private const USERNAME = "imdbapi"; // their username
        private const PASSWORD = "imdbapi"; // their password. IMPORTANT: don't forget to return them to
                                            // the initial values!!!

        private $pdo;

        public function __construct() {
            $this->pdo = new PDO("mysql:host=".self::HOST.";dbname=".self::DATABASE.";charset=utf8", self::USERNAME, self::PASSWORD);
        }

        public function execute($query) {
            $q = $this->pdo->prepare($query->getQuery());
            $q->execute($query->getArguments());
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    class Query {
        private $baseQuery;
        private $limitQuery;
        private $orderQuery;
        private $constraints;
        private $arguments;

        public function __construct() {
            $this->baseQuery = "";
            $this->limitQuery = "";
            $this->orderQuery = "";
            $this->constraints = array();
            $this->arguments = array();
        }

        public function setBaseQuery($baseQuery) {
            $this->baseQuery = $baseQuery;
        }

        private function constraintsQuery() {
            if(empty($this->constraints)) {
                return "";
            } else {
                return " WHERE " . implode(' AND ', $this->constraints);
            }
        }

        public function getQuery() {
            return $this->baseQuery . $this->constraintsQuery() . $this->orderQuery . $this->limitQuery;
        }

        public function getArguments() {
            return $this->arguments;
        }

        public function addConstraint($constraint) {
            array_push($this->constraints, $constraint);
        }

        public function addArgument($argument) {
            array_push($this->arguments, $argument);
        }

        public function setLimit($limit) {
            $this->limitQuery = " LIMIT " . $limit;
        }

        public function setOrder($column, $asc) {
            $this->orderQuery = " ORDER BY " . $column . ($asc ? " ASC" : " DESC");
        }
    }
?>