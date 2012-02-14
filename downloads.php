<?php
  include("includes/common.php");
  placeHeader("Downloads");
?>

<div style="background-color: rgb(255,220,220); border: 1px solid rgb(240,150,150); padding: 5px; margin: 5px; border-radius: 10px;">
<b>Notice:</b> For the registration of new accounts please use the <a href="registration.php"> web form</a>. The registration from within the client is disabled.
</div>

<h3>Current client release</h3>

<p>Official packages:</p>
<table style="border-width: 0px; width: 600px; margin-bottom: 0px; margin-top: 0px;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-win.png"> <a href="http://sourceforge.net/projects/themanaworld/files/The%20Mana%20World/0.6.0/tmw-0.6.0-win32.exe/download"><b>Windows installer</b> 0.6.0</a></div>
<div class="release"><img src="images/ico-osx.png"> <a href="http://server.themanaworld.org/mana-0.5.dmg"><b>MacOS X package</b> 0.5.0</a></div>
</td><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-src.png"> <a href="http://downloads.sourceforge.net/themanaworld/tmw-0.5.2.tar.gz"><b>Source code</b> 0.5.2</a></div>
</td></tr>
</tbody></table>

<p>Distribution packages and ports:</p>
<table style="border-width: 0px; width: 600px; margin-bottom: 0px; margin-top: 0px;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-deb.png"> <a href="http://packages.debian.org/tmw"><b>Debian package</b></a></div>
<div class="release"><img src="images/ico-ubuntu.png"> <a href="http://packages.ubuntu.com/tmw"><b>Ubuntu package</b></a></div>
<div class="release"><img src="images/ico-fedora.png"> <a href="http://fedoraproject.org/wiki/Games/manaworld"><b>Fedora package</b></a></div>
<div class="release"><img src="images/ico-win.png"> <a href="http://portableapps.com/apps/games/the_mana_world_portable"><b>Windows Portable</b></a></div>
</td><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-gentoo.png"> <a href="http://packages.gentoo.org/package/games-rpg/mana"><b>Gentoo eBuild</b></a></div>
<div class="release"><img src="images/ico-freebsd.png" width="16" height="16"> <a href="http://www.freshports.org/games/tmw/"><b>FreeBSD port</b></a></div>
<div class="release"><img src="images/ico-openbsd.png" width="16" height="16"> <a href="http://openports.se/games/tmw"><b>OpenBSD port</b></a></div>
</td></tr>
</tbody></table>

<p>For older versions please see our <a href="http://sourceforge.net/project/showfiles.php?group_id=106790">files
page</a> on SourceForge. Note however that they are no longer supported and
likely to no longer work on our testing server.</p>

<p>People who are still on Ubuntu Hardy (8.04) or Intrepid (8.10) can install a
more recent version of The Mana World by adding the
<a href="https://launchpad.net/~nail-nodomain/+archive/ppa">private package
achive (PPA) of Tomas Kovacik</a>.</p>

<h3>Music</h3>

<p>While the music is optionally downloadable when installing the Windows version,
it is left out of most other releases because of its size. Download it here if the music is
not available for your operating system or if you've compiled The Mana World manually.</p>

<table cellspacing="0" cellpadding="0" border="0" style="border-width: 0px; margin-bottom: 0px; margin-top: 0px;">
<tr><td style="border-width: 0px; padding-right: 10px;">
<div class="release"><a href="http://downloads.sourceforge.net/themanaworld/tmwmusic-0.3.tar.gz"><b>The Mana World music</b> 0.3</a></div>
</td></tr>
</table>

<h3>Development version</h3>

<p>The latest version of the client is developed as part of the
<a href="http://manasource.org/">Mana project</a> and is available from the
<a href="http://github.com/mana">Git repositories on github</a>. For
checking out the latest development version of the client you can use the
command:</p>

<pre>
git clone git://github.com/mana/mana.git
</pre>

<p>For more instructions and information about other projects like the server
and the dynamic data, please check our
<a href="http://wiki.themanaworld.org/index.php/Git_repository">Git wiki
page</a>.

<?php
  placeFooter();
?>
