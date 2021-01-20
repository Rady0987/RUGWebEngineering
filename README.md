# Web Engeneering Project 2020-21 Group 49
Thanks for checking out our repository! For the Web Engeneering course we were required to create a REST API and a web application to interact with data from [an IMDB data set](https://www.kaggle.com/gorochu/complete-imdb-movies-dataset "an IMDB data set").

According to the initial project requirements, users should be provided with the following functionality: 
1. Retrieve the information about all actors in the dataset, optionally filtered by (full) name.
2. Obtain all available information about a specific movie identified by its unique
IMDB URL or by its (non-unique) title.
3. Retrieve all movies by a specific actor or director identified by name and/or in
specific year.
4. Get all movie genres for a specific actor or director, optionally sorted by year
(ascending or descending).
5. Obtain an ordering of the movies ranked by their popularity (user rating) from
more to less popular, with the possibility to subset this order, e.g. the top
50 movies.
6. Obtain an ordering of movies in a specific year ranked and subsetted by popularity
as above.
7. Get descriptive statistics (mean, median, standard deviation) for the popularity of all movies for a particular actor with an optional filter by year.

## REST API
Our REST API is implemented using a simple custom PHP script. By avoiding the usage of popular REST frameworks, we managed to design a backend implementation which source code takes up less than 17kB (without any size optimizations) on the server. The project is designed to run on a LAMP server.

### Deploying an instance of the API
Server requirements:
- Apache2
- PHP >=7.4
- MariaDB / MySQL

1. Copy the _api/_ folder to your the www root of your apache server.
2. Create a SQL database for the API, and create a user priviliged access this database.
3. Import the _dataset.sql_ file into the SQL database you've just created.
4. Update the **DATABASE**, **USERNAME** & **PASSWORD** in _api/database.php_ fields to match the information you provided to create the database and the user. If your database is not running on localhost, make sure to update the **HOST** field as well.

Your API instance is now up and running!

### Documentation
The complete documentation on the API can be found [here](api_documentation.md "here").

### Generating the dataset
If the IMDB dataset gets updated, one might want to update the API database with the new resources. We have included a script that converts the CSV dataset into an SQL file.

Requirements:
- Python 3

1. Download the [IMDB dataset](https://www.kaggle.com/gorochu/complete-imdb-movies-dataset "IMDB dataset") in CSV format as _movie.csv_ and make sure it's located in the same folder as _imdb_dataset_csv_to_sql.py_.
2. Run the conversion script:
```bash
$ python3 imdb_dataset_csv_to_sql.py
```

The script has now updated the _dataset.sql_ file, and you can import it into your SQL database. When importing the SQL file, all tables will be truncated before adding new records.
## Web Application
Our web application has been implemented using the Angular framework.

### Deploying an instance of the web application
The web application can be deployed easily. NodeJS and Angular should be present on the system in order to deploy the instance. The following instructions are written with Ubuntu 20.04 in mind, but instructions should be relatively similar for most other Linux, Mac OS or Windows based operating systems.

1. Install NodeJS and Angular
```bash
$ sudo apt-get install curl
$ curl -sL https://deb.nodesource.com/setup_12.x | sudo -E bash -
$ sudo apt-get install nodejs
$ npm install -g @angular/cli
```
2. Navigate to the _webApp/_ folder
```bash
$ cd webApp/
```
3. Install dependencies
```bash
$ npm install
```
4.  Run the application
```bash
$ ng s -o
```

The web application should now be running on `localhost:4200`

## Development Process

### Work distribution

We distributed the work per milestone as follows:

#### M1: API Design

We have designed the API together over video calls. As we continued to work on the other milestones, we altered the design. Every design iteration further adheres to the REST principles, and the requirements stated by the milestone.

#### M2: Architecture, technology selection & API implementation

Max implemented the API and made decisions on it's architecture and technology. As the API design changed, the implementation was altered.

#### M3: Web app implementation & reporting

Cainarean made the decisions on the technology to be used for the web application. The implementation itself was done by Cainarean, Radu, Denis & Horea. Most of the essential components and modules have had contributions from all of them, as they alternated eachother. Max provided knowledge about the API when needed for implementation.
