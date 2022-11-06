import { ApiConst } from 'src/app/common/constants/api-const';

import { HttpClient } from '@angular/common/http';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { TestBed } from '@angular/core/testing';

import { ShowBoardResponseDto } from '../models/dtos/responses/show-board-response-dto';
import { BoardService } from './board.service';

describe('BoardService', () => {
  let service: BoardService;
  let httpClient: HttpClient;
  let httpTestingController: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ HttpClientTestingModule ]
    });
    service = TestBed.inject(BoardService);
    httpTestingController = TestBed.inject(HttpTestingController);
    httpClient = TestBed.inject(HttpClient);
  });

  afterEach(() => {
    httpTestingController.verify();
  });

  describe('BoardService', () => {
    it('should be created', () => {
      expect(service).toBeTruthy();
    });
  });

  describe('getPost', () => {
    const webApiUrl = ApiConst.SLASH + ApiConst.API + ApiConst.SLASH + ApiConst.DRINK + ApiConst.SLASH + ApiConst.SHOW
    it('should return expected response', ((done: DoneFn) => {
      const expectedBoardResponseDto: ShowBoardResponseDto = createExpectedBoardResponseDto();
      service.getPosts().subscribe({
        next: (postResponseDto) => {
          expect(postResponseDto).toEqual(expectedBoardResponseDto);
          done();
        },
        error: done.fail
      });
      const req = httpTestingController.expectOne(webApiUrl);
      expect(req.request.method).toEqual('GET');
      req.flush(expectedBoardResponseDto);
    }));

    //TODO 機能作成後に
    // it('should return null when response is 404 Not Found', () => {
    //   const errorStatus = 404;
    //   const errorMessage = '404 Not Found';
    //   service.getPosts().subscribe({
    //     error: (error) => {
    //       expect(error).toBeNull();
    //     }
    //   });
    //   const req = httpTestingController.expectOne(webApiUrl);
    //   expect(req.request.method).toEqual('GET');
    //   req.flush(errorMessage, { status: errorStatus, statusText: errorMessage });
    // });

  });


});

function createExpectedBoardResponseDto(): ShowBoardResponseDto{
  return {
    id: 1,
    user_id: 1,
    text: 'この日本酒は純米大吟醸のお酒です。',
    created_at: new Date(),
    updated_at: new Date(),
    post_tags: {
      id: 1,
      tag_id: 1,
      created_at: new Date(),
      updated_at: new Date(),
      tag: {
        id: 1,
        tag_name: '日本酒',
        created_at: new Date(),
        updated_at: new Date(),
      },
    },
    imagetag: {
      id: 1,
      post_id: 1,
      img_url: '/assets/images/wine.png',
      created_at: new Date(),
      updated_at: new Date(),
    }
  };
}
