import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';

import { UrlConst } from '../../constants/url-const';
import { CreateUserRequestDto } from '../models/dtos/requests/create-user-request-dto';
import { CreateUserResponseDto } from '../models/dtos/responses/create-user-response-dto';
import { User } from '../models/user';
import { CreateService } from '../services/create.service';
import { LoginService } from '../services/login.service';

@Component({
  selector: 'app-create-user',
  templateUrl: './create-user.component.html',
  styleUrls: ['./create-user.component.scss']
})
export class CreateUserComponent {

  public errorMessage?: string;
  public user: User;

  constructor(
    private formBuilder: FormBuilder,
    private routingService: RoutingService,
    private createService: CreateService,
    private loginService: LoginService
  ) {
    this.user = new User();
  }

  userName = new FormControl('', [
    Validators.required,
    Validators.minLength(1),
    Validators.maxLength(10),
    // Validators.pattern('\S*'),
    // Validators.pattern('[^ぁ-んァ-ン０-９a-zA-Z0-9!-\/:;@¥\[-`\{-~].+'),
  ]);

  email = new FormControl('', [
    Validators.required,
    Validators.minLength(3),
    Validators.maxLength(255),
    Validators.email,
  ]);

  birthday = new FormControl('', [
    Validators.required,
  ]);

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

  createForm = this.formBuilder.group({
    userName: this.userName,
    email: this.email,
    birthday: this.birthday,
    password: this.password,
    passwordConfirmation: this.passwordConfirmation,
  });

  clickCreateUser() {
    let createUserRequestDto = this.setCreateUserRequest();
    this.createUser(createUserRequestDto);
  }

  createUser(createUserRequestDto: CreateUserRequestDto) {
    let xsrf: Observable<string>= this.loginService.getXsrfToken();
    xsrf.subscribe( {
      next:
        () => {
        this.createService.createUser(createUserRequestDto).subscribe( {
        next:
            () => {
              this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH  + UrlConst.SLASH + UrlConst.CREATE + UrlConst.SLASH + UrlConst.SEND);
          },
        error:
          (error) => {
            if (error.status === 422) {
              return this.setErrorMessage(error.error.message);
            } else {
              return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
            }
          }
    });
      },
      //errorを以後修正
      error:
        () => {
          return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
        }
    });

  }

  private setCreateUserRequest():CreateUserRequestDto {
    return {
      user_name: this.userName.value || '',
      email: this.email.value || '',
      birthday: this.birthday.value || '',
      password: this.password.value || '',
      password_confirmation: this.passwordConfirmation.value || '',
    }
  }

  private setResult(result:boolean, message: string): CreateUserResponseDto{
    return {
      result: result || false,
      message: message || ""
    };
  }

  private setErrorMessage(errorMessage: string) {
    this.errorMessage = errorMessage;
  }

}
