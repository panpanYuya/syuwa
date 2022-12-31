import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, HostListener, OnInit } from '@angular/core';

import { NumberConst } from '../../constants/number-const';
import { UrlConst } from '../../constants/url-const';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.scss']
})
export class FooterComponent {

  constructor(private routingService: RoutingService) { }

  public navbarFlg: boolean;

  public toBoard() {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.DRINK + UrlConst.SLASH + UrlConst.BOARD);
  }
}
