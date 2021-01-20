import { Component, OnInit } from '@angular/core';
import { NzDrawerPlacement } from 'ng-zorro-antd/drawer';
import { TaskService } from 'src/app/task.service';
import { MOVIE } from './movie';
import { moviev2 } from './moviev2';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  request = "movies/?limit=50";
  items : MOVIE[];
  movie : moviev2;
  limit : number;
  loading : boolean;
  sortByPopularity : boolean;
  visible = false;
  placement: NzDrawerPlacement = 'top';
  
  constructor(private TaskService: TaskService) { 
    this.items = [];
    this.limit = 10;
    this.loading = true;
    this.sortByPopularity = false;
    this.movie = {} as moviev2;
  }

  ngOnInit(): void {
    this.getMoviesParam("", "", "", "");
  }

  // Method used to change the number of movies querried by GET request.
  changeSize(newSize : number) {
    this.limit = newSize;
  }

  // Method used to GET request the movies, with some querry parameters (as title, year, actor name, director name), and parse the JSON response into array.
  getMoviesParam(title : string, year : string, actorName : string, directorName : string) {
    this.loading = true;
    this.items = [];

    if (this.limit != 0) {
      this.request = "movies?limit=" + this.limit;
    } else {
      this.request = "movies"
    }

    if(title != "" || year != "" || actorName != "" || directorName != "" || this.sortByPopularity == true) {

      if (this.limit != 0) {
        this.request = "movies?limit=" + this.limit;
      } else {
        this.request = "movies?"
      }

      this.request += `${(title != "") ? ("&title=" + title) : ""}`;
      this.request = this.andOperator(this.request, year);
      this.request += `${(year != "") ? ("year=" + year) : ""}`;
      this.request = this.andOperator(this.request, actorName);
      this.request += `${(actorName != "") ? ("actor=" + actorName) : ""}`;
      this.request = this.andOperator(this.request, directorName);
      this.request += `${(directorName != "") ? ("director=" + directorName) : ""}`;
      
      if (this.sortByPopularity == true) {
        this.request = this.andOperator(this.request, this.sortByPopularity);
        this.request += "orderByPopularity=asc"; 
      }
    }
    console.log(this.TaskService.dataRequest(this.request).subscribe(data => {if(data == null) {this.items = []; this.loading = false} else {this.items = <MOVIE[]> data}}));
  }

  // Method to add the ampersand in the URI where it is needed.
  andOperator(request : string, param : any) {
    if((param != "" || param != 0) && (request.substr(request.length - 1) != "?"))
      request += "&";
    return request;
  }

  // Method to open the upper panel when clicked on a movie.
  open(movieID : string): void {
    this.request = "movie/"+movieID;
    console.log(this.TaskService.dataRequest(this.request).subscribe(data => this.movie = <moviev2> data));
    this.visible = true;
  }

  // Method to close the panel.
  close(): void {
    this.visible = false;
  }

  // Methods to sort the data
  sortYear = (a: MOVIE, b: MOVIE) => a.year - b.year;
  sortTitle = (a: MOVIE, b: MOVIE) => a.title.localeCompare(b.title);
}
