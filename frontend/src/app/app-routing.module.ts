import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { LoginComponent } from './pages/auth/login/login.component';
import { BoardComponent } from './pages/drink/board/board.component';

const routes: Routes = [
  {path:'auth/login', component:LoginComponent},
  {path:'drink/board', component:BoardComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
