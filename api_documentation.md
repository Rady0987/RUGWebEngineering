# REST API for IMDB dataset
A simple REST api to interact with resources from a IMDB data set from kaggle.

## Version: 1.0.0

### /movies

#### GET
##### Summary:

Retrieve movies

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| title | query | The title of the movie | No | string |
| actor | query | The name of an actor in the movie | No | string |
| director | query | The name of a director in the movie | No | string |
| year | query | The release year of the movie | No | string |
| orderByPopularity | query | When this parameter is passed, the API will order the results by the popularity of the movies | No | string |
| limit | query | When this parameter is passed, the API will limit the amount of results | No | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 204 | No movies found |

### /movie/{movie_id}

#### GET
##### Summary:

Retrieve information on a specific movie

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| movie_id | path | The imdb URL ID of the movie | Yes | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 404 | Movie not found |

### /actors

#### GET
##### Summary:

Retrieve all actors

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| name | query | The actor's name | No | string |
| limit | query | Limit results | No | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 204 | No directors found |

### /actor

#### POST
##### Summary:

Create a new actor

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| name | query | The name of the actor to create | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 201 | Actor has been created successfully |

### /actor/{actor_id}

#### GET
##### Summary:

Retrieve actor

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| actor_id | path |  | Yes | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 404 | No actors found |

#### PUT
##### Summary:

Update the name of an actor

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| actor_id | path |  | Yes | integer |
| name | query | New name | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Actor has been updated successfully |
| 400 | Malformed request |

#### DELETE
##### Summary:

Delete an actor

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| actor_id | path |  | Yes | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Actor has been deleted successfully |
| 404 | Actor not found |

### /actor/{actor_id}/genres

#### GET
##### Summary:

Retrieve genres

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| actor_id | path | The name of an actor | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 204 | No genres found |

### /directors

#### GET
##### Summary:

Retrieve directors

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| name | query | The name of the director | No | string |
| limit | query | Limit results | No | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 204 | No directors found |

### /director/{director_id}

#### GET
##### Summary:

Retrieve directors

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| director_id | path |  | Yes | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 404 | Director not found |

### /director/{director_id}/genres

#### GET
##### Summary:

Retrieve genres

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| director_id | path | The name of an actor | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 204 | No genres found |

### /actor/{actor_id}/statistics

#### GET
##### Summary:

Retrieve statistics for a specific actor

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| actor_id | path | The name of the actor | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 404 | Actor not found |

### /movie/{movie_id}/torrents

#### GET
##### Summary:

Retrieve torrents for a specific movie

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| movie_id | path | The imdb URL ID of the movie | Yes | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successful operation |
| 204 | No torrents found |
| 404 | Movie not found |

### Models


#### Torrent

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| quality | string | The quality of the torrent | No |
| url | string | The URL of the torrent file | No |
| magnet_url | string | The magnet link of the torrent | No |

#### Movie

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer | ID | No |
| title | string | Movie title | No |
| image | string | Image URL | No |
| year | integer | Release year | No |

#### MovieInfo

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| title | string | The title of the movie | No |
| rating | string | The rating of the movie | No |
| year | integer | The release year of the movie | No |
| users_rating | number | The users rating of the movie | No |
| votes | integer | The amount of votes for the movie | No |
| metascore | integer | The metascore of the movie | No |
| img_url | string | The image URL of the cover of the movie | No |
| tagline | string | The tagline of the movie | No |
| description | string | The description of the movie | No |
| runtime | integer | The of runtime the movie in minutes | No |
| imdb_url | string | The IMDB url of the movie | No |
| actors | string | The actors of the movie | No |
| directors | string | The directors of the movie | No |
| genres | string | The genres of the movie | No |
| countries | string | The countries of the movie | No |
| languages | string | The languages of the movie | No |

#### Actor

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer | ID | No |
| name | string | Name | No |

#### Director

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer | ID | No |
| name | string | Name | No |

#### ActorStatistics

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| mean_popularity | number | The mean of the popularity of the actor's movies | No |
| median_popularity | number | The median of the popularity of the actor's movies | No |
| standard_deviation_popularity | number | The standard deviation of the popularity of the actor's movies | No |

#### Genre

A movie genre

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| Genre | string | A movie genre |  |