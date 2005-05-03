<?php
/*
 * galerie.php - a simple gallery script
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * --
 * This script provides a simple gallery of all images that are located
 * in the script's directory and subdirectories.
 *
 * Requirements
 * - PHP >= 4.1.0
 * - GD Library ( >= 2.0.1 for good thumbnails)
 * - JPEG software
 * - PHP >= 4.3.0 or GD < 1.6 for GIF support
 * - libpng for PNG support
 *
 * Installation
 * Simply put this script in a folder of your web server and call it in a
 * web browser. Be sure that the script has permission to read the image
 * files and to create and write into the thumbnail folder.
 *
 * Attention:
 * This script tries to generate jpeg thumbnail files in a subfolder of the
 * gallery folder(s). The filenames look like "originalfilename.thumb.jpg".
 *
/* ------------------------------------------------------------------------- */

/* Select your charset
 */
$charset = 'ISO-8859-1';

/* How many images per page?
 */
$maxpics = 48;

/* Create thumbnails in this subfolder
 */
$thumbdir = 'thumbs';

/* Size of created thumbnails
 */
$thumbsize = 150;

/* Wether to show file names (true or false)
 */
$filenames = false;

/* Wether to show subdirectores (true or false)
 */
$subdirs = true;

/* Wether to show a title (true or false)
 */
$title = false;

/* Set the gallery root relative to the script's directory.
 *
 * If you include() this script, set the path relative to
 * the directory of the script, that does the include().
 */
$picdir = 'gallery';

/* Set this to true if you include() this script.
 */
$included = true;

/* Set this to true, if you include() this script and want the images
 * to be shown inline.
 */
$inline = false;

/* Set the thumbnail background color, if you include() this script.
 */
$bg = 'ffffff';

