<?php
/* Main include file for TMW website, including header and footer
 * (c) 2005 Bjorn Lindeijer
 */

define('TMWWEBSITE', true);

function placeHeader($page_title)
{
  echo "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>The Mana World</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15"/>
  <meta http-equiv="Content-Style-Type" content="text/css"/>
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
    <a href="irc://irc.themanaworld.org/themanaworld" title="IRC">
    #themanaworld<br />
    irc.themanaworld.org
    </a>
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
               <li><a href="index.php">News</a></li><li><a href="about.php">About</a></li><li><a href="downloads.php">Downloads</a></li><li><a href="registration.php">Registration</a></li><li><a href="http://wiki.themanaworld.org/index.php/Servers">Servers</a></li><li><a href="http://wiki.themanaworld.org/">Wiki</a></li><li><a href="http://forums.themanaworld.org/">Forums</a></li><li><a href="http://mantis.themanaworld.org/">Bug tracker</a></li><li><a href="links.php">Links</a></li></ul>
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
              <a href="http://wiki.themanaworld.org/index.php/Screenshots"><img src="images/screenshot-thumb.png" alt="Screenshots" style="border: 1px solid #784f3f;"/></a>
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
              <a href="http://www.nosoftwarepatents.com">
               <img src="images/nswpat80x15.png" alt="No software patents"/></a><br />
              <a href="http://www.gnu.org/copyleft/gpl.html">
               <img src="images/gpl.png" alt="GPLed project"/></a><br />
              <a href="http://manasource.org">
               <img src="images/manasource80x15.png" alt="ManaSource user"/></a><br />
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
              Server status
              <iframe src="http://server.themanaworld.org/status.php" width="130" height="30" frameborder="0" scrolling="no"></iframe>
              <a href="registration.php">Register new account</a>
             </div>
            </div>
           </div>
          </div>
         </div>

         <!-- This empty div fixes a rendering issue with IE 7 -->
         <div></div>

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
<?php
}

function placeFooter()
{
?>
               <div style="clear: both"></div>

              </div> <!-- /contents -->
             </div>
            </div>
           </div>
          </div>
         </div>
        </div>

       </div> <!-- /main -->
      </div>
     </div>
    </div>
   </div>

   <div id="footer">
    &copy; 2004-2011 The Mana World Dev Team -
    [<a href="http://validator.w3.org/check?uri=referer">xhtml</a>] [<a href="http://jigsaw.w3.org/css-validator/check/referer">css</a>]
   </div>
  </div> <!-- /page -->

  <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
  </script>
  <script type="text/javascript">
  _uacct = "UA-380618-3";
  urchinTracker();
  </script>
 </body>
</html>
<?php
}

?>
