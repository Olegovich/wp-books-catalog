/**
 * Class for getting filter-form data
 * @constructor
 * @param {Object} container
 * @example new AjaxFilter(container);
 */
class AjaxFilter {
  constructor(container = null) {
    if (container?.nodeType !== Node.ELEMENT_NODE) return;

    this._initConstants();
    this._container = container;
    this._form = this._container.querySelector(this.CONSTANTS.SELECTORS.FORM);
    this._initListeners();
  }

  /**
   * @private
   */
  _initConstants() {
    this.CONSTANTS = {
      SELECTORS: {
        FORM: '.ajax-filter__form',
      },
    };
  }

  /**
   * @private
   */
  _initListeners() {
    this._form.addEventListener('change', this._handleFormChange.bind(this));
  }

  /**
   * @private
   */
  _handleFormChange() {
    this._form.dispatchEvent(
      new CustomEvent('ajax-filtering', {
        bubbles: true,
        cancelable: true,
        detail: {
          filterParams: Object.fromEntries(new FormData(this._form)),
        },
      }),
    );
  }
}
