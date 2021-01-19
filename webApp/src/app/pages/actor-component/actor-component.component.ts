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
  items : any[] = [];
  
  constructor(private TaskService: TaskService) { }

  ngOnInit(): void {
    this.TaskService.dataRequest(this.request).toPromise().then(data => {
      console.log(data);

      for (let key in data) 
         if (data.hasOwnProperty(key))
           this.items.push(data[key]);
    });

  }

  switch(value : boolean) {
    this.items = [];
    if(value) {
      this.request = "actors/";
      this.TaskService.dataRequest(this.request).toPromise().then(data => {
        console.log(data);
  
        for (let key in data) 
           if (data.hasOwnProperty(key))
             this.items.push(data[key]);
      });
    } else {
      this.request = "directors/";
      this.TaskService.dataRequest(this.request).toPromise().then(data => {
        console.log(data);
  
        for (let key in data) 
           if (data.hasOwnProperty(key))
             this.items.push(data[key]);
      });
    }
    this.request = "actors/"
  }

}
