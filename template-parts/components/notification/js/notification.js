/**
 * Class for handling notifications
 * @constructor
 * @param {Object} container
 * @example new Notification(container);
 */
class Notification {
  constructor(container = null) {
    if (container?.nodeType !== Node.ELEMENT_NODE) return;

    this._container = container;
  }

  /**
   * @public
   */
  hide() {
    this._container.hidden = true;
  }

  /**
   * @param {String} message
   * @public
   */
  show(message = null) {
    if (!message) return;

    this._container.innerText = message;
    this._container.hidden = false;
  }
}
