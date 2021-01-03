import { Component, NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ActorComponent } from './pages/actor-component/actor-component.component';
import { HomeComponent } from './pages/home/home.component';
import { MoviesComponent } from './pages/movies/movies.component';
import { StatisticComponent } from './pages/statistic/statistic.component';

const routes: Routes = [
  { path: '', redirectTo: 'home', pathMatch: 'full' },
  {
    path: '',
    children: [
      {
        path: 'actors',
        component: ActorComponent
      },
      {
        path: 'home',
        component: HomeComponent
      },
      {
        path: 'movies',
        component: MoviesComponent
      },
      {
        path: 'statistics',
        component: StatisticComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
