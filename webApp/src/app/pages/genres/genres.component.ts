import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-genres',
  templateUrl: './genres.component.html',
  styleUrls: ['./genres.component.css']
})
export class GenresComponent implements OnInit {
  request = "genres/";
  arr: string[] = []
  constructor(private TaskService: TaskService, private http: HttpClient) { 
  }

  ngOnInit(): void {
  }

  getName(name : string) {
    this.arr = []
    if(name != "") {
      /*check if the parameters names (actor_name, director_name) are like 
      in the endpoints doc */
      this.request += "?actor=" + name + "&director=" + name;
      let jsonMess = this.TaskService.dataRequest(this.request);
    }
    this.TaskService.dataRequest(this.request).toPromise().then(data => {
      console.log(data);

      for (let key in data) 
         if (data.hasOwnProperty(key))
           this.arr.push(data[key]);
    });
    this.request = "genres/";
  }
}
