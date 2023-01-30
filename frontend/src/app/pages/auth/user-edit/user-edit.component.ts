import { CookieService } from 'ngx-cookie-service';
import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { ErrorStatusConst } from 'src/app/common/constants/error-status-const';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, OnChanges, OnInit } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';

import { UrlConst } from '../../constants/url-const';
import { EditUserInfoRequestDto } from '../models/dtos/requests/edit-user-info-request-dto';
import { EditUserInfoResponseDto } from '../models/dtos/responses/edit-user-info-response-dto';
import { EditUserResultResponseDto } from '../models/dtos/responses/edit-user-result-response-dto';
import { User } from '../models/user';
import { LoginService } from '../services/login.service';
import { UserInfoService } from '../services/user-info.service';

@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.scss']
})
export class UserEditComponent implements OnInit {

  public errorMessage: string;

  public requestServer: boolean;

  public user?: User;

  public userId: number;

  public successMessage?: string;

  constructor(
    private cookieService: CookieService,
    private formBuilder: FormBuilder,
    private loginService: LoginService,
    private routingService: RoutingService,
    private userInfoService: UserInfoService,
  ) {
    this.errorMessage = '';
    this.successMessage = '';
    this.requestServer = false;
    this.user = new User();
    this.requestServer = false;
  }

  userName = new FormControl('', [
    Validators.required,
    Validators.minLength(1),
    Validators.maxLength(50),
  ]);

  email = new FormControl('', [
    Validators.required,
    Validators.minLength(3),
    Validators.maxLength(255),
    Validators.email,
  ]);

  password = new FormControl('', [
    Validators.minLength(8),
    Validators.maxLength(255),
  ]);

  passwordConfirmation = new FormControl('', [
    Validators.minLength(8),
    Validators.maxLength(255),
  ]);

  editForm = this.formBuilder.group({
    userName: this.userName,
    email: this.email,
    password: this.password,
    passwordConfirmation: this.passwordConfirmation,
  });



  ngOnInit(): void {
    this.findUserInfo();
  }

  findUserInfo() {
    this.userId = Number(sessionStorage.getItem('userId'));
    let ediUserInfoResponseDto: Observable<EditUserInfoResponseDto> = this.userInfoService.findEditUserInfoByUserId(this.userId);
    ediUserInfoResponseDto.subscribe( {
      next:
        (result) => {
          this.setUser(result["User"]);
        },
      error:
        () => {
          return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
        }
    });
  }

  clickEditButton() {
    let editUserInfoRequestDto = this.createEditUserInfoRequestDto();
    this.editUserInfo(editUserInfoRequestDto);
  }


  editUserInfo(editUserInfoRequestDto: EditUserInfoRequestDto) {
    let ediUserInfoResponseDto: Observable<EditUserResultResponseDto> = this.userInfoService.editUserInfo(editUserInfoRequestDto);
    ediUserInfoResponseDto.subscribe( {
      next:
        (result) => {
          if (!result["temporary"]) {
            this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.USER + UrlConst.SLASH + UrlConst.PAGE + UrlConst.SLASH + sessionStorage.getItem('userId'));
          } else {
            this.successMessage = result["message"].toString();
          }
        },
      error:
        (error) => {
          return this.setErrorMessage(error.error.message);
        }
    });
  }


  public logout() {
    this.requestServer = true;
    let logoutResult: Observable<boolean> = this.loginService.logout();
    logoutResult.subscribe({
      next:
      () => {
      },
      error:
      () => {
        this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
      },
      complete:
      () => {
        this.requestServer = false;
      }
    });
    this.cookieService.deleteAll();
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.LOGIN)
  }

  public createEditUserInfoRequestDto():EditUserInfoRequestDto{
    return {
      user_id: this.userId,
      user_name: this.userName.value,
      email: this.email.value,
      password: this.password.value,
      password_confirmation: this.passwordConfirmation.value
    };
  }

  public setUser(user: any): void {
    this.userName.setValue(user.user_name);
    this.email.setValue(user.email);
  }

  public setErrorMessage(errorMessage: string) {
    this.errorMessage = errorMessage;
  }

}
