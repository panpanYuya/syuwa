import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { ErrorStatusConst } from 'src/app/common/constants/error-status-const';

import { HttpHeaders, HttpResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';

import { RoutingService } from '../../../common/services/routing.service';
import { UrlConst } from '../../constants/url-const';
import { LoginRequestDto } from '../models/dtos/requests/login-request-dto';
import { LoginResponseDto } from '../models/dtos/responses/login-response-dto';
import { LoginService } from '../services/login.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  public errorMessage?: string;
  public xsrfToken: string;


  email = new FormControl('', [Validators.required]);
  password = new FormControl('', [Validators.required]);

  loginForm = this.formBuilder.group({
    email: this.email,
    password: this.password
  });

  constructor(
    private formBuilder: FormBuilder,
    private loginService: LoginService,
    private routingService: RoutingService,
  ) {
    this.xsrfToken = "";
  }

  ngOnInit(): void {
    this.setErrorMessage('');
  }

  clickLoginButton() {
    let loginRequestDto = this.createLoginRequestDto();
    this.login(loginRequestDto);
  }

  private login(loginRequestDto: LoginRequestDto) {
    let loginResponseDto: Observable<LoginResponseDto> = this.loginService.login(loginRequestDto);
    loginResponseDto.subscribe( {
      next:
        () => {
            this.routingService.transitToPath(UrlConst.PATH_DRINK + UrlConst.SLASH + UrlConst.PATH_SHOW);
        },
      error:
        (error) => {
          if (error.status === ErrorStatusConst.AUTH_ERROR_CODE) {
            return this.setErrorMessage(ErrorMessageConst.AUTH_ERROR);
          } else {
            return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
          }
        }
    });
    //TODO ???????????????????????????
    // let xsrf: Observable<string>= this.loginService.getXsrfToken();
    // xsrf.subscribe( {
    //   next:
    //   () => {
    //   },
    //   //error???????????????
    //   error:
    //     () => {
    //       return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
    //     }
    // });

  }

  public createLoginRequestDto():LoginRequestDto{
    return {
      email: this.email.value || "",
      password: this.password.value || ""
    };
  }

  public setErrorMessage(errorMessage: string) {
    this.errorMessage = errorMessage;
  }

}
