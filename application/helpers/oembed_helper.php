<?php

function oembed($url) {

  if (!checkOEmbedUrl($url)) { return false; } // This means oEmbed is not supported yet.
  ini_set('memory_limit', '500M');

  $endpoint = 'http://api.embed.ly/1/oembed?format=json&key=4d8ccde6777611e1a4884040d3dc5c07&url='.urlencode($url).'&maxwidth=500';

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $endpoint);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HEADER, false);
  $str = curl_exec($curl);
  curl_close($curl);

  $response = json_decode($str, true);

  if (isset($response['provider_name'])) {

    switch ($response['provider_name']) {

      case 'Flickr':
        if (isset($response['html'])) { // This is a photoset or group
          return $response['html'];
        } else { // Individual photo
          return '<a href="'.$url.'" target="_blank"><img src="'.$response['url'].'" width="'.$response['width'].'" height="'.$response['height'].'" /></a>';
        }
      break;

      /*case 'Viddler':
        return $response['html']['video']['embed_code'];
      break;*/

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

      case 'Huffduffer':
        return $response['html'].' <a href="http://huffduffer.com/" target="_blank" title="Powered by Huffduffer"><img src="'.site_url().'assets/images/poweredby_huffduffer.jpeg" /></a>';
      break;

      /*case 'Wikipedia':
        return '<div class="row-fluid"><div class="span5"><a href="'.$url.'"><img src="'.$response['thumbnail_url'].'" /></a></div><div class="span4"><p>'.$response['description'].'</p></div></div>';
      break;*/

      // YouTube, Vimeo support HTML
      default:
        if (isset($response['html'])) {
          return $response['html'];
        } else {
          return false;
        }
      break;

    } // end switch()

  } else {
      return false;
  } // end $response

return false;

} // end oembed()


// Determine the oEmbed endpoint
function checkOEmbedUrl($url) {

    $parsedUrl = parse_url($url);
    $domain = str_replace('www.','',$parsedUrl['host']);

    switch ($domain) {

      case 'youtube.com':
        return true;
      break;

      case 'm.youtube.com':
        return true;
      break;

      case 'flickr.com':
        return true;
      break;

      case 'm.flickr.com':
        return true;
      break;

      /*case 'viddler.com':
        return true;
      break;*/

      case 'vimeo.com':
        return true;
      break;

      case 'dribbble.com':
        return true;
      break;

      case 'drbl.in':
        return true;
      break;

      case 'instagr.am':
        return true;
      break;

      case 'instagram.com':
        return true;
      break;

      case 'amazon.com':
        return true;
      break;

      case 'amzn.com':
        return true;
      break;

      case 'twitpic.com':
        return true;
      break;

      case 'speakerdeck.com':
        return true;
      break;

      case 'slideshare.net':
        return true;
      break;

      case 'skitch.com':
        return true;
      break;

      case 'img.skitch.com':
        return true;
      break;

      case 'gist.github.com':
        return true;
      break;

      case 'huffduffer.com':
        return true;
      break;

      case 'soundcloud.com':
        return true;
      break;

      case 'ted.com':
        return true;
      break;

      /*case 'wikipedia.org':
        return true;
      break;

      case 'en.wikipedia.org':
        return true;
      break;*/

      default:
        return false;
      break;

    }
return false;
} // end oembedEndpoint
?>