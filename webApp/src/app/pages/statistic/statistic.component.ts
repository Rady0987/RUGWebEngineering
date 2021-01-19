import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';

interface Statistics {
  mean_popularity : number;
  median_popularity : number;
  standard_deviation_popularity : number;
}

@Component({
  selector: 'app-statistic',
  templateUrl: './statistic.component.html',
  styleUrls: ['./statistic.component.css']
})

export class StatisticComponent implements OnInit {
  request = "actorstatistics/"
  items : Statistics;
  constructor(private TaskService: TaskService) {
    this.items = {} as Statistics;
  }

  ngOnInit(): void {
  }

  getName(name : string) {
    this.items = {} as Statistics;
    if(name != "") {
        /*check if the parameters names (actor_name, director_name) are like 
        in the endpoints doc */
      this.request += name;
      this.TaskService.dataRequest(this.request).subscribe(data  => this.items = <Statistics> data);
    }
    this.request = "actorstatistics/";
  }
}
