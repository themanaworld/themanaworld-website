<?php
/* Header for main TMW website, includes sidebar
 * (c) 2005 Bjørn Lindeijer
 *
 * Variables used:
 *  $page_title    // Title to place above contents
 */

if (!defined('TMWWEBSITE'))
{
  die("Hacking attempt?");
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "DTD/xhtml1-strict.dtd">
<html>
 <head>
  <title>The Mana World</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta http-equiv="Content-Language" content="en"/>
  <link rel="icon" type="image/png" href="images/icon16.png"/>
  <meta name="description" content="The Mana World is a free MMORPG game for Linux, MacOS X and Windows"/>
  <meta name="keywords" content="The Mana World, Mana, World, MMORPG, RPG, free, GPL"/>
  <meta name="author" content="The Mana World Dev Team"/>
  <style type="text/css">
   @import url("default.css");
  </style>
 </head>
 <body>
  <div id="page">

   <div id="irc_info">
    #manaworld<br />
    irc.freenode.net
   </div>

   <div id="title">
    <h1><span>The Mana World</span></h1>
   </div>

   <div id="main_topleft">
    <div id="main_rightrepeat">
     <div id="main_topright">
      <div id="main_bottomright">
       <div id="main">

        <div style="clear: both;"></div>

        <div id="sidebar">

         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section">
              <ul>
               <!-- No newlines after list items because IE 6 can't handle that properly -->
               <li><a href="index.php">News</a></li><li><a href="about.php">About</a></li><li><a href="downloads.php">Downloads</a></li><li><a href="wiki">Wiki</a></li><li><a href="phpBB">Forum</a></li><li><a href="links.php">Links</a></li></ul>
             </div>
            </div>
           </div>
          </div>
         </div>

         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section" style="text-align: center;">
              <a href="#"><img src="images/tmw-0.0.10-1-thumb.png" alt="screenshot" style="border: 1px solid #784f3f;"/></a>
              <div style="text-align: center;"><a style="font-size: 7pt;" href="screenshots.php">more screenshots</a></div>
             </div>
            </div>
           </div>
          </div>
         </div>

         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section">
              <h3>Server Status</h3>
             </div>
            </div>
           </div>
          </div>
         </div>

         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section banners">
              <a href="http://sourceforge.net">
               <img src="http://sflogo.sourceforge.net/sflogo.php?group_id=106790&amp;type=1" alt="SourceForge.net"/></a><br />
              <a href="http://nosoftwarepatents.com">
               <img src="images/nswpat80x15.png" alt="No software patents"/></a><br />
              <a href="http://www.gnu.org/copyleft/gpl.html">
               <img src="images/gpl.png" alt="GPLed project"/></a><br />
             </div>
            </div>
           </div>
          </div>
         </div>

        </div> <!-- /sidebar -->

        <div id="contents_leftrepeat">
         <div id="contents_topleft">
          <div id="contents_bottomleft">
           <div id="contents_rightrepeat">
            <div id="contents_topright">
             <div id="contents_bottomright">
              <div id="contents">

               <div class="main_title_topright">
                <div class="main_title_bottomright">
                 <div class="main_title_topleft">
                  <h2><?=$page_title?></h2>
                 </div>
                </div>
               </div>
