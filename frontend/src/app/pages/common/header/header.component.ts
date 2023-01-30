import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, OnInit } from '@angular/core';

import { UrlConst } from '../../constants/url-const';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  constructor(private routingService: RoutingService) { }
  ngOnInit(): void {
  }

  public toAddPost() {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.DRINK + UrlConst.SLASH + UrlConst.ADD);
  }

}
