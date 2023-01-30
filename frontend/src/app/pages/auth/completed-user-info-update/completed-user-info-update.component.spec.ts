import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CompletedUserInfoUpdateComponent } from './completed-user-info-update.component';

describe('CompletedUserInfoUpdateComponent', () => {
  let component: CompletedUserInfoUpdateComponent;
  let fixture: ComponentFixture<CompletedUserInfoUpdateComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CompletedUserInfoUpdateComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CompletedUserInfoUpdateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
