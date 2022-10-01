import { ApiConst } from 'src/app/common/constants/api-const';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';

import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';

import { LoginRequestDto } from '../models/dtos/requests/login-request-dto';
import { LoginResponseDto } from '../models/dtos/responses/login-response-dto';
import { LoginService } from './login.service';

describe('LoginService', () => {
  let service: LoginService;
  let httpTestingController: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ HttpClientTestingModule ]
    });
    service = TestBed.inject(LoginService);
    httpTestingController = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpTestingController.verify();
  });

  describe('LoginService', () => {
    it('should be created', () => {
      expect(service).toBeTruthy();
    });
  });

  describe('login', () => {
    const webApiUrl = ApiConst.SLASH + ApiConst.LOGIN;
    it('should return expected response', ((done: DoneFn) => {
      const loginRequestDto: LoginRequestDto = createExpectedLoginRequestDto();
      const expectedLoginResponseDto: LoginResponseDto = createExpectedLoginResponseDto();
      service.login(loginRequestDto).subscribe(
        {
          next: (loginResponseDto) => {
            expect(loginResponseDto)
              .toEqual(expectedLoginResponseDto);
            done();
          },
          error: done.fail
        }
      );
      const req = httpTestingController.expectOne(webApiUrl);
      expect(req.request.method).toEqual('POST');
      req.flush(expectedLoginResponseDto);
    }));

    it('should return 401 error when response', () => {
      const loginRequestDto: LoginRequestDto = createExpectedLoginRequestDto();
      const expectedErrorStatus = 401;
      const expectedErrorMessage = ErrorMessageConst.AUTH_ERROR;

      service.login(loginRequestDto).subscribe(
        {
          error:
            (error) => {
              expect(error).toBeNull();
              expect(error.status).toEqual(expectedErrorStatus);
              expect(error.expectedErrorMessage).toEqual(ErrorMessageConst.AUTH_ERROR);
            }
        }
      );

      const req = httpTestingController.expectOne(webApiUrl);
      expect(req.request.method).toEqual('POST');
      req.flush(expectedErrorMessage,{ status: expectedErrorStatus, statusText:ErrorMessageConst.AUTH_ERROR});
    });

  });
});

/**
 * ログインAPI実行時、APIに飛ばす値を作成するメソッド
 * @returns LoginResponseDto
 */
function createExpectedLoginRequestDto(): LoginRequestDto{
  return { email: 'test@test.com', password: 'password' };
}


/**
 * ログインAPI実行時に想定される戻り値を返すメソッド
 * @returns LoginResponseDto
 */
function createExpectedLoginResponseDto(): LoginResponseDto{
  return {
    accessToken: 'testtestteste'
  };
}
