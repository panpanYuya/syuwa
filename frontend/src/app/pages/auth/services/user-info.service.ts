import { catchError, Observable, throwError } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';
import { environment } from 'src/environments/environment';

import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { EditUserInfoRequestDto } from '../models/dtos/requests/edit-user-info-request-dto';
import { CompletedUserUpdateDto } from '../models/dtos/responses/completed-user-update-dto';
import { EditUserInfoResponseDto } from '../models/dtos/responses/edit-user-info-response-dto';
import { EditUserResultResponseDto } from '../models/dtos/responses/edit-user-result-response-dto';
import { ShowUserInfoResponseDTO } from '../models/dtos/responses/show-user-info-response-dto';

@Injectable({
  providedIn: 'root'
})
export class UserInfoService {

  constructor(
    private http: HttpClient,
  ) { }

  public completedUserUpdate(token: string): Observable<CompletedUserUpdateDto> {
    return this.http.post<CompletedUserUpdateDto>(environment.apiUrl + ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.UPDATE + ApiConst.SLASH + ApiConst.COMPLETE + ApiConst.SLASH + token, '')
      .pipe(
        catchError(this.handleError)
      );
  }

  public editUserInfo(editUserInfoRequestDto: EditUserInfoRequestDto): Observable<EditUserResultResponseDto> {
    return this.http.post<EditUserResultResponseDto>(environment.apiUrl + ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.UPDATE , editUserInfoRequestDto)
      .pipe(
        catchError(this.handleError)
      );
  }

  public findUserInfo(postId :number): Observable<ShowUserInfoResponseDTO> {
    return this.http.get<ShowUserInfoResponseDTO>(environment.apiUrl + ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.PAGE + ApiConst.SLASH + postId)
      .pipe(
        catchError(this.handleError)
      );
  }

  public findEditUserInfoByUserId(userId: number): Observable<EditUserInfoResponseDto> {
    return this.http.get<EditUserInfoResponseDto>(environment.apiUrl + ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.EDIT + ApiConst.SLASH + userId)
      .pipe(
        catchError(this.handleError)
      );
  }

  public followUser(userId: number): Observable<boolean> {
    return this.http.put<boolean>(environment.apiUrl + ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.FOLLOW + ApiConst.SLASH + userId, '')
      .pipe(
        catchError(this.handleError)
      );
  }

  public unfollowUser(userId: number): Observable<boolean> {
    return this.http.delete<boolean>(environment.apiUrl + ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.USER + ApiConst.SLASH + ApiConst.UNFOLLOW + ApiConst.SLASH + userId)
      .pipe(
        catchError(this.handleError)
      );
  }

  private handleError(error: HttpErrorResponse) {
    return throwError(() => error);
  }
}
