import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-actor-component',
  templateUrl: './actor-component.component.html',
  styleUrls: ['./actor-component.component.css']
})
export class ActorComponent implements OnInit {
  switchValue = false;
  constructor() { }

  ngOnInit(): void {
  }

}
