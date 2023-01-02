import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';

import { UrlConst } from '../../constants/url-const';
import { CreateNewPostRequestDto } from '../models/dtos/requests/create-new-post-request-dto';
import { GetTagsResponseDto } from '../models/dtos/responses/get-tags-response-dto';
import { Tag } from '../models/tag';
import { BoardService } from '../services/board.service';

interface Tags {
  value: number;
  viewValue: string;
}

@Component({
  selector: 'app-post',
  templateUrl: './post.component.html',
  styleUrls: ['./post.component.scss']
})
export class PostComponent implements OnInit {

  public errorMessage?: string;

  public tags: Tag[];

  /** FileInput */
  @ViewChild('fileInputElement', { static: false }) public fileInputElement: ElementRef;

  constructor(
    private formBuilder: FormBuilder,
    private routingService: RoutingService,
    private boardService: BoardService,
  ) {
    this.tags = [];
  }
  postImage = new FormControl(null);

  postTag = new FormControl(1, [
    Validators.required,
  ]);

  comment = new FormControl('', [
    Validators.maxLength(255),
  ]);

  createForm = this.formBuilder.group({
    postImage: this.postImage,
    postTag: this.postTag,
    comment: this.comment,
  });





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

  createNewPost() {
    let createNewPostRequest: CreateNewPostRequestDto = this.CreateNewPostRequestDto();
    this.boardService.createNewPost(createNewPostRequest).subscribe({
      next:
      () => {
        this.routingService.transitToPath(UrlConst.SLASH + UrlConst.DRINK + UrlConst.SLASH + UrlConst.BOARD);
      },
      error:
        (error) => {
          if (error.status === 422) {
            return this.setErrorMessage(error.error.message);
          } else {
            return this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
          }
        }
    })
  }

  clickPostImageButton(files: FileList): void {
    if (files.length === 0) {
      return;
    }
    const mimeType = files[0].type;
    if (mimeType.match("image/*") == null) {
      this.setErrorMessage(ErrorMessageConst.FILE_FORMAT_ERROR);
      return;
    }
    this.readFile(files[0]).subscribe((result:any) => {
      this.postImage.setValue(result);
    });
  }

  /**
   * Clicks clear button
   */
  clickClearButton(): void {
    if (this.fileInputElement === undefined) {
      this.fileInputElement = null as unknown as ElementRef<any>
    }
    this.fileInputElement.nativeElement.value = '';
    this.postImage.setValue(null);
  }

  private readFile(file: File): Observable<string> {
    const observable = new Observable<string>((subscriber) => {
      const reader = new FileReader();
      reader.onload = () => {
        const content: string = reader.result as string;
        subscriber.next(content);
        subscriber.complete();
      };
      reader.readAsDataURL(file);
    });
    return observable;
  }

  private CreateNewPostRequestDto(): CreateNewPostRequestDto{
    return {
      post_image: this.postImage.value,
      post_tag: this.postTag.value,
      comment: this.comment.value,
    }
  }

  setTags(responseDto: any) {
    const tag: Tag = new Tag();
    tag.value = responseDto.id;
    tag.viewValue = responseDto.tag_name;

    this.tags.push(tag);
  }

  setErrorMessage(message:string) {
    this.errorMessage = message;
  }

}
