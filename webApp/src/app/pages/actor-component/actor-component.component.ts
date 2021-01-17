import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';

@Component({
  selector: 'app-actor-component',
  templateUrl: './actor-component.component.html',
  styleUrls: ['./actor-component.component.css']
})
export class ActorComponent implements OnInit {
  switchValue = false;
  request = "actors/"
  
  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
   let jsonMess = this.TaskService.dataRequest(this.request);

  }

  switch(value : boolean) {
    if(value) {
      this.request = "actors/";
      let jsonMess = this.TaskService.dataRequest(this.request);
    } else {
      this.request = "directors/";
      let jsonMess = this.TaskService.dataRequest(this.request);
    }
    this.request = "actors/"
  }

}
