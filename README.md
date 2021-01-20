# Web Engeneering Project 2020-21 Group 49
Thanks for checking out our repository! For the Web Engeneering course we were required to create a REST API and a web application to interact with data from [an IMDB data set](https://www.kaggle.com/gorochu/complete-imdb-movies-dataset "an IMDB data set").

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
