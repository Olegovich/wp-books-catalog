/**
 * Class for getting resources of any collection
 * @constructor
 * @param {String} adminAjaxApiUrl
 * @example new CollectionResourcesApi(adminAjaxApiUrl);
 */
class CollectionResourcesApi {
  constructor(adminAjaxApiUrl = null) {
    if (!adminAjaxApiUrl) return;

    this._adminAjaxApiUrl = adminAjaxApiUrl;
  }

  /**
   * @param {Object} args
   * @returns {Object} formData
   * @public
   */
  prepareRequestData(args = null) {
    if (!args) {
      return;
    }

    const { collection } = args || '';
    const { params } = args || null;
    const searchParams = new URLSearchParams(params);
    const formData = new FormData();

    /**
     * data.filters must be a string type in 'key=value' format.
     * @type {{action: string, collection: string, filters: string}}
     */
    const data = {
      action: 'resources_api',
      collection: collection,
      filters: String(searchParams),
    };

    for (const key in data) {
      formData.append(key, data[key]);
    }

    return formData;
  }

  /**
   * @param {Object} requestData
   * @public
   */
  async makeRequest(requestData) {
    if (!requestData) return;

    const response = await fetch(this._adminAjaxApiUrl, {
      method: 'POST',
      body: requestData,
    });

    if (!response.ok) {
      throw new Error(`HTTP response code ${response.status}`);
    }

    const data = await response.json();

    return data;
  }
}
