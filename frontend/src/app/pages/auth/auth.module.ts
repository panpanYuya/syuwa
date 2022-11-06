import { MaterialModule } from 'src/app/material/material.module';

import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule, HttpClientXsrfModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';

import { LogoComponent } from '../common/logo/logo.component';
import { PagesModule } from '../pages.module';
import { LoginComponent } from './login/login.component';

@NgModule({
  declarations: [
    LoginComponent
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
