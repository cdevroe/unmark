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
      
      case 'Amazon':
        return '<div id="embedly_amzn_24496301" class="embedly_amzn"> <style type="text/css"> #embedly_amzn_24496301 {line-height:1.5;} #embedly_amzn_24496301 * {color:#000000;background:#FFFFFF none repeat scroll 0 0; vertical-align:baseline; margin:0; padding:0; border: medium none; font-family:verdana,arial,helvetica,sans-serif;} #embedly_amzn_24496301 TABLE {vertical-align:middle;border-collapse:separate;border-spacing:0;} #embedly_amzn_24496301 TD {vertical-align:top;text-align:left;} #embedly_amzn_24496301 .embedly_amzn_out_bdr {border:1px solid #EEEEEE;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;} #embedly_amzn_24496301 .embedly_amzn_in_bdr {border:1px solid #999999; padding:10px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;} #embedly_amzn_24496301 .embedly_amzn_img {float:left; margin:0 20px 0 0;} #embedly_amzn_24496301 .embedly_amzn_desc {width:100%;} #embedly_amzn_24496301 .embedly_amzn_desc H3{margin:5px 0;font-family:Arial,Helvetica,sans-serif;} #embedly_amzn_24496301 .embedly_amzn_desc H3 A{color:#000000;font-size:18px;font-weight:normal;text-decoration:none;line-height:26px;} #embedly_amzn_24496301 .embedly_amzn_desc .subhead{display:block;margin:0 0 5px;font-size:11px;} #embedly_amzn_24496301 .embedly_amzn_desc HR{border-top:1px dashed #999999;color:#FFFFFF;height:1px;margin:6px 0 3px;} #embedly_amzn_24496301 .embedly_amzn_desc .em_more {margin:0 10px 0 0;font-size:11px;} #embedly_amzn_24496301 .embedly_amzn_desc A.em_more {color:#003399} #embedly_amzn_24496301 .embedly_amzn_desc A.em_more:hover {color:#CC6600} #embedly_amzn_24496301 .embedly_amzn_desc .buying TD.label{width:70px;color:#666666;font-size:11px;text-align:right;vertical-align:middle;white-space:nowrap;margin:0 5px 0;} #embedly_amzn_24496301 .embedly_amzn_desc .buying TD.pricelabel{padding:3px 0 0;} #embedly_amzn_24496301 .embedly_amzn_desc .buying TD.listprice{padding:0 0 0 5px;font-family:arial,verdana,helvetica,sans-serif;text-decoration:line-through;font-size:13px;} #embedly_amzn_24496301 .embedly_amzn_desc .buying TD.price{padding:0 0 0 5px;color:#990000;font-size:20px;font-weight:normal;letter-spacing:-1px;} #embedly_amzn_24496301 .embedly_amzn_desc .buying TD.saved{padding:0 0 0 5px;color:#990000;font-size:13px;} #embedly_amzn_24496301 .embedly_amzn_logo A {background:transparent url(http://c1281762.cdn.cloudfiles.rackspacecloud.com/amazon-sprite.png) no-repeat scroll -160px -15px;display:inline-block;float:right;height:30px;overflow:hidden;width:140px;} </style> <div class="embedly_amzn_out_bdr"> <table class="embedly_amzn_in_bdr"> <tr> <td class="embedly_amzn_img" style="width:160px;"> <a href="'.$url.'?tag=theubergeeksn-20" ><img height="160px" width="160px" src="http://ecx.images-amazon.com/images/I/417j8GAjnyL._SL160_.jpg" /> </a> </td> <td class="embedly_amzn_desc" style="width:100%;"> <h3><a href="'.$url.'?tag=theubergeeksn-20" >'.$response['title'].'</a></h3> <span class="subhead">Product by Amazon</span> <a class="em_more" href="'.$url.'&tag=theubergeeksn-20#moreAboutThisProduct">More about this product</a> <hr size="1" noshade="noshade"> <table class="buying"> <tr> <td class="label pricelabel"></td> <td>'.$response['description'].'</td> </tr> </table> </td> </tr> <tr class="embedly_amzn_logo"> <td></td> <td> <a href="'.$url.'?tag=theubergeeksn-20" class="logo" ></a> </td> </tr> </table> </div> </div>';
      break;
      
      
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
      
      /*case 'amazon.com':
        return true;
      break;
      
      case 'amzn.com':
        return true;
      break;*/
      
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