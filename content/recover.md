+++
title = "Account recovery"
template = "page.html"

[extra]
main_class = "recovery"
account_form = true
+++

<div id="recovery-section">
<h1>Account Recovery</h1>
<p>Use this form if you forgot your username or password.</p>
<p>If it matches any account we have on file you will receive a message containing the list of your account usernames
along with a password reset link, should you wish to reset your password.</p>

<form id="recovery-form" novalidate>
<div class="field">
<label for="email">Email address</label>
<p class="hint" id="email-hint">The email address that was used to register your account(s).</p>
<input name="email" type="email" maxlength="39" id="email" autocomplete="email" autocapitalize="none" spellcheck="false" aria-describedby="email-hint" required>
<p class="field-error" id="email-not-found" role="alert" hidden>We were not able to find any accounts associated with this email address.</p>
</div>

{{ recaptcha_optin(id="recovery-consent") }}

<div class="error form-error" role="alert" hidden></div>
<button type="submit" disabled>Recover account</button>
</form>

<h1>Proceeding without reCAPTCHA</h1>
<p>If you would rather not use reCAPTCHA, you may recover your account by contacting us by email.</p>
<address>support@themanaworld.org</address>
</div>

<div id="recovery-sent" tabindex="-1" hidden>
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
<h1>Password reset</h1>
<p>Set a new password for one of your accounts.</p>

<form id="reset-form" novalidate>
<div class="field">
<label for="user">Username</label>
<p class="hint" id="user-hint">Your password reset link is <em>only</em> valid for account usernames listed in the email that was sent to you.</p>
<input name="username" type="text" id="user" minlength="4" maxlength="23" pattern="^[a-zA-Z0-9]{4,23}$" title="4-23 characters, alphanumeric" autocomplete="username" autocapitalize="none" spellcheck="false" aria-describedby="user-hint" required>
</div>

<div class="exposed" role="alert" hidden>
<strong>WARNING: This password is compromised.</strong>
This password has previously appeared in a data breach. Please use a more secure alternative.
<a href="https://haveibeenpwned.com/Passwords" target="_blank" rel="noopener">verified by haveibeenpwned.com</a>
</div>

<div class="field">
<label for="password">New password</label>
<p class="hint" id="password-hint">8 to 23 characters, letters and numbers only. Case-sensitive. Please choose a hard-to-guess, unique password.</p>
<div class="password-box">
<input name="password" type="password" id="password" minlength="8" maxlength="23" pattern="^[a-zA-Z0-9]{8,23}$" title="8-23 characters, alphanumeric" autocomplete="new-password" aria-describedby="password-hint" required>
<button type="button" class="toggle-password" aria-pressed="false" aria-label="show password">👁</button>
</div>
</div>

{{ recaptcha_optin(id="reset-consent") }}

<div class="error form-error" role="alert" hidden></div>
<button type="submit" disabled>Reset my password</button>
</form>

<h1>Proceeding without reCAPTCHA</h1>
<p>If you would rather not use reCAPTCHA, you may recover your account by contacting us by email.</p>
<address>support@themanaworld.org</address>
</div>

<div id="reset-success" tabindex="-1" hidden>
<h1>The deed is done</h1>
<p>The password of account <q id="reset-account"></q> has been reset.</p>
<h1>Next steps</h1>
<p>To start playing, <a href="https://wiki.themanaworld.org/wiki/Downloads" target="_blank">install a suitable client</a> and select the server <i>The Mana World</i>.</p>
</div>

<div id="reset-expired" tabindex="-1" hidden>
<h1>Expired link</h1>
<p>This password reset link has expired or is invalid.</p>
<p>Keep in mind that emailed links are only valid for 60 minutes.</p>
<h1>Start over</h1>
<p>You may try again in 5 minutes: <a href="/recover/">account recovery</a></p>
</div>

<noscript>
<div class="dialog">JavaScript is required for account recovery (reCAPTCHA and form submission). You may also contact us by email as described above.</div>
</noscript>
