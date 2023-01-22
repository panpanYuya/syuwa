import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { ErrorStatusConst } from 'src/app/common/constants/error-status-const';

import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';

import { UrlConst } from '../../constants/url-const';
import { PasswordResetEmailDto } from '../models/dtos/requests/password-reset-email-dto';
import { LoginResponseDto } from '../models/dtos/responses/login-response-dto';
import { LoginService } from '../services/login.service';
import { PasswordResetService } from '../services/password-reset.service';

@Component({
  selector: 'app-send-pass-reset-email',
  templateUrl: './send-pass-reset-email.component.html',
  styleUrls: ['./send-pass-reset-email.component.scss']
})
export class SendPassResetEmailComponent implements OnInit {

  public errorMessage?: string;
  public successMessage?: string;

  constructor(
    private formBuilder: FormBuilder,
    private loginService: LoginService,
    private passwordResetService: PasswordResetService,
  ) { }

  email = new FormControl('', [
    Validators.required,
    Validators.minLength(3),
    Validators.maxLength(255),
    Validators.email,
  ]);

  sendPassResetForm = this.formBuilder.group({
    email: this.email,
  });

  ngOnInit(): void {
  }

  clickSendButton() {
    let sendPassResetEmail = this.createSendPassResetEmailDto();
    this.sendPassResetEmail(sendPassResetEmail);
  }

  public sendPassResetEmail(sendPassResetEmail) {
    let xsrf: Observable<string>= this.loginService.getXsrfToken();
    xsrf.subscribe( {
      next:
      () => {
          let passwordResetEmailDto: Observable<PasswordResetEmailDto> = this.passwordResetService.sendPassResetEmail(sendPassResetEmail);
          passwordResetEmailDto.subscribe( {
            next:
              () => {
                  this.successMessage = "メールを送信しました。"
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

  private createSendPassResetEmailDto():PasswordResetEmailDto {
    return {
      email: this.email.value || ""
    };
  }

}
