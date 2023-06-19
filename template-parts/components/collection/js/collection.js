/**
 * Class for ajax filtering (without pagination, preloader and history-api)
 * @constructor
 * @param {Object} container
 * @param {String} adminAjaxApiUrl
 * @example new Collection(container, adminAjaxApiUrl);
 */
class Collection {
  constructor(container = null, adminAjaxApiUrl = null) {
    if (container?.nodeType !== Node.ELEMENT_NODE || !adminAjaxApiUrl) return;

    this._initConstants();
    this._container = container;
    this._cardsList = this._container.querySelector(this.CONSTANTS.SELECTORS.CARDS_LIST);

    if (!this._cardsList) return;

    this._initProperties(adminAjaxApiUrl);
    this._initElements();
    this._initListeners();
  }

  /**
   * @private
   */
  _initConstants() {
    this.CONSTANTS = {
      CLASSES: {
        CARDS_ITEM: 'ajax-cards-item',
        PROCESSING: 'ajax-cards-item--processing',
      },
      SELECTORS: {
        CARDS_LIST: '.ajax-cards-list',
        CARDS_ITEM: '.ajax-cards-item',
        FILTER: '.ajax-filter',
        NOTIFICATION: '.notification',
      },
      ATTRIBUTES: {
        COLLECTION: 'data-collection',
        CARDS_TEXT_EMPTY: 'data-cards-text-empty',
        CARDS_TEXT_ERROR: 'data-cards-text-error',
      },
    };
  }

  /**
   * @param {String} adminAjaxApiUrl
   * @private
   */
  _initProperties(adminAjaxApiUrl) {
    this._resourcesApi = new CollectionResourcesApi(adminAjaxApiUrl);
    this._requestData = null;
    this._collection = this._container.getAttribute(this.CONSTANTS.ATTRIBUTES.COLLECTION) || '';
    this._cardsTextEmpty = this._container.getAttribute(this.CONSTANTS.ATTRIBUTES.CARDS_TEXT_EMPTY) || '';
    this._cardsTextError = this._container.getAttribute(this.CONSTANTS.ATTRIBUTES.CARDS_TEXT_ERROR) || '';
  }

  /**
   * @private
   */
  _initElements() {
    this._filterElement = document.querySelector(this.CONSTANTS.SELECTORS.FILTER);
    this._filter = this._filterElement ? new AjaxFilter(this._filterElement) : null;
    this._notificationElement = this._container.querySelector(this.CONSTANTS.SELECTORS.NOTIFICATION);
    this._notification = this._notificationElement ? new Notification(this._notificationElement) : null;
  }

  /**
   * @private
   */
  _initListeners() {
    document.addEventListener('ajax-filtering', this._loadData.bind(this));
  }

  /**
   * @param {CustomEvent} event
   * @private
   */
  _loadData(event = null) {
    if (!event) return;

    if (event.type === 'ajax-filtering') {
      this._setRequestData(event.detail?.filterParams);
      this._setProcessingState();
    }

    this._doActionsBeforeRequest();

    this._resourcesApi.makeRequest(this._requestData)
      .then((data) => {
        const { cards } = data || null;

        if (cards) {
          const html = new DOMParser().parseFromString(String(cards), 'text/html');

          if (event.type === 'ajax-filtering') {
            this._renderCards(html);
          }
        } else {
          this._doActionsOnFailure(this._cardsTextEmpty);
        }
      })
      .catch((error) => {
        this._doActionsOnFailure(this._cardsTextError);
        console.error(error);
      })
      .finally(() => {
        this._doActionsAfterRequest();
      });
  }

  /**
   * @param {Object} html
   * @private
   */
  _renderCards(html = null) {
    const newCards = html?.querySelectorAll(this.CONSTANTS.SELECTORS.CARDS_ITEM) || [];

    if (!newCards.length) {
      this._doActionsOnFailure(this._cardsTextEmpty);

      return;
    }

    this._removeCards();

    for (let i = 0; i < newCards.length; i++) {
      this._cardsList.insertAdjacentElement('beforeend', newCards[i]);
    }
  }

  /**
   * @param {Object} filterParams
   * @private
   */
  _setRequestData(filterParams = null) {
    this._requestData = this._resourcesApi.prepareRequestData({
      collection: this._collection,
      params: filterParams,
    });
  }

  /**
   * @private
   */
  _setProcessingState() {
    this._cardsList.classList.add(this.CONSTANTS.CLASSES.PROCESSING);
  }

  /**
   * @private
   */
  _removeProcessingState() {
    this._cardsList.classList.remove(this.CONSTANTS.CLASSES.PROCESSING);
  }

  /**
   * @private
   */
  _removeCards() {
    Array.from(this._cardsList.getElementsByClassName(this.CONSTANTS.CLASSES.CARDS_ITEM)).forEach(
      (item) => item.remove()
    );
  }

  /**
   * @private
   */
  _doActionsBeforeRequest() {
    this._notification?.hide();
  }

  /**
   * @private
   */
  _doActionsAfterRequest() {
    this._removeProcessingState();
  }

  /**
   * @param {String} message
   * @private
   */
  _doActionsOnFailure(message = null) {
    this._removeCards();
    this._notification?.show(message);
  }
}
