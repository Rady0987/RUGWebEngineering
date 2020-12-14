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

        public function query($sql, $args) {
            $q = $this->pdo->prepare($sql);
            $q->execute($args);
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }

        public function addRelationsToMovieArray($data) {
            foreach($data as &$row) {
                $id = array($row['id']);
                $actors = $this->query("SELECT name FROM actors WHERE id IN (SELECT actor FROM movies_actors WHERE movie=?)", $id);
                $directors = $this->query("SELECT name FROM directors WHERE id IN (SELECT director FROM movies_directors WHERE movie=?)", $id);
                $genres = $this->query("SELECT name FROM genres WHERE id IN (SELECT genre FROM movies_genres WHERE movie=?)", $id);
                $countries = $this->query("SELECT name FROM countries WHERE id IN (SELECT country FROM movies_countries WHERE movie=?)", $id);
                $languages = $this->query("SELECT name FROM languages WHERE id IN (SELECT language FROM movies_languages WHERE movie=?)", $id);
                $row = $row + array('actors' => $actors, 'directors' => $directors, 'genres' => $genres, 'countries' => $countries, 'languages' => $languages);
            }
            return $data;
        }
    }
?>