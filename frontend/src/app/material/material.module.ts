import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatToolbarModule } from '@angular/material/toolbar';

@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    MatButtonModule,
    MatFormFieldModule,
    MatButtonModule,
    MatInputModule,
    MatToolbarModule,
    MatIconModule,
    MatSelectModule,
  ],
  exports: [
    MatButtonModule,
    MatFormFieldModule,
    MatButtonModule,
    MatInputModule,
    MatToolbarModule,
    MatIconModule,
    MatSelectModule,
  ]
})
export class MaterialModule { }
