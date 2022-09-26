import { retry } from 'rxjs';

import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { RoutingService } from '../../../common/services/routing.service';
import { UrlConst } from '../../constants/url-const';
import { LoginRequestDto } from '../models/dtos/requests/login-request-dto';
import { LoginService } from '../services/login.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

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
  ) { }

  ngOnInit(): void {
  }


  clickLoginButton() {
    let loginRequestDto = this.createLoginRequestDto();
    this.login(loginRequestDto);
  }

  onSubmit() {
    alert(JSON.stringify(this.loginForm.value));
  }

  private login(loginRequestDto:LoginRequestDto) {
    // this.loginService.login(loginRequestDto);

    this.routingService.transitToPath(UrlConst.PATH_DRINK + UrlConst.SLASH + UrlConst.PATH_SHOW);
  }

  private createLoginRequestDto():LoginRequestDto{
    return {
      email: this.email.value || "",
      password: this.password.value || ""
    };
  }


}
