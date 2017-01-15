import { LeviAppPage } from './app.po';

describe('levi-app App', function() {
  let page: LeviAppPage;

  beforeEach(() => {
    page = new LeviAppPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
