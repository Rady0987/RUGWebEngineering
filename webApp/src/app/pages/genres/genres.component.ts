import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import {HttpClient} from '@angular/common/http';
import { GENRES } from './genres';

@Component({
  selector: 'app-genres',
  templateUrl: './genres.component.html',
  styleUrls: ['./genres.component.css']
})
export class GenresComponent implements OnInit {
  switchValue = false;
  request = "genres/?actor=";
  stringsaver =""
  arr: GENRES[] = []
  
  constructor(private TaskService: TaskService, private http: HttpClient) { 
  }

  ngOnInit(): void {
  }
  switch(switchValue : boolean) {
    this.arr = [];
    if(switchValue) {
      this.request = "genres/?actor=";
      console.log(this.request)
    } else {
      this.request = "genres/?director=";
      console.log(this.request)
    }
  }
  
  getName(name : string) {
    this.arr = []
    if(name != "") {
      /*check if the parameters names (actor_name, director_name) are like 
      in the endpoints doc*/
      this.request += name;
    }
    this.TaskService.genreDataRequest(this.request).subscribe(data => this.arr = data);
    console.log(this.request);
    console.log(this.arr);
    if(this.request.includes("director")){
      this.request = "genres/?director="
    }
    if(this.request.includes("actor")){
      this.request = "genres/?actor="
    }
  }

  
  
}