/* ------------------------------------------------------------------------- */
$words = array(
 'gallery' => 'gallery',
 'error' => 'Error',
 'php_error' => 'PHP >= 4.1 is required.',
 'gd_error' => 'GD Library is required. See http://www.boutell.com/gd/.',
 'jpg_error' => 'JPEG software is required. See ftp://ftp.uu.net/graphics/jpeg/.',
 'mkdir_error' => 'Write permission is required in this folder.',
 'opendir_error' => 'The directory "%1" can not be read.'
);
isset($_SERVER) || ($error = error('php'));
function_exists('imagecreate') || ($error = error('gd'));
function_exists('imagejpeg') || ($error = error('jpg'));
if (function_exists('ini_set')) @ini_set('memory_limit', -1);
$jpg = '\.jpg$|\.jpeg$'; $gif = '\.gif$'; $png = '\.png$';
$fontsize = 2;
function word ($word) {
 global $words;
 return html($words[$word]);
}
function html ($word) {
 global $charset;
 return htmlentities($word, ENT_COMPAT, $charset);
}
function error ($word, $arg = '') {
 global $words;
 return html(str_replace('%1', $arg, $words[$word .'_error']));
}
if (!$included) {
 echo('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=' . $charset . '" />
<style type="text/css">
body { font-family: sans-serif }
hr { border-style: none; height: 1px; background-color: silver; color: silver }
p { margin: 0px; padding: 0px }
#pagenumbers { text-align: center; margin: 0px 1em 0px 1em }
img { margin: 10px; border: none }
a { text-decoration: none }
#pictures a img {
border: 1px solid rgb(200,200,200);
}
#pictures a:hover img {
border: 1px solid rgb(140,140,140);
}
');
 if ($filenames) {
  echo('div a p { font-size: small }
a:hover p { text-decoration: underline }
div { width: ' . ($thumbsize + 20) . 'px; text-align: center; float: left; margin: 1em }
');
 }
 echo("</style>\n");
}
$delim = DIRECTORY_SEPARATOR;
if (array_key_exists('dir', $_REQUEST) && $subdirs) $dir = $_REQUEST['dir'];
else $dir = '';
if (!empty($_SERVER['PATH_TRANSLATED'])) $d = dirname($_SERVER['PATH_TRANSLATED']);
elseif (!empty($_SERVER['SCRIPT_FILENAME'])) $d = dirname($_SERVER['SCRIPT_FILENAME']);
else $d = getcwd();
$delim = (substr($d, 1, 1) == ':') ? '\\' : '/';
$rp = function_exists('realpath');
if ($rp) $root = realpath($d . $delim . $picdir);
else $root = $d . $delim . $picdir;
if ($rp) $realdir = realpath($root . $dir);
else $realdir = $root . $dir;
if (substr($realdir, 0, strlen($root)) != $root) { $realdir = $root; $dir = ''; }
$dirname = substr($realdir, strlen($root));
$dirnamehttp = $picdir . $dir;
if ($delim == '\\') $dirnamehttp = strtr($dirnamehttp, '\\', '/');
if (substr($dirnamehttp, 0, 2) == './') $dirnamehttp = substr($dirnamehttp, 2);
if (empty($dirnamehttp)) $dirnamehttp = '.';
$ti = ($subdirs && !empty($dirname)) ? ": $dirname" : '';
if (!$included) {
 if (isset($error)) echo("<title>$error</title>");
 else echo('<title>' . word('gallery') . html($ti) . "</title>\n");
 echo("</head>\n<body>\n");
}
if (($d = @opendir($realdir)) === false) $error = error('opendir', array($realdir));
if (isset($error)) echo("<p style=\"color: red\">$error</p>\n"); else {
 if ($title) echo('<h1>' . word('gallery') . html($ti) . "</h1>\n");
 $dirs = $pics = array();
 $query = $jpg;
 if (function_exists('imagecreatefromgif')) $query .= "|$gif";
 if (function_exists('imagecreatefrompng')) $query .= "|$png";
 while (($filename = readdir($d)) !== false) {
  if ($filename == $thumbdir
   || ($filename == '..' && $dirname == '')
   || ($filename != '..' && substr($filename, 0, 1) == '.')) continue;
  $file = $realdir . $delim . $filename;
  if (is_dir($file)) $dirs[] = $filename;
  elseif (eregi($query, $file)) $pics[] = $filename;
 }
 closedir($d);
 sort($dirs);
 sort($pics);
 $urlsuffix = '';
 foreach ($_GET as $v => $r) {
  if (!in_array($v, array('dir', 'pic', 'offset'))) $urlsuffix .= "&$v=" . urlencode($r);
 }
 if ($included && $inline && array_key_exists('pic', $_REQUEST)) {
  $pic = $_REQUEST['pic'];
  echo("<div id=\"picture\">\n");
  echo('<img src="' . html("$dirnamehttp/{$pics[$pic]}") . '" alt="' . html(basename($pics[$pic])) . '"');
  list($width, $height, $type, $attr) = @getimagesize($pic);
  if (!empty($width)) echo(" style=\"width: {$width}px; height: {$height}px\"");
  echo(" />\n");
  $url = ($dirname  == '') ? '?' : '?dir=' . urlencode($dirname) . '&';
  if ($pic > 0)
  echo('<a href="' . html($url) . 'pic=' . ($pic - 1) . html($urlsuffix) . '">[&lt;]</a> ');
  if ($pic >= $maxpics)
  $u = "{$url}offset=" . (floor($pic / $maxpics) * $maxpics) . $urlsuffix;
  else {
   if (array_key_exists('dir', $_REQUEST)) {
    $u = substr($url, 0, strlen($url) - 1) . $urlsuffix;
   } else {
    $u = ereg_replace('^([^?]+).*$', '\1', $_SERVER['REQUEST_URI']);
    if (!empty($urlsuffix)) {
     if (strstr($u, '?') === false) $u .= '?' . substr($urlsuffix, 1);
     else $u .= $urlsuffix;
    }
   }
  }
  echo('<a href="' . html($u) . '">[-]</a>');
  if ($pic + 1 < sizeof($pics))
  echo(' <a href="' . html($url) . 'pic=' . ($pic + 1) . html($urlsuffix) . '">[&gt;]</a>');
  echo("\n</div>\n");
 } else {
  if (sizeof($dirs) > 0 && $subdirs) {
   echo("<ul id=\"directories\">\n");
   foreach ($dirs as $filename) {
    if ($rp) $target = substr(realpath($realdir . $delim . $filename), strlen($root));
    else $target = substr($realdir . $delim . $filename, strlen($root));
    if ($delim == '\\') $target = strtr($target, '\\', '/');
    if ($target == '') {
     $url = ereg_replace('^([^?]+).*$', '\1', $_SERVER['REQUEST_URI']);
     if (!empty($urlsuffix)) {
      if (strstr($url, '?') === false) $url .= '?' . substr($urlsuffix, 1);
      else $url .= $urlsuffix;
     }
    } else $url = '?dir=' . urlencode($target) . $urlsuffix;
    echo('<li><a href="' . html($url) . '">' . html($filename) . "</a></li>\n");
   }
   echo("</ul>\n");
  }
  if (($num = sizeof($pics)) > 0) {
   if (array_key_exists('offset', $_REQUEST)) $offset = $_REQUEST['offset'];
   else $offset = 0;
   if ($num > $maxpics) {
    echo("<p id=\"pagenumbers\">\n");
    for ($i = 0; $i < $num; $i += $maxpics) {
     $e = $i + $maxpics - 1;
     if ($e > $num - 1) $e = $num - 1;
     if ($i != $e) $b = ($i + 1) . '-' . ($e + 1);
     else $b = $i + 1;
     if ($i == $offset) echo("<b>$b</b>");
     else {
      $url = ($dirname  == '') ? '?' : '?dir=' . urlencode($dirname) . '&amp;';
      echo("<a href=\"{$url}offset=$i" . html($urlsuffix) . "\">$b</a>");
     }
     if ($e != $num - 1) echo(' |');
     echo("\n");
    }
    echo("</p>\n");
   }
   echo("<p id=\"pictures\">\n");
   for ($i = $offset; $i < $offset + $maxpics; $i++) {
    if ($i >= $num) break;
    $filename = $pics[$i];
    $file = $realdir . $delim . $filename;
    if (!is_readable($file)) continue;
    if (!is_dir($realdir . $delim . $thumbdir)) {
     $u = umask(0);
     if (!@mkdir($realdir . $delim . $thumbdir, 0777)) {
      echo('<p style="color: red; text-align: center">' . word('mkdir_error') . '</span>');
      break;
     }
     umask($u);
    }
    $thumb = $realdir . $delim . $thumbdir . $delim . $filename . '.thumb.jpg';
    if (!is_file($thumb)) {
     if (eregi($jpg, $file))
     $original = @imagecreatefromjpeg($file);
     elseif (eregi($gif, $file))
     $original = @imagecreatefromgif($file);
     elseif (eregi($png, $file))
     $original = @imagecreatefrompng($file);
     else continue;
     if ($original) {
      if (function_exists('getimagesize'))
      list($width, $height, $type, $attr) = getimagesize($file);
      else continue;
      if ($width >= $height && $width > $thumbsize) {
       $smallwidth = $thumbsize;
       $smallheight = floor($height / ($width / $smallwidth));
       $ofx = 0; $ofy = floor(($thumbsize - $smallheight) / 2);
      } elseif ($width <= $height && $height > $thumbsize) {
       $smallheight = $thumbsize;
       $smallwidth = floor($width / ($height / $smallheight));
       $ofx = floor(($thumbsize - $smallwidth) / 2); $ofy = 0;
      } else {
       $smallheight = $height;
       $smallwidth = $width;
       $ofx = floor(($thumbsize - $smallwidth) / 2);
       $ofy = floor(($thumbsize - $smallheight) / 2);
      }
     }
     if (function_exists('imagecreatetruecolor'))
$small = imagecreatetruecolor($thumbsize, $thumbsize);
     else $small = imagecreate($thumbsize, $thumbsize);
     sscanf($bg, "%2x%2x%2x", $red, $green, $blue);
     $b = imagecolorallocate($small, $red, $green, $blue);
     imagefill($small, 0, 0, $b);
     if ($original) {
      if (function_exists('imagecopyresampled'))
imagecopyresampled($small, $original, $ofx, $ofy, 0, 0, $smallwidth, $smallheight, $width, $height);
      else
imagecopyresized($small, $original, $ofx, $ofy, 0, 0, $smallwidth, $smallheight, $width, $height);
     } else {
      $black = imagecolorallocate($small, 0, 0, 0);
      $fw = imagefontwidth($fontsize);
      $fh = imagefontheight($fontsize);
      $htw = ($fw * strlen($filename)) / 2;
      $hts = $thumbsize / 2;
      imagestring($small, $fontsize, $hts - $htw, $hts - ($fh / 2), $filename, $black);
imagerectangle($small, $hts - $htw - $fw - 1, $hts - $fh, $hts + $htw + $fw - 1, $hts + $fh, $black);
     }
     imagejpeg($small, $thumb);
    }
    if ($filenames) echo('<div>');
    if ($included && $inline) {
    echo('<a href="?');
    if (array_key_exists('dir', $_REQUEST)) echo('dir=' . urlencode($_REQUEST['dir']) . '&amp;');
    echo('pic=' . $i . html($urlsuffix));
    } else echo('<a href="' . html("$dirnamehttp/$filename"));
    echo('"><img src="' . html("$dirnamehttp/$thumbdir/$filename.thumb.jpg"));
    echo('" alt="' . html($filename) . '" style="');
    echo("width: {$thumbsize}px; height: {$thumbsize}px\" />");
    if ($filenames) echo('<p>' . html($filename) . '</p>');
    echo('</a>');
    if ($filenames) echo("</div>\n"); else echo("\n");
   }
   echo("</p>\n");
  }
 }
}
if (!$included) echo("<div style=\"font-size: 70%;\">Powered by <a href=\"http://cker.name/galerie/\">galerie.php</a></div></body>\n</html>");
?>
