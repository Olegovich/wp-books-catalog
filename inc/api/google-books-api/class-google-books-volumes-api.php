<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Make requests to Google Books Volumes API by unique search identifier ("isbn" or "volume_id").
 * @since 1.0.0
 * @package Blogus Child theme
 * @subpackage inc/api/
 * @author Olegovich
 */
class Google_Books_Volumes_API
{
    /**
     * @var string
     */
    private const API_RESULT_TRANSIENT = 'google_books_volumes_api_result';

    /**
     * @var string
     */
    private const API_URL = 'https://www.googleapis.com/books/v1/volumes';

    /**
     * @var array
     */
    private array $apiEndpoints;

    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var array
     */
    private array $searchId;

    /**
     * Google_Books_API constructor.
     * @param array $searchId Search identifier ("isbn" or "volume_id")
     */
    public function __construct(array $searchId = [])
    {
        $this->apiKey = trim(get_field('google_books_api_key', 'option'));
        $this->searchId = $searchId;
        $this->setApiEndpoints();
    }

    /**
     * @link https://developers.google.com/books/docs/v1/reference/volumes
     * @return void
     */
    private function setApiEndpoints(): void
    {
        $this->apiEndpoints = [
            'LIST' => '',
            'GET' => '',
        ];
        $searchKey = key($this->searchId);

        switch ($searchKey) {
            case 'isbn':
                $this->apiEndpoints['LIST'] = self::API_URL . '?q=isbn:' . $this->searchId[$searchKey] . '&key=' . $this->apiKey;
                break;
            case 'volume_id':
                $this->apiEndpoints['GET'] = self::API_URL . '/' . $this->searchId[$searchKey] . '&key=' . $this->apiKey;
                break;
        }
    }

    /**
     * @return string
     */
    private function setRequestUrl(): string
    {
        $url = '';
        $searchKey = key($this->searchId);

        switch ($searchKey) {
            case 'isbn':
                $url = $this->apiEndpoints['LIST'];
                break;
            case 'volume_id':
                $url = $this->apiEndpoints['GET'];
                break;
        }

        return $url;
    }

    /**
     * @param string $name
     * @return void
     */
    private function clearTransients(string $name = ''): void
    {
        if (empty($name)) {
            return;
        }

        delete_transient($name);
    }

    /**
     * Make requests with cached results.
     *
     * @example To search by "isbn":
     * $api = new Google_Books_Volumes_API(['isbn' => $isbn]);
     * $apiResponseData = $api->makeRequest();
     *
     * @example To search by "volume_id":
     * $api = new Google_Books_Volumes_API(['volume_id' => $volumeId]);
     * $apiResponseData = $api->makeRequest();
     *
     * @example To clear transients before new request:
     * $api = new Google_Books_Volumes_API(['isbn' => $isbn]);
     * $apiResponseData = $api->makeRequest('GET', [], [], true);
     *
     * @param string $method
     * @param array $headers
     * @param array $body
     * @param bool $clearTransients
     * @return array|null
     */
    public function makeRequest(
        string $method = 'GET',
        array $headers = [],
        array $body = [],
        bool $clearTransients = false
    ): ?array
    {
        try {
            if (empty($this->apiKey)) {
                throw new RuntimeException('Request denied: empty API key');
            }

            if (empty($this->searchId)) {
                throw new RuntimeException('Request denied: empty search identifier');
            }

            if (!array_key_exists(key($this->searchId), $this->searchId)) {
                throw new RuntimeException('Request denied: no such search identifier exist. Should be "isbn" or "volume_id"');
            }

            if (empty($method)) {
                throw new RuntimeException('Request denied: empty $method parameter');
            }

            // Create unique name for transient in each request
            $transientName = self::API_RESULT_TRANSIENT . '_id_' . $this->searchId[key($this->searchId)];
            $transientExpiration = 24 * HOUR_IN_SECONDS;

            // Remove transients before setting new ones
            if ($clearTransients) {
                $this->clearTransients($transientName);
            }

            $resultTransient = get_transient($transientName);

            // Only strict comparison with false
            if (false === $resultTransient) {
                $response = wp_remote_request(
                    $this->setRequestUrl(),
                    [
                        'body' => $body,
                        'headers' => $headers,
                        'method' => $method,
                        'timeout' => 5,
                        'redirection' => 0,
                        'httpversion' => '1.1',
                    ],
                );
                $responseCode = wp_remote_retrieve_response_code($response);

                if (is_wp_error($response)) {
                    throw new RuntimeException($response->get_error_message());
                }

                if ($responseCode !== 200) {
                    throw new RuntimeException('cURL response code: ' . $responseCode);
                }

                $result = json_decode(wp_remote_retrieve_body($response), true, 512, JSON_THROW_ON_ERROR);
                $resultData = !empty($result['items']) && $result['items'][0] ? $result['items'][0] : null;

                // Create cache of response body
                if ($resultData) {
                    set_transient($transientName, $resultData, $transientExpiration);
                }

                return $resultData;
            }

            return $resultTransient;
        } catch (Exception $e) {
            echo $e->getMessage(); // for development
//            error_log($e->getMessage()); // for production

            return null;
        }
    }
}
