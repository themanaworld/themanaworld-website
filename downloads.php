<?php
  include("includes/common.php");
  placeHeader("Downloads");
?>

<div style="background-color: rgb(255,220,220); border: 1px solid rgb(240,150,150); padding: 5px; margin: 5px; border-radius: 10px;">
<b>Notice:</b> For the registration of new accounts please use the <a href="registration.php"> web form</a>. The registration from within the client is disabled.
</div>

<h3>ManaPlus client</h3>
<p>The recommended client is available on the <a href="http://manaplus.evolonline.org/">ManaPlus website</a>.</p>

<h3>The Mana World packages</h3>

<p>The Mana World packages are available for many platforms. These are based on
	the ManaPlus client, but have a different default skin and will show up
	as "The Mana World" in the application menu.</p>

<table style="border-width: 0px; width: 600px; margin-bottom: 0px; margin-top: 0px;" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td style="border-width: 0px;" width="50%">

<div class="release"><img src="images/ico-win.png"> <a href="http://download.evolonline.org/manaplus/tmw/tmw-win32.exe"><b>Windows installer (ManaPlus)</b></a></div>
<div class="release"><img src="images/ico-osx.png"> <a href="http://download3.evolonline.org/manaplus/macosx/manaplus-lion.dmg"><b>ManaPlus MacOS X package (lion)</b></a></div>
<div class="release"><img src="images/ico-osx.png"> <a href="http://download3.evolonline.org/manaplus/macosx/manaplus-snowleopard.dmg"><b>ManaPlus MacOS X package (snow leopard)</b></a></div>
<div class="release"><img src="images/ico-deb.png"> <a href="http://packages.debian.org/tmw"><b>Debian package</b></a></div>
<div class="release"><img src="images/ico-ubuntu.png"> <a href="http://packages.ubuntu.com/tmw"><b>Ubuntu package</b></a></div>
<div class="release"><img src="images/ico-fedora.png"> <a href="https://apps.fedoraproject.org/packages/tmw"><b>Fedora package</b></a></div>

</td><td style="border-width: 0px;" width="50%" valign="top">

<div class="release"><img src="images/ico-android.png"> <a href="http://download.evolonline.org/manaplus/download/manaplus.apk"><b>Android App (ManaPlus)</b></a></div>
<div class="release"><img src="images/ico-gentoo.png"> <a href="http://packages.gentoo.org/package/games-rpg/tmw"><b>Gentoo eBuild</b></a></div>
<div class="release"><img src="images/ico-openbsd.png" width="16" height="16"> <a href="http://openports.se/games/manaplus"><b>OpenBSD port</b></a></div>
<div class="release"><img src="images/ico-freebsd.png" width="16" height="16"> <a href="http://www.freshports.org/games/tmw/"><b>FreeBSD port</b></a></div>

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

<h3>Development versions</h3>

<p>The latest version of the ManaPlus client is developed as part of the
<a href="http://manaplus.evolonline.org/">ManaPlus project</a> and is available from the
<a href="https://gitorious.org/manaplus/manaplus/">Git repositories on gitorious</a>. For
checking out the latest development version of the client you can use the
command:</p>

<pre>
git clone git://gitorious.org/manaplus/manaplus.git
</pre>

<p>For more instructions and information about other projects like the server
and the dynamic data, please check our
<a href="http://wiki.themanaworld.org/index.php/Git_repository">Git wiki
page</a>.

<?php
  placeFooter();
?>
