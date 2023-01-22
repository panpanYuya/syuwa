import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, OnInit } from '@angular/core';
import { FormControl } from '@angular/forms';

import { UrlConst } from '../../constants/url-const';
import { GetTagsResponseDto } from '../models/dtos/responses/get-tags-response-dto';
import { ShowBoardResponseDto } from '../models/dtos/responses/show-board-response-dto';
import { Post } from '../models/post';
import { Tag } from '../models/tag';
import { BoardService } from '../services/board.service';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.scss']
})
export class SearchComponent implements OnInit {
  public errorMessage: string;

  public tags: Tag[];

  public postExist: boolean;

  public posts: Post[];

  public numOfDisplaiedPosts: number;


  constructor(
    private boardService: BoardService,
    private routingService: RoutingService,
  ) {
    this.errorMessage = '';
    this.tags = [];
    this.postExist = true;
    this.posts = [];
    this.numOfDisplaiedPosts = 0;
  }

  tagId = new FormControl(0);



  ngOnInit(): void {
    let getTagsResponseDto: Observable<GetTagsResponseDto> = this.boardService.getTags();
    getTagsResponseDto.subscribe({
      next:
        () => {
        getTagsResponseDto.forEach((data: any) => {
          if (data == null) {
            this.setErrorMessage(ErrorMessageConst.NO_POST);
          } else {
            for (let i = 0; i < data["tags"].length; i++){
              this.setTags(data["tags"][i]);
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

  searchPostsByTag() {
    let showBoardResponseDto: Observable<ShowBoardResponseDto> = this.boardService.searchPostsByTag(this.tagId.value ,this.numOfDisplaiedPosts);
    showBoardResponseDto.subscribe( {
      next:
        () => {
          showBoardResponseDto.forEach((data: any) => {
            if (data == null) {
              this.setErrorMessage(ErrorMessageConst.NO_POST);
              this.postExist = false;
            } else {
              this.numOfDisplaiedPosts += data["post"].length;
              for (let i = 0; i < data["post"].length; i++){
                this.setPosts(data["post"][i]);
              }
              if (data["post"].length < 10) {
                this.postExist = false;
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

  setTags(responseDto: any) {
    const tag: Tag = new Tag();
    tag.value = responseDto.id;
    tag.viewValue = responseDto.tag_name;

    this.tags.push(tag);
  }

  showPostDetail(postId: number) {
    this.routingService.transitToPath(UrlConst.SLASH + UrlConst.DRINK  + UrlConst.SLASH + UrlConst.DETAIL + UrlConst.SLASH + postId)
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

  setErrorMessage(message:string) {
    this.errorMessage = message;
  }


}
