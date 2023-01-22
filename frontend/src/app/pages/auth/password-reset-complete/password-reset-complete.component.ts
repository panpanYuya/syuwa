import { filter, interval, Observable } from 'rxjs';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { UrlConst } from '../../constants/url-const';

@Component({
  selector: 'app-password-reset-complete',
  templateUrl: './password-reset-complete.component.html',
  styleUrls: ['./password-reset-complete.component.scss']
})
export class PasswordResetCompleteComponent implements OnInit {

  constructor(private routingService: RoutingService) {}

  ngOnInit(): void {
  }

  public toLogin() {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.LOGIN)
  }


}
