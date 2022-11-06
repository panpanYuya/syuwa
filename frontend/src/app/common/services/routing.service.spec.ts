import { UrlConst } from 'src/app/pages/constants/url-const';

import { TestBed } from '@angular/core/testing';
import { Router } from '@angular/router';

import { RoutingService } from './routing.service';

describe('RoutingService', () => {
  let service: RoutingService;
  let router: Router;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(RoutingService);
    router = TestBed.inject(Router);
  });

  describe('#constractor', () => {
    it('should be created', () => {
      expect(service).toBeTruthy();
    });
  });

  describe('transitToPath', () => {
    it('should be transitToPath', () => {
      spyOn(router, 'navigate');
      service.transitToPath(UrlConst.PATH_DRINK + UrlConst.SLASH + UrlConst.PATH_SHOW);
      expect(router.navigate).toHaveBeenCalledWith([UrlConst.SLASH + UrlConst.PATH_DRINK + UrlConst.SLASH + UrlConst.PATH_SHOW]);
    });
  });

});
