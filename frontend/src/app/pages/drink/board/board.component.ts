import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';

import { Component, OnInit } from '@angular/core';

import { ShowBoardResponseDto } from '../models/dtos/responses/show-board-response-dto';
import { Post } from '../models/post';
import { BoardService } from '../services/board.service';

@Component({
  selector: 'app-board',
  templateUrl: './board.component.html',
  styleUrls: ['./board.component.scss']
})
export class BoardComponent implements OnInit {
  public errorMessage:string;

  public posts: Post[];



  constructor(private boardService: BoardService) {
    this.posts = [];
    this.errorMessage = '';
  }

  ngOnInit(): void {
    this.showBoard();
  }

  showBoard() {
    let showBoardResponseDto: Observable<ShowBoardResponseDto> = this.boardService.getPosts();
    showBoardResponseDto.subscribe( {
      next:
        () => {
          showBoardResponseDto.forEach((data: any) => {
            if (data == null) {
              this.setErrorMessage(ErrorMessageConst.NO_POST);
            } else {
              for (let i = 0; i < data["post"].length; i++){
                this.setPosts(data["post"][i]);
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
