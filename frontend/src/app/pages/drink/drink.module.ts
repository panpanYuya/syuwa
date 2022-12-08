import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';

import { MaterialModule } from '../../material/material.module';
import { PagesModule } from '../pages.module';
import { BoardComponent } from './board/board.component';
import { PostComponent } from './post/post.component';

@NgModule({
  declarations: [
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MaterialModule,
    PagesModule,
  ]
})
export class DrinkModule { }
