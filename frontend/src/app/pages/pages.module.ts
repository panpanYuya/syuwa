import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';

import { MaterialModule } from '../material/material.module';
import { AuthModule } from './auth/auth.module';
import { BoardComponent } from './drink/board/board.component';
import { HeaderComponent } from './common/header/header.component';
import { NavigationComponent } from './common/navigation/navigation.component';

@NgModule({
  declarations: [
    BoardComponent,
    HeaderComponent,
    NavigationComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MaterialModule,
    AuthModule
  ],
})
export class PagesModule { }
