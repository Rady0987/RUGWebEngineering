import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import { MOVIE } from './movie';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  request = "movies/?limit=50";
  items : MOVIE[];
  limit : number;
  loading : boolean;
  sortByPopularity : boolean;

  constructor(private TaskService: TaskService) { 
    this.items = [];
    this.limit = 10;
    this.loading = true;
    this.sortByPopularity = false;
  }

  ngOnInit(): void {
    this.getMoviesParam("", "", "", "");
  }

  changeSize(newSize : number) {
    this.limit = newSize;
  }

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
      this.request += `${(actorName != "") ? ("Actor=" + actorName) : ""}`;
      this.request = this.andOperator(this.request, directorName);
      this.request += `${(directorName != "") ? ("director=" + directorName) : ""}`;
      
      if (this.sortByPopularity == true) {
        this.request = this.andOperator(this.request, this.sortByPopularity);
        this.request += "orderByPopularity=asc"; 
      }
    }
    console.log(this.request);
    console.log(this.TaskService.dataRequest(this.request).subscribe(data => {if(data == null) {this.items = []; this.loading = false} else {this.items = <MOVIE[]> data}}));
  }

  andOperator(request : string, param : any) {
    if((param != "" || param != 0) && (request.substr(request.length - 1) != "?"))
      request += "&";
    return request;
  }

  sortYear = (a: MOVIE, b: MOVIE) => a.year - b.year;
  sortTitle = (a: MOVIE, b: MOVIE) => a.title.localeCompare(b.title);
}
