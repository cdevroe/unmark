<?php

function oembed($url, $key) {

  if (empty($key) || empty($url) || checkOEmbedUrl($url) === false) {
    return false;
  }

  $ch = curl_init('http://api.embed.ly/1/oembed?format=json&key=' . urlencode($key) . '&url='. urlencode($url) . '&maxwidth=1200');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  $response = curl_exec($ch);
  $info     = curl_getinfo($ch);
  curl_close($ch);

  if (! isset($info['http_code']) || $info['http_code'] != 200) {
    return false;
  }

  $response = json_decode($response, true);

  if (isset($response['provider_name'])) {

    // Make sure keys from response exist, if not add them as blank so they don't fail
    foreach (array('url', 'width', 'height', 'author_url', 'author_name', 'description') as $key) {
      $response[$key] = (isset($response[$key])) ? $response[$key] : '';
    }


    switch ($response['provider_name']) {

      case 'Flickr':
        // This is a photoset or group
        if (isset($response['html'])) {
          return $response['html'];
        }
        // Individual photo
        else {
          return '<a href="'.$url.'" target="_blank"><img src="'.$response['url'].'" width="'.$response['width'].'" height="'.$response['height'].'" /></a>';
        }
      break;

      case 'Dribbble':
        return  '<a href="'.$url.'" target="_blank"><img src="'.$response['url'].'" width="'.$response['width'].'" height="'.$response['height'].'" /></a>';
      break;

      case 'Instagram':
        return  '<a href="'.$url.'" target="_blank"><img src="'.$response['url'].'" width="'.$response['width'].'" height="'.$response['height'].'" /></a>';
      break;

      case 'Twitpic':
        return  '<a href="'.$url.'" target="_blank"><img src="'.$response['url'].'" width="'.$response['width'].'" height="'.$response['height'].'" /></a>';
      break;

      case 'skitch':
        return  '<a href="'.$url.'" target="_blank"><img src="'.$response['url'].'" width="'.$response['width'].'" height="'.$response['height'].'" /></a>';
      break;

      case 'YouTube' :
        return $response['html'] . '<div class="videoInfo"><span class="viLeft">by <a target="_blank" href="'.$response['author_url'].'">'.$response['author_name'].'</a></span><p>'.$response['description'].'</p></div>';
      break;

      // Vimeo, other video sites and generic HTML
      default:
        return (isset($response['html'])) ? $response['html'] : false;
      break;

    }

  }

return false;

}

function checkOEmbedUrl($url) {

    $domain = parse_url($url);
    $domain = str_replace('www.', '', $domain['host']);
    $hosts  = array(
      'youtube.com', 'm.youtube.com', 'flickr.com', 'm.flickr.com', 'vimeo.com', 'dribbble.com', 'drbl.in', 'instagr.am', 'instagram.com', 'amazon.com', 'amzn.com', 'twitpic.com',
      'speakerdeck.com', 'slideshare.net', 'skitch.com', 'img.skitch.com', 'gist.github.com', 'huffduffer.com', 'soundcloud.com', 'ted.com'
    );

    return (in_array($domain, $hosts)) ? true : false;
}