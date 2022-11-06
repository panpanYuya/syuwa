import { ComponentFixture } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

export class HtmlElementUtility {

  /**
   *
   * @param fixture
   * @param querySelector
   * @param setValue
   */
  static setValueToHTMLInputElement<T>(fixture: ComponentFixture<T>, querySelector: string, setValue: any) {
    const htmlInputElement: HTMLInputElement = fixture.debugElement.query(By.css(querySelector)).nativeElement;
    htmlInputElement.value = setValue;
    htmlInputElement.dispatchEvent(new Event('input'));
    fixture.detectChanges();
  }
}
