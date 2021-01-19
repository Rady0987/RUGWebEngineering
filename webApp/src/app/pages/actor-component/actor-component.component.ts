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
  request = "actors/"
  items : ACTORSDIR[];
  
  constructor(private TaskService: TaskService) {
    this.items = [];
   }

  ngOnInit(): void {
    this.request = "actors/";
    this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
  }

  switch(value : boolean) {
    this.items = [];
    if(value) {
      this.request = "actors/";
      this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
    } else {
      this.request = "directors/";
      this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
    }
    this.request = "actors/"
  }

}
