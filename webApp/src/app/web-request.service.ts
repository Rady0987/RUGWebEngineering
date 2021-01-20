import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http'
import { MOVIE } from './pages/movies/movie';
import { ACTORSDIR } from './pages/actor-component/actors';
import { GENRES } from './pages/genres/genres';

@Injectable({
  providedIn: 'root'
})
export class WebRequestService {

  readonly API_URL;
  
  constructor(private http: HttpClient) {
    this.API_URL = 'https://movies.max.ug/api2'
  }

  get(uri: string) {
    console.log(`${this.API_URL}/${uri}`);
    return this.http.get(`${this.API_URL}/${uri}`);
  }

  post(uri: string, payload: Object) {
    return this.http.post(`${this.API_URL}/${uri}`, payload);
  }

  patch(uri: string, payload: Object) {
    return this.http.patch(`${this.API_URL}/${uri}`, payload);
  }

  delete(uri: string) {
    return this.http.delete(`${this.API_URL}/${uri}`);
  }

  getGenre(uri : string) {
    return this.http.get<GENRES[]>(`${this.API_URL}/${uri}`);
  }
}
