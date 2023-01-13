import { map, Observable } from 'rxjs';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, RouterStateSnapshot, UrlTree } from '@angular/router';

import { LoginService } from '../auth/services/login.service';
import { UrlConst } from '../constants/url-const';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  constructor(
    private loginService: LoginService,
    private routingService: RoutingService,
  ) {}

    /**
   * Determines whether activate can
   * @returns Whether activate can
   */
  canActivate(): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    return this.loginService.checkLogin().pipe(
      map((result) => {
        if (!result || result === null) {
          return this.cantActivate();
        }
        return true;
      })
    );
  }

  private cantActivate(): boolean {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.LOGIN)
    return false;
  }


}
