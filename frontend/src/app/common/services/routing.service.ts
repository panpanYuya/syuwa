import { UrlConst } from 'src/app/pages/constants/url-const';

import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class RoutingService {

  constructor(
    private router: Router,
  ) { }

  public transitToPath(path:string){
    this.router.navigate([UrlConst.SLASH + path]);
  }
}
