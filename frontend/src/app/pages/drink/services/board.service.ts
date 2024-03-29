import { catchError, Observable, of, throwError } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';
import { environment } from 'src/environments/environment';

import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { CreateNewPostRequestDto } from '../models/dtos/requests/create-new-post-request-dto';
import { GetPostDetailResponseDTO } from '../models/dtos/responses/get-post-detail-response-dto';
import { GetTagsResponseDto } from '../models/dtos/responses/get-tags-response-dto';
import { ShowBoardResponseDto } from '../models/dtos/responses/show-board-response-dto';

@Injectable({
  providedIn: 'root'
})
export class BoardService {

  constructor(
    private http: HttpClient
  ) { }

  getPosts(displaiedPost:number): Observable<ShowBoardResponseDto> {
    return this.http.get<ShowBoardResponseDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.SHOW + ApiConst.SLASH + displaiedPost)
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

  getPostDetail(postId: number): Observable<GetPostDetailResponseDTO>{
    return this.http.get<GetPostDetailResponseDTO>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.DETAIL + ApiConst.SLASH  +postId)
      .pipe(
        catchError(this.handleError)
      );
  }

  searchPostsByTag(tagId: number, numOfDisplaiedPosts: number): Observable<ShowBoardResponseDto> {
    return this.http.get<ShowBoardResponseDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.SEARCH + ApiConst.SLASH + tagId + ApiConst.SLASH + numOfDisplaiedPosts)
      .pipe(
        catchError(this.handleError)
      );
  }


  private handleError(error: HttpErrorResponse) {
    return throwError(() => error);
  }
}
