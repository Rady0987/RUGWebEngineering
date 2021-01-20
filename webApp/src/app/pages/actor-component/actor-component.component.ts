import { Component, OnInit } from '@angular/core';
import { TaskService } from 'src/app/task.service';
import { GENRES } from './genres';
import { ACTORSDIR } from './actors';
import{STATISTICS} from './statistics';

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

  showModal(value: boolean ,id: string): void {
    this.genres =[];
    this.statistics = {} as STATISTICS;
    this.isVisible = true;
    console.log(id);
    if(value){
      this. genre_request = "actor/"+ id +"/genres";
      this.TaskService.dataRequest(this.genre_request).subscribe(data => { this.genres = <GENRES[]> data; console.log(data);});
      this. statistics_request = "actor/"+ id+"/statistics";
      this.TaskService.dataRequest(this.statistics_request).subscribe(data => { this.statistics = <STATISTICS> data; console.log(data);});
      console.log(this.genre_request, this.statistics_request);
    }else{
      this. genre_request = "director/"+ id +"/genres";
      this.TaskService.dataRequest(this.genre_request).subscribe(data => { this.genres = <GENRES[]> data; console.log(data);});
      console.log(this.genre_request, this.statistics_request);
    }
    this. genre_request = "actor/"+ id +"/genres";
    this. statistics_request = "actor/"+ id +"/statistics";
  }

  handleOk(): void {
    console.log('Button ok clicked!');
    this.isVisible = false;
  }

  handleCancel(): void {
    console.log('Button cancel clicked!');
    this.isVisible = false;
  }
  
  constructor(private TaskService: TaskService) {
    this.items = [];
    this.limit = 50;
    this.genres = [];
    this.statistics = {} as STATISTICS;
   }

  ngOnInit(): void { 
    this.request = "actors?limit=" + this.limit;
    this.TaskService.dataRequest(this.request).subscribe(data => { this.items = <ACTORSDIR[]> data; console.log(data);});
    console.log(this.items);
  }

  changeSize(newSize : number) {
    this.limit = newSize;
  }
  

  switch(value : boolean) {
    this.items = [];
    if(value) {
      this.request = "actors?limit=" + this.limit;
      this.TaskService.dataRequest(this.request).subscribe(data => { this.items = <ACTORSDIR[]> data; console.log(data);});

    } else {
      this.request = "directors?limit=" + this.limit;
      this.TaskService.dataRequest(this.request).subscribe(data => { this.items = <ACTORSDIR[]> data; console.log(data);});
    }
    this.request = "actors?limit=" + this.limit;
  }

}
