import { CookieService } from 'ngx-cookie-service';
import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';

import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';

import { RoutingService } from '../../../common/services/routing.service';
import { UrlConst } from '../../constants/url-const';
import { Post } from '../../drink/models/post';
import { ShowUserInfoResponseDTO } from '../models/dtos/responses/show-user-info-response-dto';
import { User } from '../models/user';
import { UserInfoService } from '../services/user-info.service';

@Component({
  selector: 'app-user-page',
  templateUrl: './user-page.component.html',
  styleUrls: ['./user-page.component.scss']
})
export class UserPageComponent implements OnInit {

  public errorMessage: string;

  public followFlg: boolean;

  public posts: Post[];

  public requestServer: boolean;

  public user: User;

  public userId: number;

  public userPageFlg: boolean;

  constructor(
    private cookieService: CookieService,
    private routingService: RoutingService,
    private userInfoService: UserInfoService,
    private route: ActivatedRoute,
  ) {
    this.errorMessage = '';
    this.followFlg = false;
    this.posts = [];
    this.requestServer = false;
    this.user = new User();
    this.userPageFlg = true;
   }

  ngOnInit(): void {
    this.route.params.subscribe((params) => {
      this.userId = params['userId'];
      this.showUserInfo(this.userId);
    });
  }

  showUserInfo(userId: number) {
    let showUserInfoResponseDTO: Observable<ShowUserInfoResponseDTO> = this.userInfoService.findUserInfo(userId);
    showUserInfoResponseDTO.subscribe({
      next:
        () => {
          showUserInfoResponseDTO.forEach((data: any) => {
            this.posts = [];
            if (data == null) {
              this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
            } else {
              if(Number(this.userId) !==  Number(sessionStorage.getItem('userId'))) {
                this.userPageFlg = false;
              } else {
                this.userPageFlg = true;
              }
              this.followFlg = data['follow_flg'];
              this.setUser(data['user_info']);
              for (let i = 0; i < data['user_info']['posts'].length; i++) {
                this.setPosts(data['user_info']['posts'][i]);
              }

            }
          });
        },
      error:
        () => {
          this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
        }
    });
  }

  showPostDetail(postId: number) {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.DRINK + UrlConst.SLASH + UrlConst.DETAIL + UrlConst.SLASH + postId)
  }

  public followUser(userId: number) {
    this.requestServer = true;
    let followResult: Observable<boolean> = this.userInfoService.followUser(userId);
    followResult.subscribe({
      next:
      () => {
        followResult.forEach((data: any) => {
          if (data['result']) {
            this.followFlg = true;
          }
        });
      },
      error:
      () => {
        this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
      },
      complete:
      () => {
          this.requestServer = false;
      }
    });
  }

  public unfollowUser(userId: number) {
    this.requestServer = true;
    let followResult: Observable<boolean> = this.userInfoService.unfollowUser(userId);
    followResult.subscribe({
      next:
        () => {
          followResult.forEach((data: any) => {
            if (data['result']) {
              this.followFlg = false;
            }
          });
        },
      error:
        () => {
          this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
        },
      complete:
        () => {
            this.requestServer = false;
        }
    });
  }

  public toEditUser() {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.USER + UrlConst.SLASH + UrlConst.EDIT);
  }

  setUser(responseDto: any) {
    this.user.id = responseDto.id;
    this.user.userName = responseDto.user_name;
  }

  setPosts(responseDto: any) {
    const post: Post = new Post();
    post.id = responseDto.id;
    post.userId = responseDto.user_id;
    post.text = responseDto.text;
    post.createdAt = responseDto.created_at;
    //現状、タグと画像は一つずつの投稿にしているので、このままpush
    post.tagId = responseDto.post_tags[0].id;
    post.tagName = responseDto.post_tags[0].tag.tag_name;
    post.imageId = responseDto.images[0].id;
    post.imageUrl = responseDto.images[0].img_url;

    this.posts.push(post);
  }

  setErrorMessage(message: string) {
    this.errorMessage = message;
  }

}
