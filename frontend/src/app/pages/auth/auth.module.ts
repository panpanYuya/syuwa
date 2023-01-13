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

@NgModule({
  declarations: [
    LoginComponent,
    CreateUserComponent,
    SendEmailComponent,
    UserPageComponent,
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
