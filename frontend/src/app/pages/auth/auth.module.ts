import { MaterialModule } from 'src/app/material/material.module';

import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule, HttpClientXsrfModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';

import { PagesModule } from '../pages.module';
import { CreateUserComponent } from './create-user/create-user.component';
import { LoginComponent } from './login/login.component';

@NgModule({
  declarations: [
    LoginComponent,
    CreateUserComponent,
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MaterialModule,
    HttpClientModule,
    PagesModule
  ],
  exports: [
    ReactiveFormsModule,
  ]
})
export class AuthModule { }
