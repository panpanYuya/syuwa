import { catchError, Observable, of, throwError } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { CreateUserRequestDto } from '../models/dtos/requests/create-user-request-dto';
import { CreateUserResponseDto } from '../models/dtos/responses/create-user-response-dto';

@Injectable({
  providedIn: 'root'
})
export class CreateService {

  constructor(
    private http: HttpClient,
  ) { }

  public createUser(createUserRequestDto:CreateUserRequestDto):Observable<any> {
    return this.http.post<CreateUserResponseDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.REGIST, createUserRequestDto)
      .pipe(
        catchError(this.handleError)
      );
  }

  private handleError(error: HttpErrorResponse) {
    return throwError(() => error);
  }

}
