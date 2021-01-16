import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';

@Component({
  selector: 'app-genres',
  templateUrl: './genres.component.html',
  styleUrls: ['./genres.component.css']
})
export class GenresComponent implements OnInit {
  request = "genres/";

  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
  }

  getName(name : string) {
    if(name != "") {
      /*check if the parameters names (actor_name, director_name) are like 
      in the endpoints doc */
      this.request += "?actor=" + name + "&director=" + name;
      let jsonMess = this.TaskService.dataRequest(this.request);
    }
    this.request = "genres/";
  }
}
