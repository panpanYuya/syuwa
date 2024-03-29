import { catchError, Observable, of, tap, throwError } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';
import { environment } from 'src/environments/environment';

import { HttpClient, HttpErrorResponse, HttpHeaders, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { LoginRequestDto } from '../models/dtos/requests/login-request-dto';
import { LoginResponseDto } from '../models/dtos/responses/login-response-dto';

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor(
    private http: HttpClient,
  ) { }

  public getXsrfToken(): Observable<string> {
    return this.http.get<string>(ApiConst.SLASH + ApiConst.XSRF)
      .pipe(
        catchError(() => {
          return of(null as unknown as string);
        })
      );
  }

  public login(loginRequestDto:LoginRequestDto): Observable<LoginResponseDto> {
    return this.http.post<LoginResponseDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.LOGIN, loginRequestDto)
      .pipe(
        catchError(this.handleError)
      );
  }

  public checkLogin():Observable<boolean> {
    return this.http.post<boolean>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.CHECK, "")
      .pipe(
        catchError(() => {
          return of(null);
        })
      );
  }

  public logout(): Observable<boolean> {
    return this.http.post<boolean>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.LOGOUT, '')
      .pipe(
        catchError(() => {
          return of(false);
        })
      );
  }

  private handleError(error: HttpErrorResponse) {
    return throwError(() => error);
  }

}
