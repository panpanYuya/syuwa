import { of } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { MaterialModule } from 'src/app/material/material.module';
import { HtmlElementUtility } from 'src/app/testing/html-element-utility';

import { NO_ERRORS_SCHEMA } from '@angular/core';
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FormBuilder, ReactiveFormsModule } from '@angular/forms';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { Router } from '@angular/router';

import { UrlConst } from '../../constants/url-const';
import { LoginRequestDto } from '../models/dtos/requests/login-request-dto';
import { LoginService } from '../services/login.service';
import { LoginComponent } from './login.component';

describe('LoginComponent', () => {
  const expectedSignInResponseDto: LoginRequestDto = createExpectedRequestDto();
  let component: LoginComponent;

  let fixture: ComponentFixture<LoginComponent>;
  // let loginServiceSpy: { login: jasmine.Spy, setUser: jasmine.Spy };
  let loginServiceSpy = jasmine.createSpyObj('LoginService', ['getXsrfToken','login']);
  let router:Router;

  beforeEach(async () => {
    loginServiceSpy = jasmine.createSpyObj('LoginService', ['login', 'setUser']);
    let expectedLoginReqDto:LoginRequestDto = createExpectedRequestDto();
    await TestBed.configureTestingModule({
      schemas: [NO_ERRORS_SCHEMA],
      declarations: [LoginComponent],
      imports: [
        MaterialModule,
        BrowserAnimationsModule,
        ReactiveFormsModule
      ],
      providers: [
        FormBuilder,
        {provide: LoginService, useValue: loginServiceSpy}
      ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(LoginComponent);
    router = TestBed.inject(Router);
    component = fixture.componentInstance;
    fixture.detectChanges();

  });

  describe('#constractor', () => {
    it('should create', () => {
      expect(component).toBeTruthy();
    });
  });

  describe('formCheck', () => {
    it('should sign in user email', () => {
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#login-email', expectedSignInResponseDto.email);
      expect(component.email.value).toEqual(expectedSignInResponseDto.email);
    });

    it('sign in user password', () => {
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#login-password', expectedSignInResponseDto.password);
      expect(component.password.value).toEqual(expectedSignInResponseDto.password);
    });
  });

  describe('login', () => {
    it('should check xsrf-token', () => {
      loginServiceSpy.getXsrfToken();
    });

    it('should login', () => {
      loginServiceSpy.login.and.returnValue(of(expectedSignInResponseDto));
      spyOn(router, 'navigate');
      component.clickLoginButton();
      //TODO バックエンド作成後に作成
      expect(loginServiceSpy.setUser.calls.count()).toEqual(1);
      expect(router.navigate).toHaveBeenCalledWith([UrlConst.SLASH + UrlConst.PATH_DRINK + UrlConst.SLASH + UrlConst.PATH_SHOW]);
    });

  });

  describe('createLoginRequest', () => {
    it('should create RequestDto', () => {
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#login-email', expectedSignInResponseDto.email);
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#login-password', expectedSignInResponseDto.password);
      const loginRequestDto: LoginRequestDto = component.createLoginRequestDto();
      expect(loginRequestDto).toEqual(expectedSignInResponseDto);
    });
  });

});

function createExpectedRequestDto(): LoginRequestDto{
  return { email: 'test@test.com', password: 'password' };
}
