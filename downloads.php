<?php
  include("includes/common.php");
  placeHeader("Downloads");
?>

<h3>Current client release</h3>

<p>Official packages:</p>
<table style="border-width: 0px; width: 600px; margin-bottom: 0px; margin-top: 0px;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-win.png"> <a href="http://downloads.sourceforge.net/themanaworld/tmw-0.0.26-win32.exe"><b>Windows installer</b> 0.0.26</a></div>
<div class="release"><img src="images/ico-osx.png"> <a href="http://downloads.sourceforge.net/themanaworld/tmw-0.0.25-osx.dmg"><b>MacOS X package</b> 0.0.25</a></div>
</td><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-src.png"> <a href="http://downloads.sourceforge.net/themanaworld/tmw-0.0.27.tar.gz"><b>Source code</b> 0.0.27</a></div>
<div class="release"><img src="images/ico-slackware.png"> <a href="http://downloads.sourceforge.net/themanaworld/tmw-0.0.26-i686-1.tgz"><b>Slackware 12.1 package</b> 0.0.26</a></div>
</td></tr>
</tbody></table>

<p>Distribution packages and ports:</p>
<table style="border-width: 0px; width: 600px; margin-bottom: 0px; margin-top: 0px;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-deb.png"> <a href="http://packages.debian.org/tmw"><b>Debian package</b></a></div>
<div class="release"><img src="images/ico-ubuntu.png"> <a href="http://packages.ubuntu.com/tmw"><b>Ubuntu package</b></a></div>
<div class="release"><img src="images/ico-fedora.png"> <a href="http://fedoraproject.org/wiki/Games/manaworld"><b>Fedora package</b></a></div>
</td><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-gentoo.png"> <a href="http://packages.gentoo.org/package/games-rpg/tmw"><b>Gentoo eBuild</b></a></div>
<div class="release"><img src="images/ico-freebsd.png"> <a href="http://www.freshports.org/games/tmw/"><b>FreeBSD port</b></a></div>
<div class="release"><img src="images/ico-openbsd.png"> <a href="http://openports.se/games/tmw"><b>OpenBSD port</b></a></div>
</td></tr>
</tbody></table>

<p>For older versions please see our <a href="http://sourceforge.net/project/showfiles.php?group_id=106790">files
page</a> on SourceForge. Note however that they are no longer supported and
likely to no longer work on our testing server.</p>

<h3>Music</h3>

<p>While the music is included with our installer for Windows, it is left out
of most other releases because of its size. Download it here if the music is
not available for your operating system or if you've compiled The Mana World
manually.</p>

<table cellspacing="0" cellpadding="0" border="0" style="border-width: 0px; margin-bottom: 0px; margin-top: 0px;">
<tr><td style="border-width: 0px; padding-right: 10px;">
<div class="release"><a href="http://downloads.sourceforge.net/themanaworld/tmwmusic-0.2.tar.gz"><b>The Mana World music</b> 0.2</a></div>
</td></tr>
</table>

<h3>Development version</h3>

<p>The latest versions of our projects are always available from their
<a href="http://gitorious.org/search?q=category%3Athe-mana-world">Git
repositories on Gitorious</a>. For checking out the latest development version
of the client you can use the command:</p>

<pre>
git clone git://gitorious.org/tmw/mainline.git tmw
</pre>

<p>For more instructions and information about other projects like the server
and the dynamic data, please check our
<a href="http://wiki.themanaworld.org/index.php/Git_Repository">Git wiki
page</a>.

<p class="note">
<b>Note:</b> The mainline git repository is based on our own server,
<i>tmwserv</i>. If you're interested in the latest version of the client that
is able to connect to eAthena, check out the
<a href="http://gitorious.org/projects/tmw/repos/eathena">eathena
repository</a> of the <i>tmw</i> project.
</p>
<!--
<a name="repository"></a>
<h3>Ubuntu repository (out of date)</h3>

<p>For convenience for Ubuntu users, a package repository
has been set up by Bertram. The repository also contains
<a href="http://guichan.sourceforge.net/">Guichan</a>, because its latest
version isn't generally available yet.</p>

<h4>Ubuntu Edgy (i386 and AMD-64)</h4>

<pre>
deb http://bertram.ifrance.com/ ubuntu edgy
</pre>

<p>Then to install TMW do:</p>

<p><b>apt-get update<br/>
apt-get install tmw</b></p>
-->
<?php
  placeFooter();
?>
