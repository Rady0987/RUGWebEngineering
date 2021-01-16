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
    if(name != "") {
        /*check if the parameters names (actor_name, director_name) are like 
        in the endpoints doc */
      this.request += "?actor_name=" + name + "&director_name=" + name;
      let jsonMess = this.TaskService.dataRequest(this.request);
    }
    this.request = "actorstatistics/";
  }
}
