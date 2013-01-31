<?php
  include("includes/common.php");
  placeHeader("Downloads");
?>

<div style="background-color: rgb(255,220,220); border: 1px solid rgb(240,150,150); padding: 5px; margin: 5px; border-radius: 10px;">
<b>Notice:</b> For the registration of new accounts please use the <a href="registration.php"> web form</a>. The registration from within the client is disabled.
</div>

<h3>ManaPlus client</h3>
The recommended client is available <a href="http://manaplus.evolonline.org/">on this other website</a>.

<h3>Mana client</h3>

<p>The old official client for playing The Mana World is the Mana client and is
	developed as part of the <a href="http://manasource.org/">Mana project</a>.
        The latest version of the client can be downloaded from there.</p>

<p>There will probably be no more releases of the Mana client that support this server. Use ManaPlus instead.
</p>

<h3>The Mana World packages</h3>

<p>The Mana World packages are available for many platforms. These are based on
	the Mana client, but have a different default skin and will show up
	as "The Mana World" in the application menu.</p>

<table style="border-width: 0px; width: 600px; margin-bottom: 0px; margin-top: 0px;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td style="border-width: 0px;" width="50%">

<div class="release"><img src="images/ico-win.png"> <a href="http://sourceforge.net/projects/themanaworld/files/The%20Mana%20World/0.6.0/tmw-0.6.0-win32.exe/download"><b>Windows installer</b> 0.6.0</a></div>
<div class="release"><img src="images/ico-osx.png"> <a href="http://server.themanaworld.org/mana-0.5.dmg"><b>MacOS X package</b> 0.5.0</a> (outdated, see forum)</div>
<div class="release"><img src="images/ico-deb.png"> <a href="http://packages.debian.org/tmw"><b>Debian package</b></a></div>
<div class="release"><img src="images/ico-ubuntu.png"> <a href="http://packages.ubuntu.com/tmw"><b>Ubuntu package</b></a></div>
<div class="release"><img src="images/ico-fedora.png"> <a href="https://apps.fedoraproject.org/packages/tmw"><b>Fedora package</b></a></div>

</td><td style="border-width: 0px;" width="50%" valign="top">

<div class="release"><img src="images/ico-win.png"> <a href="http://portableapps.com/apps/games/the_mana_world_portable"><b>Windows Portable</b></a></div>
<div class="release"><img src="images/ico-gentoo.png"> <a href="http://packages.gentoo.org/package/games-rpg/tmw"><b>Gentoo eBuild</b></a></div>
<div class="release"><img src="images/ico-freebsd.png" width="16" height="16"> <a href="http://www.freshports.org/games/tmw/"><b>FreeBSD port</b></a></div>
<div class="release"><img src="images/ico-openbsd.png" width="16" height="16"> <a href="http://openports.se/games/tmw"><b>OpenBSD port</b></a></div>

</td></tr>
</tbody></table>

<p>For packagers (or for users who're interested), the branding files can be
	downloaded separately:</p>

<table style="border-width: 0px; width: 600px; margin-bottom: 0px; margin-top: 0px;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td style="border-width: 0px;" width="50%">
<div class="release"><img src="images/ico-src.png"> <a href="http://sourceforge.net/projects/themanaworld/files/tmw-branding/tmw-branding-20120727.tar.gz/download"><b>Branding files</b> 2012-07-27</a></div>
</td></tr>
</tbody></table>

<p>For older versions please see our <a href="https://sourceforge.net/projects/themanaworld/files/">files
page</a> on SourceForge. Note however that these releases are no longer supported and
likely to no longer work with our server.</p>

<h3>Music</h3>

<p>We no longer recommend music be installed directly,
as it easily becomes out-of-date.</p>
<p>To enable music in the client, make sure that "Download Music" is checked
in the Audio tab of the Setup window.</p>

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
