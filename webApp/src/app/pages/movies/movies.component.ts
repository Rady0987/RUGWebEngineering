import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  titleVal = "";
  actorVal = "";
  directorVal = "";
  yearVal = 0;

  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
  }

  getText(title : any, year : any, actor : any, director: any) {
    // Need to continue
    console.warn(title)
    alert(title)
    this.titleVal = title;
    this.yearVal = year;
    this.actorVal = actor;
    this.directorVal = director;
  }
  
}
