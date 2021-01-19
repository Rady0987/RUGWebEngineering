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
  request = "genres/";
  arr: GENRES[] = []
  
  constructor(private TaskService: TaskService, private http: HttpClient) { 
  }

  ngOnInit(): void {
  }
  switch(switchValue : boolean) {
    this.arr = [];
    if(switchValue) {
      this.request = "genres/?actor=";
      this.TaskService.genreDataRequest(this.request).subscribe(data => this.arr = data);
    } else {
      this.request = "directors/?director=";
      this.TaskService.genreDataRequest(this.request).subscribe(data => this.arr = data);
    }
    this.request = "genres/"
  }
  
  getName(name : string) {
    this.arr = []
    this.request = "genres/"
    if(name != "") {
      /*check if the parameters names (actor_name, director_name) are like 
      in the endpoints doc*/
      this.request += "?director="+name;
    }
    this.TaskService.genreDataRequest(this.request).subscribe(data => this.arr= data);
    console.log(this.request);
    console.log(this.arr);
    
  }

  
  
}
