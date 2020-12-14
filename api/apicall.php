<?php
    ini_set('display_errors', 'on');
    ini_set('memory_limit','512M');
    require("database.php");
    require("apicallprocessor.php");

    $database = new Database();
    $callManager = new CallManager($_SERVER, $_REQUEST);

    $callManager->addCallProcessor(new CallProcessor("GET", "genres", function($request) {
        global $database;
        $args = array();
        $query = "SELECT * FROM genres";
        $constraints = array();
        if(isset($request['actor'])) {
            array_push($constraints, "id IN (SELECT genre FROM movies_genres WHERE movie IN (SELECT movie FROM movies_actors WHERE actor IN (SELECT id FROM actors WHERE lower(name) = lower(?))))");
            array_push($args, $request['actor']);
        }

        if(!empty($constraints)) $query .= " WHERE " . implode(' AND ', $constraints);

        $data = $database->query($query, $args);
        foreach($data as &$row) {
            unset($row['id']);
        }
        if(empty($data)) {
            return new CallResponse(404, array("error"=>"No genres found using the specified filters"));
        } else {
            return new CallResponse(200, $data);
        }
    }));
    $callManager->addCallProcessor(new CallProcessor("GET", "actors", function($request) {
        global $database;
        $args = array();
        $query = "SELECT * FROM actors";
        if(isset($request['name'])) {
            $query .= " WHERE lower(name) LIKE lower(?)";
            array_push($args, '%'.$request['name'].'%');
        }
        $data = $database->query($query, $args);
        return new CallResponse(200, $data);
    }));
    $callManager->addCallProcessor(new CallProcessor("GET", "movies", function($request) {
        global $database;
        $args = array();
        if($request['API_VAR'] === "") {
            if(isset($request['details'])) {
                $query = "SELECT * FROM movies";
            } else {
                $query = "SELECT id, title, imdb_url, img_url FROM movies";
            }
            $constraints = array();
            if(isset($request['title'])) {
                array_push($constraints, "title LIKE ?");
                array_push($args, '%'.$request['title'].'%');
            }
            if(isset($request['actor'])) {
                array_push($constraints, "id IN (SELECT movie FROM movies_actors WHERE actor IN (SELECT id FROM actors WHERE lower(name) LIKE lower(?)))");
                array_push($args, '%'.$request['actor'].'%');
            }
            if(isset($request['director'])) {
                array_push($constraints, "id IN (SELECT movie FROM movies_directors WHERE director IN (SELECT id FROM directors WHERE lower(name) LIKE lower(?)))");
                array_push($args, '%'.$request['director'].'%');
            }
            if(isset($request['year'])) {
                array_push($constraints, "year = ?");
                array_push($args, $request['year']);
            }

            if(!empty($constraints)) $query .= " WHERE " . implode(' AND ', $constraints);
            
            if(isset($request['orderByPopularity'])) {
                if($request['orderByPopularity'] === "asc") {
                    $query .= " ORDER BY users_rating ASC";
                } else {
                    $query .= " ORDER BY users_rating DESC";
                }
            } else if(isset($request['orderByYear'])) {
                if($request['orderByYear'] === "asc") {
                    $query .= " ORDER BY year ASC";
                } else {
                    $query .= " ORDER BY year DESC";
                }
            }

            if(isset($request['limit'])) {
                $query .= " LIMIT ".intval($request['limit']);
            }

            $data = $database->query($query, $args);
            if(isset($request['details'])) {
                $data = $database->addRelationsToMovieArray($data);
            }
            foreach($data as &$row) {
                unset($row['id']);
            }
            if(empty($data)) {
                return new CallResponse(404, array("error"=>"No movies found using the specified filters"));
            } else {
                return new CallResponse(200, $data);
            }
        } else {
            array_push($args, "https://www.imdb.com/title/".$request['API_VAR']."/");
            $data = $database->query("SELECT * FROM movies WHERE imdb_url=?", $args);
            $data = $database->addRelationsToMovieArray($data);
            if(empty($data)) {
                return new CallResponse(404, array("error"=>"Movie not found"));
            } else {
                return new CallResponse(200, $data);
            }
        }
    }));
    $callManager->run();
?>