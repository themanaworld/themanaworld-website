<?php
  include("includes/common.php");
  placeHeader("Downloads");
?>

<h3>Current client release</h3>

<div class="releases">
<div class="release"><img src="images/ico-win.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.21.1-win32.exe?download"><b>Windows installer</b> 0.0.21.1</a></div>
<div class="release"><img src="images/ico-src.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.21.1.tar.gz?download"><b>Source code</b> 0.0.21.1</a></div>
<div class="release"><img src="images/ico-osx.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.20-mac.dmg?download"><b>MacOS X package</b> 0.0.20</a></div>
<div class="release"><img src="images/ico-slackware.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.21-i686-1.tgz?download"><b>Slackware 10.2-current package</b> 0.0.21</a></div>
<div class="release"><img src="images/ico-deb.png"/> <a href="#repository"><b>Debian/Ubuntu repositories</b></a></div>
<div class="release"><img src="images/ico-gentoo.png"/> <a href="http://packages.gentoo.org/packages/?category=games-rpg;name=tmw"><b>Gentoo eBuild</b></a></div>
</div>

<p>For older versions please see our <a
href="http://sourceforge.net/project/showfiles.php?group_id=106790">files
page</a> on SourceForge. Note however that they are no longer supported and
likely to no longer work.</p>

<h3>Subversion access</h3>

<p>The latest version is always available from Subversion, see the
<a href="http://sourceforge.net/svn/?group_id=106790">Subversion instructions</a> at
SourceForge. The client module is named <i>tmw</i>. For checking out the latest
development version (trunk) of the client this means to use the command:<br/>
<br/>
<i>svn co https://svn.sourceforge.net/svnroot/themanaworld/tmw/trunk tmw</i><br/>
<br/>
For more instructions you can also read our small guide on
<a href="svn-tutorial.txt">how to use SVN</a>. Use the SVN version only if
you're interested in testing the latest changes.

<a name="repository"></a>
<h3>Debian/Ubuntu repositories</h3>

<p>For convenience to Debian and Ubuntu users, a package repository has been
set up by Bertram. The repository also contains
<a href="http://guichan.sourceforge.net/">Guichan</a>, because its latest
version isn't generally available yet.</p>

<p>To use this repository on Debian Testing, add the following line to your
sources.list file:</p>

<p><i>
deb http://bertram.ifrance.com ./<br/>
deb-src http://bertram.ifrance.com ./
</i></p>

<p>When you're on Ubuntu Dapper/Edgy, add the following:

<p><i>
deb http://bertram.ifrance.com/tmw-dapper ./
</i></p>

<p>Then to install TMW do:</p>

<p><b>apt-get update<br/>
apt-get install tmw</b></p>

<?php
  placeFooter();
?>
