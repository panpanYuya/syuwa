import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SendPassResetEmailComponent } from './send-pass-reset-email.component';

describe('SendPassResetEmailComponent', () => {
  let component: SendPassResetEmailComponent;
  let fixture: ComponentFixture<SendPassResetEmailComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SendPassResetEmailComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SendPassResetEmailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
