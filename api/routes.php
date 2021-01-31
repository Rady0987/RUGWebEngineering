<?php
	//ini_set('display_errors', 'on');
	ini_set('memory_limit','512M');
	require("database.php");
	require("api.php");
	$database = new Database();
	$api = new API($_SERVER, $_REQUEST);
	
	//MOVIE ENDPOINTS
	
	# GET /movies
	$api->addAPIRoute(new APIRoute("GET", "/^movies$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id, title, img_url, year FROM movies");

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
			return new APIResponse(204, $data);
		} else {
			return new APIResponse(200, $data);
		}
	}));
	# GET /movie/{movie_id}
	$api->addAPIRoute(new APIRoute("GET", "/^movie\/\d+$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT title, rating, year, users_rating, votes, metascore, img_url, tagline, description, runtime, imdb_url, (SELECT GROUP_CONCAT(a.name SEPARATOR ', ') FROM movies_actors ma JOIN actors a ON a.id = ma.actor WHERE ma.movie = m.id ) AS actors, (SELECT GROUP_CONCAT(d.name SEPARATOR ', ') FROM movies_directors md JOIN directors d ON d.id = md.director WHERE md.movie = m.id ) AS directors, (SELECT GROUP_CONCAT(g.name SEPARATOR ', ') FROM movies_genres mg JOIN genres g ON g.id = mg.genre WHERE mg.movie = m.id ) AS genres, (SELECT GROUP_CONCAT(c.name SEPARATOR ', ') FROM movies_countries mc JOIN countries c ON c.id = mc.country WHERE mc.movie = m.id) AS countries, (SELECT GROUP_CONCAT(l.name SEPARATOR ', ') FROM movies_languages ml JOIN languages l ON l.id = ml.language WHERE ml.movie = m.id) AS languages FROM movies m");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		} else {
			return new APIResponse(200, $data[0]);
		}
	}));
	# GET /movie/{movie_id}/torrents
	$api->addAPIRoute(new APIRoute("GET", "/^movie\/\d+\/torrents$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT title FROM movies");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		}
		$yts_api_call = json_decode(file_get_contents("https://yts.mx/api/v2/list_movies.json?query_term=" . urlencode($data[0]['title'])), true);
		if($yts_api_call['data']['movie_count'] > 0) {
			$result = array();
			foreach($yts_api_call['data']['movies'][0]['torrents'] as $torrent) {
				$row = array();
				$row['quality'] = $torrent['quality'];
				$row['url'] = $torrent['url'];
				$row['magnet_url'] = "magnet:?xt=urn:btih:" . $torrent['hash'] . "&dn=" . urlencode($data[0]['title']);
				$trackers = array("http://track.one:1234/announce", "udp://track.two:80", "udp://open.demonii.com:1337/announce", "udp://tracker.openbittorrent.com:80", "udp://tracker.coppersurfer.tk:6969", "udp://glotorrents.pw:6969/announce", "udp://tracker.opentrackr.org:1337/announce", "udp://torrent.gresille.org:80/announce", "udp://p4p.arenabg.com:1337", "udp://tracker.leechers-paradise.org:6969");
				foreach($trackers as $tracker) {
					$row['magnet_url'] .= "&tr=$tracker";
				}
				array_push($result, $row);
			}
			return new APIResponse(200, $result);
		} else return new APIResponse(204, array());
	}));
	
	//ACTOR ENDPOINTS
	
	# GET /actors
	$api->addAPIRoute(new APIRoute("GET", "/^actors$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id, name FROM actors");
		
		if(isset($request['name'])) {
			$query->addConstraint("lower(name) LIKE lower(?)");
			$query->addArgument('%'.$request['name'].'%');
		}
		
		if(isset($request['limit'])) {
			$query->setLimit(intval($request['limit']));
		}
		
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(204, array());
		} else {
			return new APIResponse(200, $data);
		}
	}));
	# POST /actor
	$api->addAPIRoute(new APIRoute("POST", "/^actor$/", function($request) {
		global $database;
		if(!isset($request['name'])) {
			return new APIResponse(400, array());
		} else {
			$query = new Query();
			$query->setBaseQuery("INSERT INTO actors (`name`) VALUES (?)");
			$query->addArgument($request['name']);
			$data = $database->execute($query);
			header("Location: /actor/" . $database->lastInsertId());
			return new APIResponse(201, array());
		}
	}));
	# GET /actor/{actor_id}
	$api->addAPIRoute(new APIRoute("GET", "/^actor\/\d+$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id, name FROM actors");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		} else {
			return new APIResponse(200, $data[0]);
		}
	}));
	# PUT /actor/{actor_id}
	$api->addAPIRoute(new APIRoute("PUT", "/^actor\/\d+$/", function($request) {
		global $database;
		if(!isset($request['name'])) {
			return new APIResponse(400, array());
		} else {
			$query = new Query();
			$query->setBaseQuery("SELECT id FROM actors");
			$query->addConstraint("id = ?");
			$query->addArgument(explode('/', $request['route'])[1]);
			$data = $database->execute($query);
			if(empty($data)) {
				return new APIResponse(404, array());
			}
			$query = new Query();
			$query->setBaseQuery("UPDATE actors SET name = ?");
			$query->addArgument($request['name']);
			$query->addConstraint("id = ?");
			$query->addArgument(explode('/', $request['route'])[1]);
			$data = $database->execute($query);
			return new APIResponse(204, array());
		}
	}));
	# DELETE /actor/{actor_id}
	$api->addAPIRoute(new APIRoute("DELETE", "/^actor\/\d+$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id FROM actors");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		}
		$query = new Query();
		$query->setBaseQuery("DELETE FROM actors");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		return new APIResponse(204, array());
	}));
	# GET /actor/{actor_id}/genres
	$api->addAPIRoute(new APIRoute("GET", "/^actor\/\d+\/genres$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id FROM actors");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		}
		$query = new Query();
		$query->setBaseQuery("SELECT name FROM genres WHERE id IN (SELECT genre FROM movies_genres WHERE movie IN (SELECT movie FROM movies_actors WHERE actor = ?))");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		return new APIResponse(200, $data);
	}));
	# GET /actor/{actor_id}/statistics
	$api->addAPIRoute(new APIRoute("GET", "/^actor\/\d+\/statistics$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id FROM actors");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		}
		$statistics = array();
		$actorId = explode('/', $request['route'])[1];
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

		return new APIResponse(200, $statistics);
	}));

	//DIRECTOR ENDPOINTS
	
	# GET /directors
	$api->addAPIRoute(new APIRoute("GET", "/^directors$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id, name FROM directors");
		
		if(isset($request['name'])) {
			$query->addConstraint("lower(name) LIKE lower(?)");
			$query->addArgument('%'.$request['name'].'%');
		}
		
		if(isset($request['limit'])) {
			$query->setLimit(intval($request['limit']));
		}
		
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(204, array());
		} else {
			return new APIResponse(200, $data);
		}
	}));
	# GET /director/{director_id}
	$api->addAPIRoute(new APIRoute("GET", "/^director\/\d+$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id, name FROM directors");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		} else {
			return new APIResponse(200, $data[0]);
		}
	}));
	# GET /director/{director_id}/genres
	$api->addAPIRoute(new APIRoute("GET", "/^director\/\d+\/genres$/", function($request) {
		global $database;
		$query = new Query();
		$query->setBaseQuery("SELECT id FROM directors");
		$query->addConstraint("id = ?");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		if(empty($data)) {
			return new APIResponse(404, array());
		}
		$query = new Query();
		$query->setBaseQuery("SELECT name FROM genres WHERE id IN (SELECT genre FROM movies_genres WHERE movie IN (SELECT movie FROM movies_directors WHERE director = ?))");
		$query->addArgument(explode('/', $request['route'])[1]);
		$data = $database->execute($query);
		return new APIResponse(200, $data);
	}));
	
	$api->run();
?>
