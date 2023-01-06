import { catchError, Observable, throwError } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { ShowUserInfoResponseDTO } from '../models/dtos/responses/show-user-info-response-dto';

@Injectable({
  providedIn: 'root'
})
export class UserInfoService {

  constructor(
    private http: HttpClient,
  ) { }

  public  findUserInfo(postId :number): Observable<ShowUserInfoResponseDTO> {
    return this.http.get<ShowUserInfoResponseDTO>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.PAGE + ApiConst.SLASH + postId)
      .pipe(
        catchError(this.handleError)
      );
  }


  private handleError(error: HttpErrorResponse) {
    return throwError(() => error);
  }
}
