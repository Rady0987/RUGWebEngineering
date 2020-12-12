<?php
    require("database.php");
    require("apicallprocessor.php");

    $database = new Database();
    $callManager = new CallManager($_SERVER, $_REQUEST);

    $callManager->addCallProcessor(new CallProcessor("GET", "actors", function($request) {
        global $database;
        $query = "SELECT * FROM actors";
        if(isset($request['name'])) {
            $query .= " WHERE lower(name) LIKE lower(?)";
        }
        $query .= " LIMIT 100000";
        $data = $database->query($query, array('%'.$request['name'].'%'));
        return new CallResponse(200, $data);
    }));
    $callManager->addCallProcessor(new CallProcessor("GET", "movies", function($request) {
        global $database;
        if($request['API_VAR'] === "") {
            return new CallResponse(200, array("empty var"));
        } else {
            $imdb_url = "https://www.imdb.com/title/".$request['API_VAR']."/";
            $data = $database->query("SELECT * FROM movies WHERE imdb_url=?", array($imdb_url));
            return new CallResponse(200, $data);
        }
    }));
    $callManager->run();
?>