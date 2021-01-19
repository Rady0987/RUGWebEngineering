import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import {HttpClient} from '@angular/common/http';
import {MOVIE} from './movie';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  request = "movies/?details&limit=50";
  items : MOVIE[] = [];

  constructor(private TaskService: TaskService, private http : HttpClient) { 
  }

  ngOnInit(): void {
  }

  getMoviesParam(title : string, year : string, actor : string, director: string) {
    this.request = "movies/?details&limit=50";
    this.items = [];
    if(actor != "" || title != "" || director != "" || year != "") {
      this.request += `${(title != "") ? ("&title=" + title) : ""}`;
      this.request = this.andOperator(this.request, actor);
      this.request += `${(actor != "") ? ("actor=" + actor) : ""}`;
      this.request = this.andOperator(this.request, director);
      this.request += `${(director != "") ? ("director=" + director) : ""}`;
      this.request = this.andOperator(this.request, year);
      this.request += `${(year != "") ? ("year=" + year) : ""}`;
    }
    console.log(this.request);
    this.TaskService.movieDataRequest(this.request).subscribe(data => this.items = data);
  }

  andOperator(request : string, param : any) {
    if(param != "" || param != 0)
      request += "&";
    return request;
  }
  
}
