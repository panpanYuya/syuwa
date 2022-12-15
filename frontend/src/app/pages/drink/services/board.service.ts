import { catchError, Observable, of, throwError } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { getSafePropertyAccessString } from '@angular/compiler';
import { Injectable } from '@angular/core';

import { CreateNewPostRequestDto } from '../models/dtos/requests/create-new-post-request-dto';
import { GetTagsResponseDto } from '../models/dtos/responses/get-tags-response-dto';
import { ShowBoardResponseDto } from '../models/dtos/responses/show-board-response-dto';

@Injectable({
  providedIn: 'root'
})
export class BoardService {

  constructor(
    private http: HttpClient
  ) { }

  getPosts(): Observable<ShowBoardResponseDto> {
    return this.http.get<ShowBoardResponseDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.SHOW)
      .pipe(
        catchError(() => {
          return of(null as unknown as ShowBoardResponseDto);
        })
      );
  }

  createNewPost(createNewPost: CreateNewPostRequestDto): Observable<CreateNewPostRequestDto> {
    return this.http.post<CreateNewPostRequestDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.ADD, createNewPost)
      .pipe(
        catchError(this.handleError)
      );
  }

  getTags(): Observable<GetTagsResponseDto> {
    return this.http.get<GetTagsResponseDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.CREATE)
      .pipe(
        catchError(this.handleError)
      );
  }


  private handleError(error: HttpErrorResponse) {
    return throwError(() => error);
  }
}
