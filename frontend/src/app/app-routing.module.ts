import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { AuthModule } from './pages/auth/auth.module';
import { CreateUserComponent } from './pages/auth/create-user/create-user.component';
import { LoginComponent } from './pages/auth/login/login.component';
import { BoardComponent } from './pages/drink/board/board.component';

const routes: Routes = [
  { path:'auth/login', component:LoginComponent},
  { path:'auth/create/regist', component: CreateUserComponent },
  { path:'drink/board', component:BoardComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
