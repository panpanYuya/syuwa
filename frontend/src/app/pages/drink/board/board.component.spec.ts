import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BoardService } from '../services/board.service';
import { BoardComponent } from './board.component';

describe('BoardComponent', () => {
  let component: BoardComponent;
  let fixture: ComponentFixture<BoardComponent>;
  let boardServiceSpy: { getPosts: jasmine.Spy };

  beforeEach(async () => {
    boardServiceSpy = jasmine.createSpyObj('BoardService', ['getPosts']);
    await TestBed.configureTestingModule({
      declarations: [BoardComponent],
      providers: [
        {provide: BoardService, useValue: boardServiceSpy}
      ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(BoardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

    describe('#constractor', () => {
      it('should create', () => {
        expect(component).toBeTruthy();
      });
    });


});
