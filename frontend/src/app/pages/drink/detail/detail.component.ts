import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

import { UrlConst } from '../../constants/url-const';
import { GetPostDetailResponseDTO } from '../models/dtos/responses/get-post-detail-response-dto';
import { PostDetail } from '../models/post-detail';
import { BoardService } from '../services/board.service';

@Component({
  selector: 'app-detail',
  templateUrl: './detail.component.html',
  styleUrls: ['./detail.component.scss']
})
export class DetailComponent implements OnInit {
  public errorMessage:string;

  public postDetail: PostDetail;
  public postId: number;

  constructor(
    private route: ActivatedRoute,
    private boardServcie: BoardService,
    private routingService: RoutingService
  ) {
    this.postDetail = new PostDetail();
  }

  ngOnInit(): void {
    this.route.params.subscribe((params) => {
      this.postId = params['postId'];
      this.showDetail(this.postId);
    });
  }

  showDetail(postId :number) {
    let getPostDetailResponseDTO: Observable<GetPostDetailResponseDTO> = this.boardServcie.getPostDetail(postId);
    getPostDetailResponseDTO.subscribe({
      next:
        () => {
          getPostDetailResponseDTO.forEach((data: any) => {
            if (data == null) {
              this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
            } else {
              this.setPosts(data["post"]);
            }
          });
        },
      error:
        () => {
          this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
        }
    });
  }

  public toUserPage(userId:number) {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.AUTH + UrlConst.SLASH + UrlConst.USER + UrlConst.SLASH + UrlConst.PAGE + UrlConst.SLASH + userId);
  }


  setPosts(responseDto: any) {

    this.postDetail.userId = responseDto.user_id;
    this.postDetail.userName = responseDto.user.user_name;
    this.postDetail.postId = responseDto.id;
    this.postDetail.text = responseDto.text;
    this.postDetail.createdAt = responseDto.created_at;
    //現状、タグと画像は一つずつの投稿にしているので、このままpush
    this.postDetail.tagName = responseDto.post_tags[0].tag.tag_name;
    this.postDetail.imageUrl = responseDto.images[0].img_url;
  }


  setErrorMessage(message:string) {
    this.errorMessage = message;
  }
}
