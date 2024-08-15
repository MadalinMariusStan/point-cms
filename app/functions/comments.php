<?php

/********************************
 *  Theme functions for comments
 ********************************/

use System\input;
use System\uri;

/**
 * Checks whether the current article has comments
 *
 * @return bool|int false if invalid article, number of comments otherwise
 * @throws \Exception
 */
function has_comments()
{
    if (!$itm = Registry::get('article')) {
        return false;
    }

    if (!$comments = Registry::get('comments')) {
        $comments = Comment::where('status', '=', 'approved')
            ->where('post', '=', $itm->id)
            ->where('parent_comment_id', '=', '0')
            ->get();

        $comments = new Items($comments);

        Registry::set('comments', $comments);
    }

    return $comments->length();
}

/**
 * Checks whether the current comment has replies
 *
 * @return bool Whether the comment has replies
 * @throws \Exception
 */
function has_replies()
{
    // Assuming you have a 'comment' object in your Registry
    if (!$comment = Registry::get('comment')) {
        return false;
    }

    // Assuming you have a 'replies' property in your Comment object
    if (!$replies = $comment->replies) {
        // If 'replies' property is not already set, you can retrieve replies here
        $replies = Comment::where('parent_comment_id', '=', $comment->id)->get();

        // You may need to modify the logic to fetch replies based on your data structure

        $replies = new Items($replies);

        // Assuming you have a 'replies' property in your Comment object
        $comment->replies = $replies;
    }

    return $replies->length();
}

/**
 * Retrieves the amount of comments the current article has
 *
 * @return int number of comments
 * @throws \Exception
 */
function total_comments()
{
    if (!has_comments()) {
        return 0;
    }

    $comments = Registry::get('comments');

    return $comments->length();
}

/**
 * Loops through all comments
 *
 * @return bool
 * @throws \Exception
 */
function comments()
{
    if (!has_comments()) {
        return false;
    }

    $comments = Registry::get('comments');

    if ($result = $comments->valid()) {

        // register single comment
        Registry::set('comment', $comments->current());

        // move to next
        $comments->next();
    }

    return $result;
}

/**
 * Loops through all comment replies
 *
 * @return bool
 * @throws \Exception
 */
function comment_replies()
{
    if (!has_replies()) {
        return false;
    }

    // Assuming you have a 'comment' object in your Registry representing the current comment
    $comment = Registry::get('comment');

    // Assuming you have a 'replies' property in your Comment object
    $replies = $comment->replies;

    if ($result = $replies->valid()) {

        // register single reply
        Registry::set('comment_reply', $replies->current());

        // move to next
        $replies->next();
    }

    return $result;
}

/**
 * Retrieves the current comment ID
 *
 * @return int
 */
function comment_id()
{
    return Registry::prop('comment', 'id');
}

/**
 * Retrieves the ID of the current comment reply
 *
 * @return int Comment reply ID
 */
function comment_reply_id()
{
    return Registry::prop('comment_reply', 'id');
}

/**
 * Retrieves the current comment creation time
 *
 * @return string
 */
function comment_time()
{
    if ($time = Registry::prop('comment', 'date')) {
        return Date::format($time, 'U');
    }
}

/**
 * Retrieves the creation time of a comment reply
 *
 * @return string Creation time of the comment reply
 */
function comment_reply_time()
{
    if ($time = Registry::prop('comment_reply', 'date')) {
        return Date::format($time, 'U');
    }
}

/**
 * Retrieves the current comment creation date
 *
 * @return string
 */
function comment_date()
{
    if ($date = Registry::prop('comment', 'date')) {
        return Date::format($date);
    }
}

/**
 * Retrieves the current comment's author name
 *
 * @return string
 */
function comment_name()
{
    return Registry::prop('comment', 'name');
}

/**
 * Retrieves the name of the author of a comment reply
 *
 * @return string Author's name
 */
function comment_reply_name()
{
    return Registry::prop('comment_reply', 'name');
}

/**
 * Retrieves the current comment's author email address
 *
 * @return string
 */
function comment_email()
{
    return Registry::prop('comment', 'email');
}

/**
 * Retrieves the email address of the author of a comment reply
 *
 * @return string Author's email address
 */
function comment_reply_email()
{
    return Registry::prop('comment_reply', 'email');
}

/**
 * Retrieves the current comment text
 *
 * @return string
 */
function comment_text()
{
    return Registry::prop('comment', 'text');
}

/**
 * Retrieves the text content of a comment reply
 *
 * @return string Text content of the comment reply
 */
function comment_reply_text()
{
    return Registry::prop('comment_reply', 'text');
}

/**
 * Checks whether the comments are open for the current article
 *
 * @return bool
 */
function comments_open()
{
    return Registry::prop('article', 'comments') ? true : false;
}

/**
 * Retrieves the comment form action URI
 *
 * @return string
 * @throws \ErrorException
 * @throws \OverflowException
 */
function comment_form_url()
{
    return Uri::to(Uri::current());
}

/**
 * Retrieves the current comment form name input field
 *
 * @param string $extra (optional) additional input attributes
 *
 * @return string
 */
function comment_form_input_name($extra = '')
{
    return '<input class="form-control" name="name" id="name" type="text" ' . $extra . ' value="' . Input::previous('name') . '">';
}


function getProfilePicture($name)
{
    $name_slice = explode(' ', $name);
    $name_slice = array_filter($name_slice);
    $initials = '';

    // Check if there is at least one word in the name
    if (count($name_slice) > 0) {
        $initials .= isset($name_slice[0][0]) ? strtoupper($name_slice[0][0]) : '';

        // Check if there is a second word in the name
        if (count($name_slice) > 1) {
            $initials .= isset($name_slice[1][0]) ? strtoupper($name_slice[1][0]) : '';
        }
    }

    // Add inline styles for background and font size
    $profilePicture = '<div class="rounded-circle border" style="background-color: #fff; color: #e2e1e2; font-size: 24px; font-weight: 500!important; width: 48px; height: 48px; text-align: center; line-height: 48px;">' . $initials . '</div>';

    return $profilePicture;
}




