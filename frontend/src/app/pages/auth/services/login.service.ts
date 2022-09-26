import { Observable } from 'rxjs';

import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { LoginRequestDto } from '../models/dtos/requests/login-request-dto';

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  apiUrl = "/test";

  constructor(
    private http: HttpClient,
  ) { }

  public login(loginRequestDto:LoginRequestDto): Observable<LoginRequestDto> {
    return this.http.post<LoginRequestDto>(this.apiUrl, loginRequestDto);
  }

}
