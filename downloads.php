<?php
  include("includes/common.php");
  placeHeader("Downloads");
?>

<h3>Current client release</h3>

<div class="releases">
<div class="release"><img src="images/ico-win.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.23-win32.exe?download"><b>Windows installer</b> 0.0.23</a></div>
<div class="release"><img src="images/ico-src.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.23.tar.gz?download"><b>Source code</b> 0.0.23</a></div>
<div class="release"><img src="images/ico-osx.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.23-osx.dmg?download"><b>MacOS X package</b> 0.0.23</a></div>
<div class="release"><img src="images/ico-slackware.png"/> <a href="http://prdownloads.sourceforge.net/themanaworld/tmw-0.0.22.2-i686-1.tgz?download"><b>Slackware 11.0/Current package</b> 0.0.22.2</a></div>
<div class="release"><img src="images/ico-deb.png"/> <a href="#repository"><b>Debian/Ubuntu repositories</b></a> (out of date)</div>
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
development version (trunk) of the client this means to use the command:</p>

<pre>
svn co https://svn.sourceforge.net/svnroot/themanaworld/tmw/trunk tmw
</pre>

<p>For more instructions you can also read our small guide on
<a href="svn-tutorial.txt">how to use SVN</a>. Use the SVN version only if
you're interested in testing the latest changes.</p>
<p class="note">
<b>Note:</b> Currently trunk development is based on our own server which can be
found in the <i>tmwserv</i> module. If you're interested in the latest version
able to connect to eAthena, check out the <i>0.0</i> branch.
</p>

<a name="repository"></a>
<h3>Debian/Ubuntu repositories</h3>

<p>For convenience to Debian and Ubuntu users, several package repositories
have been set up by Bertram. The repositories also contain
<a href="http://guichan.sourceforge.net/">Guichan</a>, because its latest
version isn't generally available yet. Choose the right repository for your
system, and add the given line to your sources.list file:</p>

<h4>Debian Unstable</h4>

<pre>
deb http://bertram.ifrance.com/ debian unstable
</pre>

<h4>Ubuntu Edgy (i386 and AMD-64)</h4>

<pre>
deb http://bertram.ifrance.com/ ubuntu edgy
</pre>

<p>Then to install TMW do:</p>

<p><b>apt-get update<br/>
apt-get install tmw</b></p>

<?php
  placeFooter();
?>
