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

@NgModule({
  declarations: [
    BoardComponent,
    HeaderComponent,
    NavigationComponent,
    LogoComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MaterialModule,
  ],
  exports: [
    LogoComponent
  ]
})
export class PagesModule { }
