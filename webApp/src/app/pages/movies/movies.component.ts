import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  request = "movies/";

  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
  }

  getMoviesParam(title : string, year : number, actor : string, director: string) {
    if(actor != "" || title != "" || director != "" || year != 0) {
      this.request += "?";
      this.request += `${(title != "") ? ("title=" + title) : ""}`;
      this.request = this.andOperator(this.request, actor);
      this.request += `${(actor != "") ? ("actor=" + actor) : ""}`;
      this.request = this.andOperator(this.request, director);
      this.request += `${(director != "") ? ("director=" + director) : ""}`;
      this.request = this.andOperator(this.request, year);
      this.request += `${(year != 0) ? ("year=" + year) : ""}`;
    }
    let jsonMess = this.TaskService.dataRequest(this.request);
    this.request = "movies/";
  }

  andOperator(request : string, param : any) {
    if(request != "movies/?" && (param != "" || param != 0))
      request += "&";
    return request;
  }
  
}
