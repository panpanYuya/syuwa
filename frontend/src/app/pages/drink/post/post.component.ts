import { Observable } from 'rxjs';
import { ErrorMessageConst } from 'src/app/common/constants/error-message-const';
import { RoutingService } from 'src/app/common/services/routing.service';

import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, Validators } from '@angular/forms';

import { UrlConst } from '../../constants/url-const';
import { CreateNewPostRequestDto } from '../models/dtos/requests/create-new-post-request-dto';
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

  /** FileInput */
  @ViewChild('fileInputElement', { static: false }) public fileInputElement: ElementRef;

  constructor(
    private formBuilder: FormBuilder,
    private routingService: RoutingService,
    private boardService: BoardService,
  ) {
  }

  postImage = new FormControl(null);

  //TODO 初期値はtagsで取ってきた値を格納できるようにする
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

  tags: Tags[] = [
    {value: 1, viewValue: 'ワイン' },
    {value: 2, viewValue: '日本酒' },
    {value: 3, viewValue: 'ウィスキー' },
  ];



  ngOnInit(): void {
  }

  createNewPost() {
    let createNewPostRequest: CreateNewPostRequestDto = this.CreateNewPostRequestDto();
    this.boardService.createNewPost(createNewPostRequest).subscribe({
      next:
      () => {
        this.routingService.transitToPath(UrlConst.SLASH + UrlConst.DRINK + UrlConst.SLASH + UrlConst.BOARD);
      },
      error:
      () => {
        this.setErrorMessage(ErrorMessageConst.SERVER_ERROR);
      }
    })
  }

  clickPostImageButton(files: FileList): void {
    if (files.length === 0) {
      return;
    }
    const mimeType = files[0].type;
    if (mimeType.match("image/*") == null) {
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


  setErrorMessage(message:string) {
    this.errorMessage = message;
  }
}
