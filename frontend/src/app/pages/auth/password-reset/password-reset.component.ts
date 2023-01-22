import { filter, Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { ErrorStatusConst } from 'src/app/common/constants/error-status-const';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';
import { Router, RouterEvent } from '@angular/router';

import { UrlConst } from '../../constants/url-const';
import { PasswordResetRequestDto } from '../models/dtos/requests/password-reset-request-dto';
import { LoginService } from '../services/login.service';
import { PasswordResetService } from '../services/password-reset.service';

@Component({
  selector: 'app-password-reset',
  templateUrl: './password-reset.component.html',
  styleUrls: ['./password-reset.component.scss']
})
export class PasswordResetComponent implements OnInit {
  public errorMessage?: string;

  public currentUrl?: string;

  public token: string;

  public expiredFlg: boolean;

  public passwordFlg: boolean;

  constructor(
    private formBuilder: FormBuilder,
    private loginService: LoginService,
    private passwordResetService: PasswordResetService,
    private routingService: RoutingService,
    private router: Router
  ) {
    this.currentUrl = this.router.url;
    this.expiredFlg = true;
    this.passwordFlg = false;
  }

  password = new FormControl('', [
    Validators.required,
    Validators.minLength(8),
    Validators.maxLength(255),
  ]);
  passwordConfirmation = new FormControl('', [
    Validators.required,
    Validators.minLength(8),
    Validators.maxLength(255),
  ]);

  passwordForm = this.formBuilder.group({
    password: this.password,
    passwordConfirmation: this.passwordConfirmation
  });


  ngOnInit(): void {
    this.setErrorMessage('');
    this.getToken();
    this.checkToken();
  }

  getToken() {
    let url = this.currentUrl.split('/')
    this.token = url[4];
  }

  clickPasswordButton() {
    let sendPassResetDto = this.createSendPassResetDto();
    this.passwordReset(sendPassResetDto);
  }

  public toLogin() {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.LOGIN)
  }



  checkToken() {
    let xsrf: Observable<string>= this.loginService.getXsrfToken();
    xsrf.subscribe( {
      next:
      () => {
          let passwordResetEmailDto: Observable<boolean> = this.passwordResetService.checkToken(this.token);
          passwordResetEmailDto.subscribe( {
            next:
              () => {
                this.passwordFlg = true;
              },
            error:
              () => {
                this.expiredFlg = false;
                this.setErrorMessage(ErrorMessageConst.EXPIRED_ERROR);
              }
            });
        },
        error:
          () => {
            this.expiredFlg = false;
            return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
          }
      });
  }

  passwordReset(sendPassResetDto: PasswordResetRequestDto) {
    this.passwordFlg = false;
    let passwordResetEmailDto: Observable<boolean> = this.passwordResetService.passwordReset(sendPassResetDto);
    passwordResetEmailDto.subscribe( {
      next:
        () => {
            this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.PASSWORD + UrlConst.SLASH + UrlConst.COMPLETE);
        },
      error:
        (error) => {
          this.passwordFlg = true;
          if (error.status === ErrorStatusConst.VALIDATION_ERROR) {
            return this.setErrorMessage(error.error.message);
          } else {
            return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
          }
        }
    });
  }


  public setErrorMessage(errorMessage: string) {
    this.errorMessage = errorMessage;
  }


  private createSendPassResetDto():PasswordResetRequestDto {
    return {
      token: this.token || "",
      password: this.password.value || "",
      password_confirmation: this.passwordConfirmation.value || ""
    };
  }

}

function checkPasswordOfValue() {
  if (this.password !== this.passwordConfirmation) {

  }
}
