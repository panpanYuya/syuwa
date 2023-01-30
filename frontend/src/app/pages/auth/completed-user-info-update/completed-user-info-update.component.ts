import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { ErrorStatusConst } from 'src/app/common/constants/error-status-const';

import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { CompletedUserUpdateDto } from '../models/dtos/responses/completed-user-update-dto';
import { LoginService } from '../services/login.service';
import { UserInfoService } from '../services/user-info.service';

@Component({
  selector: 'app-completed-user-info-update',
  templateUrl: './completed-user-info-update.component.html',
  styleUrls: ['./completed-user-info-update.component.scss']
})
export class CompletedUserInfoUpdateComponent implements OnInit {

  public errorMessage?: string;

  public successMessage?: string;

  public currentUrl?: string;

  public token: string;

  constructor(
    private userInfoService: UserInfoService,
    private loginService: LoginService,
    private router: Router
  ) {
    this.currentUrl = this.router.url;
   }

  ngOnInit(): void {
    this.getToken();
    this.CompletedUserUpdate();
  }

  getToken() {
    let url = this.currentUrl.split('/')
    this.token = url[5];
  }

  public CompletedUserUpdate() {
    let xsrf: Observable<string>= this.loginService.getXsrfToken();
    xsrf.subscribe( {
      next:
      () => {
          let completedUserUpdateDto: Observable<CompletedUserUpdateDto> =this.userInfoService.completedUserUpdate(this.token);
          completedUserUpdateDto.subscribe( {
            next:
              (result) => {
                if (!result.result) {
                  this.errorMessage = result["message"];
                } else {
                  this.successMessage = result["message"];
                }
              },
            error:
              (error) => {
                if (error.status === ErrorStatusConst.VALIDATION_ERROR) {
                  return this.setErrorMessage(error.error.message);
                } else {
                  return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
                }
              }
            });
        },
        error:
          () => {
            return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
          }
      });
  }


  public setErrorMessage(errorMessage: string) {
    this.errorMessage = errorMessage;
  }

}
