import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  request = "movies/";
  test = "http://movies.max.ug/api/movies/?year=2020";
  test2 = "https://jsonplaceholder.typicode.com/todos/1"
  items : any[] = [];

  constructor(private TaskService: TaskService, private http : HttpClient) { 
    this.http.get(this.test).toPromise().then(data => {
      console.log(data);

      for (let key in data) 
        if (data.hasOwnProperty(key))
          this.items.push(data[key]);
    });
  }

  ngOnInit(): void {
  }

  getMoviesParam(title : string, year : any, actor : string, director: string) {
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
