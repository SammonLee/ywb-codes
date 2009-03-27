<?php
// lib/Jobeet.class.php
class Jobeet
{
  static public function slugify($text)
  {
    // replace all non letters or digits by -
    $text = preg_replace('/\W+/', '-', $text);
 
    // trim and lowercase
    $text = strtolower(trim($text, '-'));

    if (empty($text))
    {
      return 'n-a';
    }
    return $text;
  }
}