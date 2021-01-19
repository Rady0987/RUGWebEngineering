import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import {MOVIE} from './movie';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  request = "movies/?limit=50";
  items : MOVIE[];
  constructor(private TaskService: TaskService) { 
    this.items = [];
  }

  ngOnInit(): void {
    this.getMoviesParam("", "");
  }

  getMoviesParam(title : string, year : string) {
    this.items = [];
    this.request = "movies/?limit=50";
    if(title != "" || year != "") {
      this.request += `${(title != "") ? ("&title=" + title) : ""}`;
      this.request = this.andOperator(this.request, year);
      this.request += `${(year != "") ? ("year=" + year) : ""}`;
    }
    console.log(this.request);
    console.log(this.TaskService.dataRequest(this.request).subscribe(data => this.items = <MOVIE[]> data));
  }

  andOperator(request : string, param : any) {
    if(param != "" || param != 0)
      request += "&";
    return request;
  }
  
}
