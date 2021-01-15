import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';

@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.css']
})
export class MoviesComponent implements OnInit {
  titleVal = "?title=";
  actorVal = "?actor=";
  directorVal = "?director=";
  yearVal = 0;
  request = "";

  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
  }

  getText(title : any, year : any, actor : any, director: any) {
    // Need to continue
    this.titleVal += title;
    this.yearVal = year;
    this.actorVal += actor;
    this.directorVal += director;
    console.log('title:' + this.titleVal);
    console.log('year:' + this.yearVal);
    console.log('actor:' + this.actorVal);
    console.log('director:' + this.directorVal);
    this.request = `/movies${(this.titleVal != "?title=") ? this.titleVal : ""}`;
    //this.request += `${(this.actorVal != "&?actor=") ? this.titleVal : ""}`;
    console.log('request:' + this.request);
    //this.TaskService.movieRequest()
  }
  
}
