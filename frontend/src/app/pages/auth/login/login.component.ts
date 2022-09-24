import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';

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
    private loginService: LoginService
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
    this.loginService.login(loginRequestDto);
  }

  private createLoginRequestDto():LoginRequestDto{
    return {
      email: this.email.value || "",
      password: this.password.value || ""
    };
  }


}
