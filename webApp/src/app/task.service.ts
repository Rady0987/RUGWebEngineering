import { Injectable } from '@angular/core';
import { WebRequestService } from './web-request.service';

@Injectable({
  providedIn: 'root'
})
export class TaskService {

  constructor(private webReqService: WebRequestService) { }

  dataRequest(parameters: string) {
    return this.webReqService.get(parameters);
  }

  movieDataRequest(parameters: string) {
    return this.webReqService.getMovies(parameters);
  }

  actorDataRequest(parameters : string) {
    return this.webReqService.getActors(parameters);
  }

  directorDataRequest(parameters : string) {
    return this.webReqService.getDirectors(parameters);
  }
}
