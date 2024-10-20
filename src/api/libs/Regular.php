<?php

class Regular
{

  function __construct()
  {
    /*
      Regular expression
      replacements
      preg_replace
     */
  }

  function urlFriendly($url)
  {
    if (isset($url)):
      $str = $url;
      // $clean = preg_replace("/[^أ-يa-zA-Z0-9\/_|+ -]/", '', $str);
      $preg = preg_replace('/\s+/', "-", $str);
      $preg = filter_var($preg, FILTER_SANITIZE_STRING);

      $clean = $preg;
      $clean = strtolower(trim($str, '-'));
      $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
      $clean = preg_replace("/[\/_|+ .]+/", '', $clean);


      return $clean;

    else:
      echo 'argument no get!';
    endif;
  }

  function host($url = FALSE)
  {
    if (isset($url)):
      $url = $url;
      preg_match('@^(?:http://)?([^/]+)@i', $url, $matches);
      $host = $matches[1];
      return $host;
    else:
      echo 'argument no get!';
    endif;
    // or use :
    /*
      $value = 'http://net.tuts®plus.com';
      echo filter_var($value, FILTER_SANITIZE_URL);
     */
  }

  function replace_urls($text = null)
  {
    $regex = '/((http|ftp|https):\/\/)?[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/';
    return preg_replace_callback($regex, function ($m) {
      $link = $name = $m[0];
      if (empty($m[1])) {
        $link = "http://" . $link;
      }
      return '<a href="' . $link . '" target="_blank" rel="nofollow">' . $name . '</a>';
    }, $text);
    /*
      $text = "http://stackoverflow.com/questions/17854971/preg-replace-to-replace-string-for-matching-url#17855054
      www.google.com
      https://twitter.com/
      http://www.somelinkwithhash.com/post/4454/?foo=bar#foo=bar";

      echo $this->replace_urls( $text );
      die;
     */
  }

  public function number($string)
  {
    $output = preg_replace('/[^0-9]/', '', $string);
    return $output;
  }

  public function username($username)
  {
    $username = $username;
    if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
      return 1;
    } else {
      return 0;
    }
  }

}