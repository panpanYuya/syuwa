import { of } from 'rxjs';
import { HtmlElementUtility } from 'src/app/testing/html-element-utility';

import { ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
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
      declarations: [LoginComponent],
      providers: [
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
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#email', expectedValue);
      expect(component.email.value).toEqual(expectedValue);
    });

    it('sign in user email', () => {
      const expectedValue = 'testtest';
      HtmlElementUtility.setValueToHTMLInputElement(fixture, '#password', expectedValue);
      expect(component.password.value).toEqual(expectedValue);
    });
  });

  describe('login', () => {
    // it('should not login', () => {

    // });
    it('should login', () => {
      loginServiceSpy.login.and.returnValue(of(expectedSignInResponseDto));
      component.clickLoginButton();
      expect(loginServiceSpy.setUser.calls.count()).toEqual(1);
      expect(router.navigate).toHaveBeenCalledWith([UrlConst.SLASH + UrlConst.PATH_DRINK + UrlConst.SLASH + UrlConst.PATH_SHOW]);
    });
  });


});

function createExpectedRequestDto(): LoginRequestDto{
  return { email: 'test@test.com', password: 'password' };
}
