import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';

@Component({
  selector: 'app-statistic',
  templateUrl: './statistic.component.html',
  styleUrls: ['./statistic.component.css']
})
export class StatisticComponent implements OnInit {
  request = "actorstatistics/"

  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
  }

  getName(name : string) {
    //API call get/api/actorstatistics/ has only actor parameter (no director)
    this.request += "?actor_name=" + name;
    let jsonMess = this.TaskService.dataRequest(this.request);
    this.request = "actorstatistics/";
  }
}
