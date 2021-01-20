import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import { GENRES } from './genres';
import { ACTORSDIR } from './actors';
import{ STATISTICS } from './statistics';

@Component({
  selector: 'app-actor-component',
  templateUrl: './actor-component.component.html',
  styleUrls: ['./actor-component.component.css']
})

export class ActorComponent implements OnInit {
  switchValue = false;
  request = "actors";
  genre_request = "actor/"
  statistics_request = "actor/"
  items : ACTORSDIR[];
  limit : number;
  genres: GENRES[];
  statistics: STATISTICS;
  isVisible = false;
  
  constructor(private TaskService: TaskService) {
    this.items = [];
    this.limit = 50;
    this.genres = [];
    this.statistics = {} as STATISTICS;
   }

  // Method that will be called as soon as the page is initialized
  ngOnInit(): void { 
    this.request = "actors?limit=" + this.limit;
    this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
  }

  // Method that is used to GET request (and parse the response into an array) the genres and statistics data for actors and directors,
  showModal(value: boolean ,id: string) : void {
    this.genres =[];
    this.statistics = {} as STATISTICS;
    this.isVisible = true;
    if(value) {
      this. genre_request = "actor/" + id + "/genres";
      this.TaskService.dataRequest(this.genre_request).subscribe(data => this.genres = <GENRES[]> data);
      this. statistics_request = "actor/" + id + "/statistics";
      this.TaskService.dataRequest(this.statistics_request).subscribe(data => this.statistics = <STATISTICS> data);
    } else {
      this. genre_request = "director/" + id + "/genres";
      this.TaskService.dataRequest(this.genre_request).subscribe(data => this.genres = <GENRES[]> data);
    }
    this. genre_request = "actor/"+ id +"/genres";
    this. statistics_request = "actor/"+ id +"/statistics";
  }

  // Method called when the ok button on modal menu is pressed
  handleOk(): void {
    console.log('Button ok clicked!');
    this.isVisible = false;
  }

  // Method used to change the number of actor/director names querried by GET request.
  changeSize(newSize : number) {
    this.limit = newSize;
  }

  // Method to search actors / directors by name
  filterBy(name : string, value : boolean) { 
    this.items = [];
    if (value) { 
      this.request = "actors?name=" + name;
      this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
    } else {
      this.request = "directors?name=" + name;
      this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
    }
    this.request = "actors?name=" + name;
  }
  

  // Method to display all the actors or directors, with a given limit.
  switch(value : boolean) {
    this.items = [];
    if(value) {
      if (this.limit != 0) {
        this.request = "actors?limit=" + this.limit;
        this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
      } else {
        this.request = "actors"
        this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
      }
    } else {
      if (this.limit != 0) {
        this.request = "directors?limit=" + this.limit;
        this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
      } else {
        this.request = "directors"
        this.TaskService.dataRequest(this.request).subscribe(data => this.items = <ACTORSDIR[]> data);
      }
    }
    this.request = "actors?limit=" + this.limit;
  }

  // Method using for sorting the actors alphabetically by name
  sortName = (a: ACTORSDIR, b: ACTORSDIR) => a.name.localeCompare(b.name);
}
