<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Prepare books item to output.
 * @since 1.0.0
 * @package Blogus Child theme
 * @subpackage template-parts/components/books-list
 * @author Olegovich
 *
 * @param WP_Post $post
 * @return string|null
 */
function prepareBooksItem(WP_Post $post): ?string
{
    if (!$post) {
        return null;
    }

    // Define variables
    $output = '';

    $postId = $post->ID;
    $postLink = get_permalink($postId);
    $postTitle = get_the_title();
    $postGenres = get_the_terms($postId, 'book_genre');
    $postIsbn = get_field('book_isbn', $postId);

    // Data from Google Books API
    $api = new Google_Books_Volumes_API(['isbn' => $postIsbn]);
    $apiResponseData = $api->makeRequest();

    $postAuthor = !empty($apiResponseData['volumeInfo']) && !empty($apiResponseData['volumeInfo']['authors'])
        ? implode(', ', $apiResponseData['volumeInfo']['authors'])
        : null;

    $postPublishedDate = !empty($apiResponseData['volumeInfo']) && !empty($apiResponseData['volumeInfo']['publishedDate'])
        ? $apiResponseData['volumeInfo']['publishedDate']
        : null;

    $postAuthorToOutput = $postAuthor && $postPublishedDate
        ? $postAuthor . ' · ' . date('Y', strtotime($postPublishedDate))
        : $postAuthor;

    $postPublisher = !empty($apiResponseData['volumeInfo']) && !empty($apiResponseData['volumeInfo']['publisher'])
        ? $apiResponseData['volumeInfo']['publisher']
        : null;

    $postThumbnail = !empty($apiResponseData['volumeInfo'])
    && !empty($apiResponseData['volumeInfo']['imageLinks'])
    && !empty($apiResponseData['volumeInfo']['imageLinks']['thumbnail'])
        ? $apiResponseData['volumeInfo']['imageLinks']['thumbnail']
        : null;

    // Add html to output
    $output .= '<li class="books-list__item ajax-cards-item">';
    $output .= '<article class="books-list__item-card book-card">';

    // Text wrapper - START
    $output .= '<div class="book-card__content">';

    if ($postGenres) {
        $output .= '<div class="bs-blog-category">';

        foreach ($postGenres as $term) {
            $termUrl = get_term_link($term);
            $termTitle = $term->name;

            if ($termUrl && $termTitle) {
                $output .= '<a class="blogus-categories category-color-1" href="' . $termUrl . '">' . $termTitle . '</a>';
            }
        }

        $output .= '</div>';
    }

    if ($postLink && $postTitle) {
        $output .= '<h3 class="book-card__title">';
        $output .= '<a class="book-card__link" href="' . $postLink . '">' . $postTitle . '</a>';
        $output .= '</h3>';
    }

    if ($postAuthor) {
        $output .= '<p class="book-card__info">' . $postAuthorToOutput . '</p>';
    }

    if ($postPublisher) {
        $output .= '<p class="book-card__info"><i class="fa fa-feather"></i>' . str_replace('"', '', $postPublisher) . '</p>';
    }

    $output .= '</div>';
    // Text wrapper - END

    // Image wrapper
    if ($postThumbnail) {
        $output .= '<div class="book-card__img-wrapper">';
        $output .= '<img class="book-card__img" src="' . $postThumbnail . '" alt="">';
        $output .= '</div>';
    }

    $output .= '</article>';
    $output .= '</li>';

    return $output;
}
