import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { CreateUserComponent } from './pages/auth/create-user/create-user.component';
import { LoginComponent } from './pages/auth/login/login.component';
import {
    PasswordResetCompleteComponent
} from './pages/auth/password-reset-complete/password-reset-complete.component';
import { PasswordResetComponent } from './pages/auth/password-reset/password-reset.component';
import { SendEmailComponent } from './pages/auth/send-email/send-email.component';
import {
    SendPassResetEmailComponent
} from './pages/auth/send-pass-reset-email/send-pass-reset-email.component';
import { UserPageComponent } from './pages/auth/user-page/user-page.component';
import { BoardComponent } from './pages/drink/board/board.component';
import { DetailComponent } from './pages/drink/detail/detail.component';
import { PostComponent } from './pages/drink/post/post.component';
import { SearchComponent } from './pages/drink/search/search.component';

const routes: Routes = [
  { path:'auth/login', component:LoginComponent},
  { path:'auth/create/regist', component: CreateUserComponent },
  { path:'auth/create/send', component: SendEmailComponent },
  { path:'auth/user/page/:userId', component: UserPageComponent },
  { path:'auth/password/email', component: SendPassResetEmailComponent },
  { path:'auth/password/reset/:token', component: PasswordResetComponent },
  { path:'auth/password/complete', component: PasswordResetCompleteComponent },
  { path:'drink/board', component:BoardComponent},
  { path:'drink/add', component:PostComponent},
  { path:'drink/detail/:postId', component:DetailComponent},
  { path:'drink/search', component:SearchComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
