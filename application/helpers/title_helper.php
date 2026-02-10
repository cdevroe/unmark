<?php defined('BASEPATH') or exit('No direct script access allowed');

if (! function_exists('unmark_fetch_url_html')) {
    function unmark_fetch_url_html($url, $timeout = 8, $connect_timeout = 4)
    {
        if (empty($url)) {
            return '';
        }

        $user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0 Safari/537.36';

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: text/html,application/xhtml+xml'));
            if (! ini_get('open_basedir')) {
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            }

            $html = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($html !== false && $status >= 200 && $status < 400) {
                return $html;
            }
        }

        $context = stream_context_create(array(
            'http' => array(
                'method'  => 'GET',
                'timeout' => $timeout,
                'header'  => "User-Agent: {$user_agent}\r\nAccept: text/html,application/xhtml+xml\r\n",
            ),
        ));

        $html = @file_get_contents($url, false, $context);
        return ($html !== false) ? $html : '';
    }
}

if (! function_exists('unmark_extract_title')) {
    function unmark_extract_title($html)
    {
        if (empty($html)) {
            return '';
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();

        $title = '';
        $list = $dom->getElementsByTagName('title');
        if ($list->length > 0) {
            $title = trim($list->item(0)->textContent);
        }

        if (empty($title)) {
            $xpath = new DOMXPath($dom);
            $nodes = $xpath->query("//meta[@property='og:title' or @name='og:title' or @name='twitter:title']/@content");
            if ($nodes->length > 0) {
                $title = trim($nodes->item(0)->nodeValue);
            }
        }

        if (empty($title)) {
            $h1 = $dom->getElementsByTagName('h1');
            if ($h1->length > 0) {
                $title = trim($h1->item(0)->textContent);
            }
        }

        return html_entity_decode($title, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('unmark_title_from_url')) {
    function unmark_title_from_url($url)
    {
        if (empty($url)) {
            return '';
        }

        $parts = parse_url($url);
        $path = isset($parts['path']) ? trim($parts['path']) : '';
        $title = '';

        if (! empty($path) && $path !== '/') {
            $title = basename($path);
            $title = ($title === '') ? trim($path, '/') : $title;
        } elseif (isset($parts['host'])) {
            $title = $parts['host'];
        }

        return trim(urldecode($title));
    }
}
