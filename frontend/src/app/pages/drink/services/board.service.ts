import { catchError, Observable, of } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient } from '@angular/common/http';
import { getSafePropertyAccessString } from '@angular/compiler';
import { Injectable } from '@angular/core';

import { ShowBoardResponseDto } from '../models/dtos/responses/show-board-response-dto';

@Injectable({
  providedIn: 'root'
})
export class BoardService {

  constructor(
    private http: HttpClient
  ) { }

  getPosts(): Observable<ShowBoardResponseDto>{
    return this.http.get<ShowBoardResponseDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.SHOW)
      .pipe(
        catchError(() => {
          return of(null as unknown as ShowBoardResponseDto);
        })
      );
  }
}
