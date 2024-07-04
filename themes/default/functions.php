<?php

/**
 * Calculates the word count of the provided content.
 *
 * @param string $content The content to analyze.
 * @return int Returns the word count.
 */
function getWordCount($content) {
    return str_word_count(strip_tags($content));
}

/**
 * Estimates the reading time for the provided word count.
 *
 * @param string $content The content to analyze.
 * @return string Returns estimated reading time as a string.
 */
function getReadingTime($content) {
    // Strip HTML tags to get only the text content
    $textContent = strip_tags($content);
    // Count the number of words in the content
    $wordCount = str_word_count($textContent);
    // Calculate the reading time (in minutes) based on an average reading speed of 200 words per minute
    $readingTime = ceil($wordCount / 200);
    // Return the estimated reading time with appropriate wording
    if ($readingTime > 0) {
        // Use the translation function to translate "min read"
        return $readingTime . ' ' . translate('min_read');
    } else {
        // Use the translation function to translate "Less than a minute read"
        return translate('less_than_a_minute_read');
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => translate('year'),
        'm' => translate('month'),
        'w' => translate('week'),
        'd' => translate('day'),
        'h' => translate('hour'),
        'i' => translate('minute'),
        's' => translate('second'),
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ' . translate('ago') : translate('just_now');
}

function translate($key) {
    global $App; // Assuming $App is the instance of the App class
    return $App->translate($key);
}
