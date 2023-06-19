(() => {
  const SELECTORS = {
    COLLECTION: '[data-collection="books"]',
  };
  const adminAjaxApi = typeof ADMIN_AJAX_API !== 'undefined' ? ADMIN_AJAX_API : { URL: null };

  document.addEventListener('DOMContentLoaded', () => {
    const collectionElement = document.querySelector(SELECTORS.COLLECTION);
    const collection = collectionElement ? new Collection(collectionElement, adminAjaxApi.URL) : null;
  });
})();
