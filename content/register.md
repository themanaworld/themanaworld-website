+++
title = "Registration"
template = "page.html"

[extra]
main_class = "registration"
account_form = true
+++

<div id="register-section">
<h1>Account creation</h1>
<p>Welcome to The Mana World! With this form you can register for a new game account.</p>
<p>Please note that you will also need to <a href="https://wiki.themanaworld.org/wiki/Downloads" target="_blank">install a suitable client</a> to play this game.</p>

<div class="dialog"><b>NOTICE:</b> No email confirmation is necessary; you can log in to the game as soon as your account has been created.</div>

{{ event_notice() }}

<form id="register-form" novalidate>
<div class="field">
<label for="email">Email address <span class="optional">(optional)</span></label>
<p class="hint" id="email-hint">Only used to reset your password, should you lose access to your account. We will never give it to someone else or send you spam.</p>
<input name="email" type="email" maxlength="39" id="email" autocomplete="email" autocapitalize="none" spellcheck="false" aria-describedby="email-hint">
</div>

<div class="field">
<label for="user">Username</label>
<p class="hint" id="user-hint">4 to 23 characters, letters and numbers only. It is used to log in to the game server and never shown to other players.</p>
<input name="username" type="text" id="user" minlength="4" maxlength="23" pattern="^[a-zA-Z0-9]{4,23}$" title="4-23 characters, alphanumeric" autocomplete="username" autocapitalize="none" spellcheck="false" aria-describedby="user-hint" required>
<p class="field-error" id="username-taken" role="alert" hidden>This username is already taken. Please choose another username.</p>
</div>

<div class="exposed" role="alert" hidden>
<strong>WARNING: This password is compromised.</strong>
This password has previously appeared in a data breach. Please use a more secure alternative.
<a href="https://haveibeenpwned.com/Passwords" target="_blank" rel="noopener">verified by haveibeenpwned.com</a>
</div>

<div class="field">
<label for="password">Password</label>
<p class="hint" id="password-hint">8 to 23 characters, letters and numbers only. Case-sensitive. Please choose a hard-to-guess, unique password.</p>
<div class="password-box">
<input name="password" type="password" id="password" minlength="8" maxlength="23" pattern="^[a-zA-Z0-9]{8,23}$" title="8-23 characters, alphanumeric" autocomplete="new-password" aria-describedby="password-hint" required>
<button type="button" class="toggle-password" aria-pressed="false" aria-label="show password">👁</button>
</div>
</div>

{{ recaptcha_optin(id="register-consent") }}

<div class="error form-error" role="alert" hidden></div>
<button type="submit" disabled>Create account</button>
</form>

<h1>Registering without reCAPTCHA</h1>
<p>If you would rather not use reCAPTCHA, you may register by contacting us by email.</p>
<p>We will create a new account for you and associate it with the email address that you used to contact us.</p>
<address>registration@themanaworld.org</address>
</div>

<div id="register-success" tabindex="-1" hidden>
<h1>Thank you</h1>
<p>Your account has been successfully created.</p>
<h1>Next steps</h1>
<p>To start playing, <a href="https://wiki.themanaworld.org/wiki/Downloads" target="_blank">install a suitable client</a> and select the server <i>The Mana World</i>.</p>
<h1>Troubleshooting</h1>
<p>Sometimes, you may get an error if you try to login right after registering. Common messages include "Password Incorrect" and "Account not found".</p>
<p>Just allow the system a little longer to synchronize; doing a password reset is NOT necessary.</p>
</div>

<noscript>
<div class="dialog">JavaScript is required to complete registration (reCAPTCHA and form submission). You may also register by email as described above.</div>
</noscript>
