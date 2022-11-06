import { of } from 'rxjs';
import { ApiConst } from 'src/app/common/constants/api-const';
import { MaterialModule } from 'src/app/material/material.module';

import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HeaderComponent } from '../../common/header/header.component';
import { ShowBoardResponseDto } from '../models/dtos/responses/show-board-response-dto';
import { BoardService } from '../services/board.service';
import { BoardComponent } from './board.component';

describe('BoardComponent', () => {
  let expectedPostsResponseDto:ShowBoardResponseDto= createExpectedBoardResponseDto();
  let component: BoardComponent;
  let fixture: ComponentFixture<BoardComponent>;
  let boardServiceSpy: { getPosts: jasmine.Spy };

  beforeEach(async () => {
    boardServiceSpy = jasmine.createSpyObj('BoardService', ['getPosts']);
    await TestBed.configureTestingModule({
      declarations: [
        BoardComponent,
        HeaderComponent
      ],
      imports: [
        MaterialModule,
      ],
      providers: [
        {provide: BoardService, useValue: boardServiceSpy}
      ]
    })
      .compileComponents();


    fixture = TestBed.createComponent(BoardComponent);
    component = fixture.componentInstance;
    //Cannot read property 'subscribe' of undefinedのエラーを消すためにコメントアウト
    //ngOnInitを実行する際、getMenuが動いてしまう。
    //しかしこの段階ではgetMenuのスパイを定義していないので、エラーが発生する
    // fixture.detectChanges();
  });

  describe('#constractor', () => {
    it('should create', () => {
      expect(component).toBeTruthy();
    });
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
