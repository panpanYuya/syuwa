import { catchError, Observable, of, tap } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient } from '@angular/common/http';
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

  public login(loginRequestDto:LoginRequestDto): Observable<LoginResponseDto> {
    return this.http.post<LoginResponseDto>(ApiConst.SLASH + ApiConst.LOGIN, loginRequestDto)
      .pipe(
        catchError(() => {
          return of(null as unknown  as LoginResponseDto);
        })
      );
  }

}
