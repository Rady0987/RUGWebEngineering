import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import {HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-genres',
  templateUrl: './genres.component.html',
  styleUrls: ['./genres.component.css']
})
export class GenresComponent implements OnInit {
  switchValue = false;
  request = "genres/";
  choice = "";
  arr: string[] = [];
  constructor(private TaskService: TaskService, private http: HttpClient) { 
  }

  ngOnInit(): void {
  }

  getName(name : string) {
    this.arr = []
    if(name != "") 
      this.request += `${(this.switchValue) ? ("?director=" + name) : ("?actor=" + name)}`;
    this.TaskService.dataRequest(this.request).toPromise().then(data => {
      console.log(data);
      for (let key in data) 
         if (data.hasOwnProperty(key))
           this.arr.push(data[key]);
    });
    this.request = "genres/";
  }
}
