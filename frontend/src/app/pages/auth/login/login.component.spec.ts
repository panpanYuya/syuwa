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
  let loginServiceSpy: { login: jasmine.Spy, setUser: jasmine.Spy };
  let router:Router;

  beforeEach(async () => {
    loginServiceSpy = jasmine.createSpyObj('LoginService', ['login', 'setUser']);

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
    it('sign in user email', () => {
      const expectedValue = 'test@test.com';
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#login-email', expectedValue);
      expect(component.email.value).toEqual(expectedValue);
    });

    it('sign in user password', () => {
      const expectedValue = 'testtest';
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#login-password', expectedValue);
      expect(component.password.value).toEqual(expectedValue);
    });
  });

  describe('login', () => {
    it('should login', () => {
      spyOn(router, 'navigate');
      component.clickLoginButton();
      // expect(loginServiceSpy.setUser.calls.count()).toEqual(1);
      expect(router.navigate).toHaveBeenCalledWith([UrlConst.SLASH + UrlConst.PATH_DRINK + UrlConst.SLASH + UrlConst.PATH_SHOW]);
    });
  });


});

function createExpectedRequestDto(): LoginRequestDto{
  return { email: 'test@test.com', password: 'password' };
}
