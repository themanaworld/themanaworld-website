<?php
include('includes/common.php');
include('includes/news.php');
placeHeader("Home");
?>
<div>
<h3>The Mana World Project</h3><br>
<p style="font-size:14px;">The Mana World (TMW) is a serious effort to create an innovative free and open source MMORPG. TMW uses 2D graphics and aims to create a large and diverse interactive world. It is licensed under the GPL, making sure this game can't ever run away from you. </p>
<div class="read-more"><a class="more" href="/about.php">Read More >></a></div>
</div>
<div><br>
<h3>Recent News</h3>
</br></div>
<?php
echo printNews(1);
placeFooter();
?>
