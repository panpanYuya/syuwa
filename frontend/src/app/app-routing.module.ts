import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import {
    CompletedUserInfoUpdateComponent
} from './pages/auth/completed-user-info-update/completed-user-info-update.component';
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
import { UserEditComponent } from './pages/auth/user-edit/user-edit.component';
import { UserPageComponent } from './pages/auth/user-page/user-page.component';
import { BoardComponent } from './pages/drink/board/board.component';
import { DetailComponent } from './pages/drink/detail/detail.component';
import { PostComponent } from './pages/drink/post/post.component';
import { SearchComponent } from './pages/drink/search/search.component';
import { AuthGuard } from './pages/guards/auth.guard';

const routes: Routes = [
  { path:'auth/login', component:LoginComponent },
  { path:'auth/create/regist', component: CreateUserComponent },
  { path:'auth/create/send', component: SendEmailComponent },
  { path:'auth/user/edit/complete/:token', component: CompletedUserInfoUpdateComponent},
  { path:'auth/user/edit', component: UserEditComponent, canActivate: [AuthGuard]},
  { path:'auth/user/page/:userId', component: UserPageComponent, canActivate: [AuthGuard]},
  { path:'auth/password/email', component: SendPassResetEmailComponent },
  { path:'auth/password/reset/:token', component: PasswordResetComponent },
  { path:'auth/password/complete', component: PasswordResetCompleteComponent },
  { path:'drink/board', component:BoardComponent, canActivate: [AuthGuard]},
  { path:'drink/add', component:PostComponent, canActivate: [AuthGuard]},
  { path:'drink/detail/:postId', component:DetailComponent, canActivate: [AuthGuard]},
  { path:'drink/search', component:SearchComponent, canActivate: [AuthGuard]},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
