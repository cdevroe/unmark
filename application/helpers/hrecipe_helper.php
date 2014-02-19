<?php 
include('simple_html_dom.php');

function parse_hrecipe($url) {
  ini_set('user_agent', 'Unmark/1.0');
  ini_set('memory_limit', '500M');

  /* Alchemy API
  $endpoint = 'http://access.alchemyapi.com/calls/url/URLGetMicroformatData';
    $endpoint .= '?apikey=73f5f7a081b189a285d1ccf5a86464b183d7a1a5';
    $endpoint .= '&url='.urlencode($url);
    $endpoint .= '&outputMode=json';
  //echo $endpoint;
  //exit; 
  Doesn't support hRecipe right now.
  */
  $html = file_get_html($url);
  
  if (!$html) { return false; }
  
  
  $hrecipe = $html->find('.hrecipe');
  
  if (isset($hrecipe) && is_array($hrecipe)) {
    $recipe = array();
    
    // Parse recipe
    foreach($html->find('.fn') as $e)
      $recipe['title'] = $e->plaintext;
      
    foreach($html->find('.ingredient') as $e)
      $recipe['ingredients'][] = $e->plaintext;
      
    foreach($html->find('.yield') as $e)
      $recipe['yield'] = $e->plaintext;
      
    foreach($html->find('.instructions') as $e)
      $recipe['instructions'] = $e->plaintext;
      
    foreach($html->find('.duration') as $e)
      $recipe['duration'] = $e->plaintext;
      
  if (!isset($recipe['title']) || !isset($recipe['ingredients'])) { return false; } // Could be hCard
  
   $recipeHTML = '<div class="hrecipe"><span class="fn">'.$recipe['title'].'</span><div class="ingredients">';
   
   if (isset($recipe['ingredients'])) {
      for($i=0;$i<count($recipe['ingredients']);$i++) { 
        $recipeHTML .= '<span class="ingredient">'.$recipe['ingredients'][$i].'</span>';
      }
   }
    
  $recipeHTML .= '</div>';
  
  if (isset($recipe['yield'])) {
    $recipeHTML .= '<span class="yield">'.$recipe['yield'].'</span>';
  }
  
  $recipeHTML .= '</div>';
      
  unset($recipe);
  return $recipeHTML;
    
  } else {
    return false;
  
  }
}


?>