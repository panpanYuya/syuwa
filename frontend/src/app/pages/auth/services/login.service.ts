import { catchError, Observable, of, tap } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient, HttpHeaders, HttpResponse } from '@angular/common/http';
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
        catchError(() => {
          return of(null as unknown  as LoginResponseDto);
        })
      );
  }

  public checkLogin():Observable<boolean> {
    return this.http.get<boolean>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.CHECK)
      .pipe(
        catchError(() => {
          return of(null as unknown as boolean);
        })
      );
  }

}
