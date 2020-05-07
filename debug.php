<?php

function log_request($wp)
{
    if (!is_local() && !defined('LOG_REQUEST')) {
        return;
    }
    if (is_admin()) {
        return;
    }
    logr('wp->request', $wp->request, 'wp->matched_rule', $wp->matched_rule, 'wp->matched_query', $wp->matched_query);

    /*
    logr("wp->query_vars");
    logr($wp->query_vars);
    */
    // logr($wp);

    if (!empty($_POST)) {
        logr('_POST', $_POST);
    }
    $wp->query_vars['debug'] = true;
}
if (defined('LOG_REQUEST')) {
    add_action('parse_request', 'log_request', 9999);
}

function basics_posts_request($request, $wp_query)
{
    if (isset($wp_query->query['debug']) or isset($_GET['DEBUG'])) {
        logr($wp_query->query, $request);
    }

    if ((isset($wp_query->query['debug']) && 'xmpr' === $wp_query->query['debug'])) {
        xmpr($wp_query->query, $request);
        // xmpr(get_caller());
    }

    return $request;
}
add_filter('posts_request', 'basics_posts_request', 99, 2);

function log_wp_redirect($location, $status)
{
    logr("wp_redirect({$location}, {$status})");

    return $location;
}
if (defined('LOG_REDIRECT')) {
    add_filter('wp_redirect', 'log_wp_redirect', 9999, 2);
}

add_filter('template_redirect', 'mahi_local_image_404_override', 2);
function mahi_local_image_404_override()
{
    global $wp_query;

    if ($wp_query->is_404) {
        if (is_dev()) {
            if (preg_match('#content/.*\\.(gif|jpg|jpeg|png)$#', $_SERVER['REQUEST_URI'])) {
                header('Status: 404');
                exit();
            }
        }
    }
}
