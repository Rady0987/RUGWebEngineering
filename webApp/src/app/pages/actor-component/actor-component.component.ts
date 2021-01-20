import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import { ACTORSDIR } from './actors';

@Component({
  selector: 'app-actor-component',
  templateUrl: './actor-component.component.html',
  styleUrls: ['./actor-component.component.css']
})

export class ActorComponent implements OnInit {
  switchValue = false;
  request = "actors";
  items : ACTORSDIR[];
  limit : number;
  
  constructor(private TaskService: TaskService) {
    this.items = [];
    this.limit = 50;
   }

  ngOnInit(): void { 
    this.request = "actors?limit=" + this.limit;
    this.TaskService.dataRequest(this.request).subscribe(data => { this.items = <ACTORSDIR[]> data; console.log(data);});
    console.log(this.items);
  }

  changeSize(newSize : number) {
    this.limit = newSize;
  }

  switch(value : boolean) {
    this.items = [];
    if(value) {
      this.request = "actors?limit=" + this.limit;
      this.TaskService.dataRequest(this.request).subscribe(data => { this.items = <ACTORSDIR[]> data; console.log(data);});
    } else {
      this.request = "directors?limit=" + this.limit;
      this.TaskService.dataRequest(this.request).subscribe(data => { this.items = <ACTORSDIR[]> data; console.log(data);});
    }
    this.request = "actors?limit=" + this.limit;
  }

}
