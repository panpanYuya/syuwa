import { HttpClient } from '@angular/common/http';
import { TestBed } from '@angular/core/testing';

import { LoginService } from './login.service';

describe('LoginService', () => {
  let service: LoginService;
  let httpClientSpy = jasmine.createSpyObj<HttpClient>(["get"]);

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(LoginService);
  });

  xdescribe('LoginService', () => {
    it('should be created', () => {
      expect(service).toBeTruthy();
    });
  });

  xdescribe('login', () => {
    it('should return expected response', () => {
      expect(service).toBeTruthy();
    });
  });


});
