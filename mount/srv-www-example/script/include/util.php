<?php
class Util {

  public function __constractor() { }

  // This function receives string such as '1M', then returns integer 1048576.
  public static function to_bytes($str) {
    $size = preg_replace('/ /', '', $str);  
    $value = substr($size, 0, -1);
    $shorthand = substr($size, -1); 
    switch (true) {
      case preg_match('/^k$/i', $shorthand): return (int)$value * 1024;
      case preg_match('/^m$/i', $shorthand): return (int)$value * 1024 ** 2;
      case preg_match('/^g$/i', $shorthand): return (int)$value * 1024 ** 3;
      default: return (int)$value;
    }
  }
}
?>
