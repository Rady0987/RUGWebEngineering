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

### Technology & Architecture decisions

For the FrontEnd we chose Angular framework, TypeScript (which is basically the same as JS), html and css. We chose this pack of tools: TypeScript because it was really easy to learn and it is really fast, Angular was chosen because it is a very big library with a lot of pre-built tools that we could use. Also, Angular is famous four it's great documentation and fast development of the product.
Architecture of our program:
Angular framework works like a ship, it can place containers onto eachother, remove them and also if a contrainer fails to load, it won't affect other pages.
Also angular has a great built-in HTTP Client, which made the connection with the BackEnd really simple. We communicate to our server through http request and get JSON data, we parse that data and dispay it on the screen.
#### REST API
The technology that was chosen for the implementation of the REST API was the LAMP stack. PHP was used to implement the application logic, Apache's _.htaccess_ file was used to obtain REST-like endpoints to identify the resources, and a MySQL server was used to hold the resources. Python was used to convert the data from CSV to SQL.

The initial motivation to choose PHP to implement the API was because the backend developer, Max, was already familiar with it. He also had access to a LAMP webserver, so that was quite useful. The decision to implement the application logic without use of a well-established framework, like Laravel, was made because Max found that this was overcomplicated for this project. Looking back, this might have not been the best decision when taking things like scalability and modularity in mind. This decision also took away the oppertunity to get familiar with the professional frameworks. This aside, implementing the backend was a lot of fun and the end result is both functional, and pretty adhering to REST principles. The lack of complicated frameworks did remove things like compilation, which decreased the time between writing code and testing it, so that was a plus. It is also very easy to quickly add new endpoints and deploy them, as long as the current model of the application allows it.

The architecture of the API is very simple. It contains of 4 files. The _.database_ file implements basic database functions that are used by the application to interact with the stored resources. This is also where the database credentials reside. The _.api.php_ is home to 3 classes. The **API** class is used to decide which route should handle the request and makes sure the correct response is returned to the client. The **APIRoute** class takes care of the processing of calls to a specific route. All route/request combinations have their own **APIRoute** object which takes care of the logic. The **APIResponse** class is used as a response which can be returned to the client. It holds the HTTP status code and the resource to be returned. The **API** class takes care of providing the correct resource representation, by server-driven content negotiation. The _routes.php_ file is where the actual API is implemented using the classes discussed above. Lastly, the _.htaccess_ file routes requests to the _routes.php_ file, so our API can have REST-like endpoints for it's resources.

#### Web Application
Speaking about the front-end part, we wanted to create an intuitive, user-friendly interface that will perform well with the backend components and will provide the desired requirements of the project. For this reason, the web application was developed using a well-known TypeScript framework, Angular. 

Firstly, we thought of using the basic web developing languages, like HTML, CSS and JavaScript, since all of us were familiar with them. However, we decided that it will be better and easier for us to find a Framework that will fulfill our needs, this is how we found about Angular. It's diverse documentation/plentiful of tutorials online made it possible for us to learn it in a couple of days. Angular is like the skeleton of our web application and using TypeScript, CSS and HTML we added some content on it.


### Work distribution

#### M1: API Design
We have designed the API together over video calls. As we continued to work on the other milestones, we altered the design. Every design iteration further adheres to the REST principles, and the requirements stated by the milestone.

#### M2: Architecture, technology selection & API implementation

Max implemented the API and made decisions on it's architecture and technology. As the API design changed, the implementation was altered.

#### M3: Web app implementation & reporting

Constantin made the decisions on the technology to be used for the web application. The implementation itself was done by Constantin, Radu, Denis & Horea.
Constantin did the whole Movies Pages(Front), helped with the report and with the documentation.
Radu did the connection with the backend with the frontend, the sorting in the Movies and Actors and Directors Pages, helped with the report and with the documentation. 
Denis created the Actors and Directors FrontEnd implementation, displaying the data, helped with parsing on Movies page, helped with the report and with the documentation.
Horea Bochis helped with Actors and Directors FrontEnd implementation, displaying the data, helped with the report and with the documentation.
Most of the essential components and modules have had contributions from all of them, as they alternated each other. Max provided knowledge about the API when needed for implementation.
