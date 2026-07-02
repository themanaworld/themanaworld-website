+++
title = "Account recovery"
template = "page.html"
aliases = ["recover/username"]

[extra]
main_class = "recovery"
account_form = true
+++

<div id="recovery-intro">
<h1>Account Recovery</h1>
<p>Use this form if you forgot your username or password.</p>
<p>If it matches any account we have on file you will receive a message containing the list of your account usernames
along with a password reset link, should you wish to reset your password.</p>
</div>

<form id="recovery-form" novalidate>
<h1>Email address</h1>
<p>The email address that was used to register your account(s).</p>
<div class="error not-found" id="email-not-found" hidden>
<h2>Not found</h2>
<p>We were not able to find any accounts associated with this email address.</p>
</div>
<label for="email">Enter your email address:</label>
<input name="email" type="email" maxlength="39" id="email" placeholder="you@mail.com" autocomplete="email" required>

{{ recaptcha_optin() }}

<div class="error form-error" role="alert" hidden></div>
<button type="submit" disabled>Recover account</button>
</form>

<div id="recovery-sent" hidden>
<h1>Recovery process started</h1>
<p>An email was sent with the list of your accounts.</p>
<h1>Next steps</h1>
<p>If you wish to reset the password of your accounts, click the provided link in the email you received.</p>
<p>This link is only valid once: if you wish to reset more than one password you will have to repeat the process.</p>
<br><br><br>
<h1>Can't find the account you were looking for?</h1>
<p>Double-check the email address you entered; You might have used a different address when you created the account or you might have omitted to provide an email address.</p>
<h1>Still need help?</h1>
<p>Feel free to <a href="/support/">contact us</a> for further assistance.</p>
</div>

<div id="reset-section" hidden>
<form id="reset-form" novalidate>
<h1>Username</h1>
<p>Your password reset link is <em>only</em> valid for account usernames listed in the email that was sent to you.</p>
<label for="user">Enter a username:</label>
<input name="username" type="text" id="user" placeholder="type your username here" minlength="4" maxlength="23" pattern="^[a-zA-Z0-9]{4,23}$" title="4-23 characters, alphanumeric" autocomplete="username" required>

<h1>Password</h1>
<p>Please choose a new, hard-to-guess password.</p>
<p>It must contain between 8 and 23 characters. Letters and numbers only. Case-sensitive.</p>
<div class="exposed" hidden>
<h2>WARNING: This password is compromised</h2>
This password has previously appeared in a data breach. Please use a more secure alternative.
<a href="https://haveibeenpwned.com/Passwords" target="_blank" rel="noopener">verified by haveibeenpwned.com</a>
</div>
<div class="pass-box">
<label for="password">Choose a unique password:</label>
<input name="password" type="password" id="password" placeholder="type your password here" minlength="8" maxlength="23" pattern="^[a-zA-Z0-9]{8,23}$" title="8-23 characters, alphanumeric" autocomplete="new-password" required>
<span role="button" title="show password" aria-label="toggle password visibility" aria-pressed="false"></span>
</div>
<div class="pass-box">
<label for="password2">Confirm your password:</label>
<input name="password2" type="password" id="password2" placeholder="type your password again" minlength="8" maxlength="23" pattern="^[a-zA-Z0-9]{8,23}$" title="8-23 characters, alphanumeric" autocomplete="new-password" required>
<span role="button" title="show password" aria-label="toggle password visibility" aria-pressed="false"></span>
</div>

{{ recaptcha_optin() }}

<div class="error form-error" role="alert" hidden></div>
<button type="submit" disabled>Reset my password</button>
</form>
</div>

<div id="reset-success" hidden>
<h1>The deed is done</h1>
<p>The password of account <q id="reset-account"></q> has been reset.</p>
<h1>Next steps</h1>
<p>To start playing, <a href="https://wiki.themanaworld.org/wiki/Downloads" target="_blank">install a suitable client</a> and select the server <i>The Mana World</i>.</p>
</div>

<div id="reset-expired" hidden>
<h1>Expired link</h1>
<p>This password reset link has expired or is invalid.</p>
<p>Keep in mind that emailed links are only valid for 60 minutes.</p>
<h1>Start over</h1>
<p>You may try again in 5 minutes: <a href="/recover/password/">account recovery</a></p>
</div>

<h1>Proceeding without reCAPTCHA</h1>

If you would rather not use reCAPTCHA, you may recover your account by contacting us by email.

<address>support@themanaworld.org</address>

<noscript>
<div class="dialog">JavaScript is required for account recovery (reCAPTCHA and form submission). You may also contact us by email as described above.</div>
</noscript>
