// Replace [PAGE_URL] and [PAGE_TITLE] dynamically using JavaScript
document.addEventListener("DOMContentLoaded", function () {
  const currentPageUrl = window.location.href;
  const currentPageTitle = document.title;

  const facebookShareLink = document.querySelector(
    'a[href*="facebook.com/sharer"]'
  );
  if (facebookShareLink) {
    facebookShareLink.href = facebookShareLink.href.replace(
      "[PAGE_URL]",
      encodeURIComponent(currentPageUrl)
    );
  }

  const twitterShareLink = document.querySelector(
    'a[href*="twitter.com/intent/tweet"]'
  );
  if (twitterShareLink) {
    twitterShareLink.href = twitterShareLink.href
      .replace("[PAGE_URL]", encodeURIComponent(currentPageUrl))
      .replace("[PAGE_TITLE]", encodeURIComponent(currentPageTitle));
  }

  const linkedinShareLink = document.querySelector(
    'a[href*="linkedin.com/sharing/shareArticle"]'
  );
  if (linkedinShareLink) {
    linkedinShareLink.href = linkedinShareLink.href
      .replace("[PAGE_URL]", encodeURIComponent(currentPageUrl))
      .replace("[PAGE_TITLE]", encodeURIComponent(currentPageTitle));
  }
});
