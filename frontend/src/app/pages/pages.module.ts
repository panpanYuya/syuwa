import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';

import { MaterialModule } from '../material/material.module';
import { AuthModule } from './auth/auth.module';
import { HeaderComponent } from './common/header/header.component';
import { LogoComponent } from './common/logo/logo.component';
import { NavigationComponent } from './common/navigation/navigation.component';
import { BoardComponent } from './drink/board/board.component';
import { PostComponent } from './drink/post/post.component';

@NgModule({
  declarations: [
    HeaderComponent,
    NavigationComponent,
    LogoComponent,
    PostComponent,
    BoardComponent,
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MaterialModule,
    HttpClientModule,
  ],
  exports: [
    LogoComponent,
    HeaderComponent,
  ]
})
export class PagesModule { }
