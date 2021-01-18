import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  request = "movies/?details=&limit=5";
  items : any[] = [];

  constructor(private TaskService: TaskService, private http : HttpClient) { 
  }

  ngOnInit(): void {
  }

  getMoviesParam(title : string, year : any, actor : string, director: string) {
    if(actor != "" || title != "" || director != "" || year != 0) {
      //this.request += "?";
      this.request += `${(title != "") ? ("&title=" + title) : ""}`;
      this.request = this.andOperator(this.request, actor);
      this.request += `${(actor != "") ? ("actor=" + actor) : ""}`;
      this.request = this.andOperator(this.request, director);
      this.request += `${(director != "") ? ("director=" + director) : ""}`;
      this.request = this.andOperator(this.request, year);
      this.request += `${(year != 0) ? ("year=" + year) : ""}`;
    }
    this.TaskService.dataRequest(this.request).toPromise().then(data => {
      console.log(data);

      for (let key in data) 
         if (data.hasOwnProperty(key))
           this.items.push(data[key]);
    });
    this.request = "movies/?details=&limit=5";
  }

  andOperator(request : string, param : any) {
    if(request != "movies/?details=&limit=5" && (param != "" || param != 0))
      request += "&";
    return request;
  }
  
}
