import { catchError, Observable, throwError } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { PasswordResetEmailDto } from '../models/dtos/requests/password-reset-email-dto';
import { PasswordResetRequestDto } from '../models/dtos/requests/password-reset-request-dto';

@Injectable({
  providedIn: 'root'
})
export class PasswordResetService {

  constructor(
    private http: HttpClient,
  ) { }

  public sendPassResetEmail(email :string): Observable<PasswordResetEmailDto> {
    return this.http.post<PasswordResetEmailDto>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.PASSWORD + ApiConst.SLASH + ApiConst.EMAIL, email)
      .pipe(
        catchError(this.handleError)
      );
  }

  public checkToken(token: string): Observable<boolean> {
    return this.http.post<boolean>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.PASSWORD + ApiConst.SLASH + ApiConst.RESET + ApiConst.SLASH + token, '')
      .pipe(
        catchError(this.handleError)
      );
  }

  public passwordReset(passwordResetRequestDto: PasswordResetRequestDto): Observable<boolean>{
    return this.http.post<boolean>(ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.PASSWORD + ApiConst.SLASH + ApiConst.COMPLETE, passwordResetRequestDto)
      .pipe(
        catchError(this.handleError)
      );

  }

  private handleError(error: HttpErrorResponse) {
      return throwError(() => error);
  }

}
