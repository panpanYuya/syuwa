import { CookieService } from 'ngx-cookie-service';
import { MaterialModule } from 'src/app/material/material.module';

import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule, HttpClientXsrfModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';

import { PagesModule } from '../pages.module';
import { CreateUserComponent } from './create-user/create-user.component';
import { LoginComponent } from './login/login.component';
import { SendEmailComponent } from './send-email/send-email.component';
import { UserPageComponent } from './user-page/user-page.component';
import { PasswordResetComponent } from './password-reset/password-reset.component';
import { SendPassResetEmailComponent } from './send-pass-reset-email/send-pass-reset-email.component';
import { PasswordResetCompleteComponent } from './password-reset-complete/password-reset-complete.component';
import { UserEditComponent } from './user-edit/user-edit.component';
import { CompletedUserInfoUpdateComponent } from './completed-user-info-update/completed-user-info-update.component';

@NgModule({
  declarations: [
    LoginComponent,
    CreateUserComponent,
    SendEmailComponent,
    UserPageComponent,
    PasswordResetComponent,
    SendPassResetEmailComponent,
    PasswordResetCompleteComponent,
    UserEditComponent,
    CompletedUserInfoUpdateComponent,
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MaterialModule,
    HttpClientModule,
    PagesModule
  ],
  providers: [
    CookieService
  ],
  exports: [
    ReactiveFormsModule,
  ]
})
export class AuthModule { }
