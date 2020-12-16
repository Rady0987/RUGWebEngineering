<?php
    ini_set('display_errors', 'on');
    ini_set('memory_limit','512M');
    require("database.php");
    require("apicallprocessor.php");

    $database = new Database();
    $callManager = new CallManager($_SERVER, $_REQUEST);

    $callManager->addCallProcessor(new CallProcessor("GET", "genres", function($request) {
        global $database;
        $query = new Query();
        $query->setBaseQuery("SELECT name FROM genres");
        if(isset($request['actor'])) {
            $query->addConstraint("id IN (SELECT genre FROM movies_genres WHERE movie IN (SELECT movie FROM movies_actors WHERE actor IN (SELECT id FROM actors WHERE lower(name) = lower(?))))");
            $query->addArgument($request['actor']);
        }

        $data = $database->execute($query);

        if(empty($data)) {
            return new CallResponse(404, array("error"=>"No genres found using the specified filters"));
        } else {
            return new CallResponse(200, $data);
        }
    }));
    $callManager->addCallProcessor(new CallProcessor("GET", "actors", function($request) {
        global $database;
        $query = new Query();
        $query->setBaseQuery("SELECT name FROM actors");
        if(isset($request['name'])) {
            $query->addConstraint("lower(name) LIKE lower(?)");
            $query->addArgument('%'.$request['name'].'%');
        }
        $data = $database->execute($query);
        if(empty($data)) {
            return new CallResponse(404, array("error"=>"No actors found using the specified filters"));
        } else {
            return new CallResponse(200, $data);
        }
    }));
    $callManager->addCallProcessor(new CallProcessor("GET", "movies", function($request) {
        global $database;
        $query = new Query();
        if($request['API_VAR'] === "") {
            if(isset($request['details'])) {
                $query->setBaseQuery("SELECT title, rating, year, users_rating, votes, metascore, img_url, tagline, description, runtime, imdb_url, (SELECT GROUP_CONCAT(a.name SEPARATOR ', ') FROM movies_actors ma JOIN actors a ON a.id = ma.actor WHERE ma.movie = m.id ) AS actors, (SELECT GROUP_CONCAT(d.name SEPARATOR ', ') FROM movies_directors md JOIN directors d ON d.id = md.director WHERE md.movie = m.id ) AS directors, (SELECT GROUP_CONCAT(g.name SEPARATOR ', ') FROM movies_genres mg JOIN genres g ON g.id = mg.genre WHERE mg.movie = m.id ) AS genres, (SELECT GROUP_CONCAT(c.name SEPARATOR ', ') FROM movies_countries mc JOIN countries c ON c.id = mc.country WHERE mc.movie = m.id) AS countries, (SELECT GROUP_CONCAT(l.name SEPARATOR ', ') FROM movies_languages ml JOIN languages l ON l.id = ml.language WHERE ml.movie = m.id) AS languages FROM movies m");
            } else {
                $query->setBaseQuery("SELECT title, imdb_url, img_url FROM movies");
            }
            if(isset($request['title'])) {
                $query->addConstraint("title LIKE ?");
                $query->addArgument('%'.$request['title'].'%');
            }
            if(isset($request['actor'])) {
                $query->addConstraint("id IN (SELECT movie FROM movies_actors WHERE actor IN (SELECT id FROM actors WHERE lower(name) LIKE lower(?)))");
                $query->addArgument('%'.$request['actor'].'%');
            }
            if(isset($request['director'])) {
                $query->addConstraint("id IN (SELECT movie FROM movies_directors WHERE director IN (SELECT id FROM directors WHERE lower(name) LIKE lower(?)))");
                $query->addArgument('%'.$request['director'].'%');
            }
            if(isset($request['year'])) {
                $query->addConstraint("year = ?");
                $query->addArgument($request['year']);
            }

            if(isset($request['orderByPopularity'])) {
                $query->setOrder("users_rating", $request['orderByPopularity'] === "asc");
            } else if(isset($request['orderByYear'])) {
                $query->setOrder("year", $request['orderByYear'] === "asc");
            }

            if(isset($request['limit'])) {
                $query->setLimit(intval($request['limit']));
            }

            $data = $database->execute($query);

            if(empty($data)) {
                return new CallResponse(404, array("error"=>"No movies found using the specified filters"));
            } else {
                return new CallResponse(200, $data);
            }
        } else {
            $query->setBaseQuery("SELECT title, rating, year, users_rating, votes, metascore, img_url, tagline, description, runtime, imdb_url, (SELECT GROUP_CONCAT(a.name SEPARATOR ', ') FROM movies_actors ma JOIN actors a ON a.id = ma.actor WHERE ma.movie = m.id ) AS actors, (SELECT GROUP_CONCAT(d.name SEPARATOR ', ') FROM movies_directors md JOIN directors d ON d.id = md.director WHERE md.movie = m.id ) AS directors, (SELECT GROUP_CONCAT(g.name SEPARATOR ', ') FROM movies_genres mg JOIN genres g ON g.id = mg.genre WHERE mg.movie = m.id ) AS genres, (SELECT GROUP_CONCAT(c.name SEPARATOR ', ') FROM movies_countries mc JOIN countries c ON c.id = mc.country WHERE mc.movie = m.id) AS countries, (SELECT GROUP_CONCAT(l.name SEPARATOR ', ') FROM movies_languages ml JOIN languages l ON l.id = ml.language WHERE ml.movie = m.id) AS languages FROM movies m");
            $query->addConstraint("imdb_url = ?");
            $query->addArgument("https://www.imdb.com/title/".$request['API_VAR']."/");
            
            $data = $database->execute($query);
            
            if(empty($data)) {
                return new CallResponse(404, array("error"=>"Movie not found"));
            } else {
                return new CallResponse(200, $data);
            }
        }
    }));
    $callManager->addCallProcessor(new CallProcessor("POST", "actors", function($request) {
        global $database;
        $auth = false;
        if(isset($request['api_key'])) {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM api_keys");
            $query->addConstraint("api_key = ?");
            $query->addArgument($request['api_key']);
            $data = $database->execute($query);
            if(!empty($data)) {
                $auth = true;
            }
        }
        if(!$auth) {
            return new CallResponse(401, array("error"=>"Access denied"));
        }

        if(isset($request['name'])) {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM actors");
            $query->addConstraint("lower(name) = lower(?)");
            $query->addArgument($request['name']);
            $data = $database->execute($query);
            if($request['name'] === ""){
                return new CallResponse(400, array("error"=>"'name' value cannot be empty"));
            } else if(!empty($data)) {
                return new CallResponse(400, array("error"=>"An actor with this name already exists"));
            }
        } else {
            return new CallResponse(400, array("error"=>"No 'name' value provided"));
        }
        $query = new Query();
        $query->setBaseQuery("INSERT INTO actors (`name`) VALUES (?)");
        $query->addArgument($request['name']);
        $data = $database->execute($query);
        return new CallResponse(201, array("success"=>"Actor successfully created"));
    }));
    $callManager->addCallProcessor(new CallProcessor("DELETE", "actors", function($request) {
        global $database;
        $auth = false;
        if(isset($request['api_key'])) {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM api_keys");
            $query->addConstraint("api_key = ?");
            $query->addArgument($request['api_key']);
            $data = $database->execute($query);
            if(!empty($data)) {
                $auth = true;
            }
        }
        if(!$auth) {
            return new CallResponse(401, array("error"=>"Access denied"));
        }

        if(isset($request['name'])) {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM actors");
            $query->addConstraint("lower(name) = lower(?)");
            $query->addArgument($request['name']);
            $data = $database->execute($query);
            if(empty($data)) {
                return new CallResponse(400, array("error"=>"No actor with the given name was found"));
            } else {
                $actorId = $data[0]['id'];
            }
        } else {
            return new CallResponse(400, array("error"=>"No 'name' value provided"));
        }
        $query = new Query();
        $query->setBaseQuery("DELETE FROM actors");
        $query->addConstraint("lower(name) = lower(?)");
        $query->addArgument($request['name']);
        $data = $database->execute($query);

        $query = new Query();
        $query->setBaseQuery("DELETE FROM movies_actors");
        $query->addConstraint("actor = ?");
        $query->addArgument($actorId);
        $data = $database->execute($query);

        return new CallResponse(200, array("success"=>"Actor successfully deleted"));
    }));
    $callManager->addCallProcessor(new CallProcessor("PUT", "actors", function($request) {
        global $database;
        $auth = false;
        if(isset($request['api_key'])) {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM api_keys");
            $query->addConstraint("api_key = ?");
            $query->addArgument($request['api_key']);
            $data = $database->execute($query);
            if(!empty($data)) {
                $auth = true;
            }
        }
        if(!$auth) {
            return new CallResponse(401, array("error"=>"Access denied"));
        }

        if(isset($request['name'])) {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM actors");
            $query->addConstraint("lower(name) = lower(?)");
            $query->addArgument($request['name']);
            $data = $database->execute($query);
            if(empty($data)) {
                return new CallResponse(400, array("error"=>"No actor with the given name was found"));
            }
        } else {
            return new CallResponse(400, array("error"=>"No 'name' value provided"));
        }
        if(isset($request['newName'])) {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM actors");
            $query->addConstraint("lower(name) = lower(?)");
            $query->addArgument($request['newName']);
            $data = $database->execute($query);
            if(!empty($data)) {
                return new CallResponse(400, array("error"=>"There is already another actor with this new name"));
            }
        } else {
            return new CallResponse(400, array("error"=>"No 'newName' value provided"));
        }

        $query = new Query();
        $query->setBaseQuery("UPDATE actors SET name = ?");
        $query->addArgument($request['newName']);
        $query->addConstraint("lower(name) = lower(?)");
        $query->addArgument($request['name']);
        $data = $database->execute($query);
        return new CallResponse(200, array("success"=>"Actor updated deleted"));
    }));

    $callManager->addCallProcessor(new CallProcessor("GET", "actorstatistics", function($request) {
        global $database;
        if($request['API_VAR'] === "") {
            return new CallResponse(400, array("error"=>"No actor specified"));
        } else {
            $query = new Query();
            $query->setBaseQuery("SELECT id FROM actors");
            $query->addConstraint("lower(name) = (?)");
            $query->addArgument($request['API_VAR']);
            $data = $database->execute($query);
            if(empty($data)) {
                return new CallResponse(404, array("error"=>"Actor not found"));
            }
            $actorId = $data[0]['id'];

            $statistics = array();

            $query = new Query();
            $query->setBaseQuery("SELECT AVG(users_rating) AS mean FROM movies");
            $query->addConstraint("id IN (SELECT movie FROM movies_actors WHERE actor = ?)");
            $query->addArgument($actorId);
            $query->setOrder("users_rating", true);
            $data = $database->execute($query);
            $statistics["mean_popularity"] = $data[0]['mean'];

            $query = new Query();
            $query->setBaseQuery("SELECT AVG(dd.users_rating) as median FROM (SELECT d.users_rating, @rownum:=@rownum+1 as `row_number`, @total_rows:=@rownum FROM movies d, (SELECT @rownum:=0) r WHERE d.id IN (SELECT movie FROM movies_actors WHERE actor = ?) ORDER BY d.users_rating ) as dd WHERE dd.row_number IN ( FLOOR((@total_rows+1)/2), FLOOR((@total_rows+2)/2) )");
            $query->addArgument($actorId);
            $data = $database->execute($query);
            $statistics["median_popularity"] = $data[0]['median'];

            $query = new Query();
            $query->setBaseQuery("SELECT STDDEV(users_rating) AS mean FROM movies");
            $query->addConstraint("id IN (SELECT movie FROM movies_actors WHERE actor = ?)");
            $query->addArgument($actorId);
            $query->setOrder("users_rating", true);
            $data = $database->execute($query);
            $statistics["standard_deviation_popularity"] = $data[0]['mean'];

            return new CallResponse(200, $statistics);
        }
    }));
    $callManager->run();
?>