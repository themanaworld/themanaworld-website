<?php
/* Main include file for TMW website, including header and footer
 * (c) 2005 Bjorn Lindeijer
 */

define('TMWWEBSITE', true);

function placeHeader($page_title)
{
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>The Mana World</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta http-equiv="Content-Style-Type" content="text/css"/>
  <meta http-equiv="Content-Language" content="en"/>
  <link rel="icon" type="image/png" href="images/icon16.png"/>
  <meta name="description" content="The Mana World is a free MMORPG game for Linux, MacOS X and Windows"/>
  <meta name="keywords" content="The Mana World, Mana, World, MMORPG, RPG, free, GPL, Open Source, Pixel Art, 2D, Software Development, MacOX, Windows, Linux, desktop, tablet"/>
  <meta name="author" content="The Mana World Dev Team"/>
  <style type="text/css">
   @import url("default.css");
  </style>
 </head>
 <body>
  <div style="margin-left: auto; margin-right: auto; max-width: 1350px;">
  <div id="page">

   <div id="irc_info">
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
               <li><a href="registration.php">Registration</a></li>
               <li><a href="downloads.php">Downloads</a></li>
               <li><a href="index.php">News</a></li>
               <li><a href="about.php">About</a></li>
               <li><a href="http://wiki.themanaworld.org/index.php/Servers">Servers</a></li>
               <li><a href="http://wiki.themanaworld.org/">Wiki</a></li>
               <li><a href="http://forums.themanaworld.org/">Forums</a></li>
              </ul>
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
             <div class="section">
              <ul>
               <li><a href="https://webchat.freenode.net/?channels=#themanaworld">IRC</a></li>
               <li><a href="https://plus.google.com/114523926767095095371">Google+</a></li>
               <li><a href="https://www.facebook.com/themanaworld">Facebook</a></li>
               <li><a href="https://github.com/themanaworld">Github</a></li>
               <li><a href="https://trello.com/themanaworld">Trello</a></li>
               <li><a href="http://sourceforge.net/projects/themanaworld/"><img src="https://sflogo.sourceforge.net/sflogo.php?group_id=106790&amp;type=1" alt="SourceForge.net"/></a></li>
               <li><a href="http://www.gnu.org/copyleft/gpl.html"><img src="images/gpl.png" alt="GPLed project"/></a></li>
              </ul>
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
              <iframe src="https://server.themanaworld.org/status.php" width="130" height="30" frameborder="0" scrolling="no"></iframe>
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
  </div>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-5KTWGJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5KTWGJ');</script>
<!-- End Google Tag Manager -->
 </body>
</html>
<?php
}

?>
