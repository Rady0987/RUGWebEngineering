import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import { ACTORS } from './actors';
import { DIRECTORS } from './directors';

@Component({
  selector: 'app-actor-component',
  templateUrl: './actor-component.component.html',
  styleUrls: ['./actor-component.component.css']
})
export class ActorComponent implements OnInit {
  switchValue = false;
  request = "actors/"
  actors : ACTORS[] = [];
  directors : DIRECTORS[] = [];
  
  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
    this.request = "actors/";
    this.TaskService.actorDataRequest(this.request).subscribe(data => this.actors = data);
  }

  switch(value : boolean) {
    this.actors = [];
    this.directors = [];
    if(value) {
      this.request = "actors/";
      this.TaskService.actorDataRequest(this.request).subscribe(data => this.actors = data);
    } else {
      this.request = "directors/";
      this.TaskService.directorDataRequest(this.request).subscribe(data => this.directors = data);
    }
    this.request = "actors/"
  }

}
