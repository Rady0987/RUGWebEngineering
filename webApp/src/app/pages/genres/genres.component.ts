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
    //API call get/api/genres/ has only actor parameter (no director)
    this.request += "?actor=" + name;
    let jsonMess = this.TaskService.dataRequest(this.request);
    this.request = "genres/";
  }
}
