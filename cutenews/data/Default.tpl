<?PHP
///////////////////// TEMPLATE Default /////////////////////
$template_active = <<<HTML
<div class="news">
  <div class="news_date">{date} by {author}</div>
  <h3>{title}</h3>
<!--<div class="news_avatar">{avatar}</div>-->
  <div class="news_body"><p>{short-story}</p></div>
</div>
HTML;


$template_full = <<<HTML
<table border="0" width="420" cellspacing="1" cellpadding="3">
<tr>
<td width="100%" style="text-align: justify">
<b>{title}</b>
</td>
</tr>
<tr>
<td width="100%" style="text-align: justify">
<font style="font-family:georgia, verdana, arial, sans-serif;	color:#666; font-size:14;">{full-story}</font></td>
</tr>
<tr>
<td width="100%">
<table border="0" style="border-top: 1px dotted #f2f3f3" width="408" cellspacing="0">
<tr>
<td width="220"><i><font style="font-family:georgia, verdana, arial, sans-serif; font-size:11; color:black;">{date} by {author-name}</font></i><br> </td
</tr>
</table>
</td>
</tr>
</table>
<br />
HTML;


$template_comment = <<<HTML
 <table border="0" width="400" height="40" cellspacing="" cellpadding="3">
    <tr>
      <td height="1" style="border-bottom-style: solid;border-bottom-width: 1px; border-bottom-color: black;">by <b>{author}</b> @ {date}</td>
    </tr>
    <tr>
      <td height="40" valign="top" bgcolor="#F9F9F9" >{comment}</td>
    </tr>
  </table>
<br>
HTML;


$template_form = <<<HTML
  <table border="0" width="342" cellspacing="0" cellpadding="0">
    <tr>
      <td width="49" height="1">
name:
      </td>
      <td width="289" height="1"><input type="text" name="name" tabindex="1"></td>
    </tr>
    <tr>
      <td width="49" height="1">
mail:
      </td>
      <td width="289" height="1"> <input type="text" name="mail" tabindex="2"> (optional)</td>
    </tr>
  </center>
    <tr>
      <td width="51" height="1">
        <p style="text-align: left;">smile:</p>
      </td>
<center>
      <td width="291" height="1" >
{smilies}
      </td>
    </tr>
    <tr>
      <td width="340" height="1" colspan="2"> <textarea cols="40" rows="6" name="comments" tabindex="3"></textarea><br />
        <input type="submit" name="submit" value="Add My Comment" accesskey="s">
<input type=checkbox name=CNremember  id=CNremember value=1> <label for=CNremember>Remember Me</label> |
  <a href="javascript:CNforget();">Forget Me</a>
      </td>
    </tr>
  </table>

HTML;


$template_prev_next = <<<HTML
<p style="text-align: center;">[prev-link]<< Previous[/prev-link] {pages} [next-link]Next >>[/next-link]</p>
HTML;
$template_comments_prev_next = <<<HTML
<p style="text-align: center;">[prev-link]<< Older[/prev-link] ({pages}) [next-link]Newest >>[/next-link]</p>
HTML;
?>
