+++
title = "Registration"
template = "page.html"
aliases = ["registration.php"]

[extra]
main_class = "registration"
account_form = true
+++

# Account creation

Welcome to The Mana World! With this form you can register for a new game account.

Please note that you will also need to <a href="https://wiki.themanaworld.org/wiki/Downloads" target="_blank">install a suitable client</a> to play this game.

<br>
<div class="dialog"><b>NOTICE:</b> Accounts may take up to 15 minutes for automatic activation. No email confirmation is necessary.</div>

{{ event_notice() }}

<form id="register-form" novalidate>
<h1>Email address</h1>
<p>We will never give your email address to someone else or send you spam.</p>
<p>Providing an email address is entirely optional but it is the only way to request a password reset, should you loose access to your account.
If you did not provide an email address you will be unable to perform password resets.</p>
<label for="email">Enter your email (optional):</label>
<input name="email" type="email" maxlength="39" id="email" placeholder="your@email.com" autocomplete="email">

<h1>Username</h1>
<p>Your username is used to log in to the game server. It is never shared with other players: only you see this name.</p>
<p>It must contain between 4 and 23 characters. Letters and numbers only.</p>
<div class="error taken" id="username-taken" hidden>
<h2>Username taken</h2>
<p>Please choose another username.</p>
</div>
<label for="user">Choose a username:</label>
<input name="username" type="text" id="user" placeholder="type your username here" minlength="4" maxlength="23" pattern="^[a-zA-Z0-9]{4,23}$" title="4-23 characters, alphanumeric" autocomplete="username" required>

<h1>Password</h1>
<p>Please choose a hard-to-guess password.</p>
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
<button type="submit" disabled>Create account</button>
</form>

<div id="register-success" hidden>
<h1>Thank you</h1>
<p>Your account has been successfully created.</p>
<h1>Next steps</h1>
<p>To start playing, <a href="https://wiki.themanaworld.org/wiki/Downloads" target="_blank">install a suitable client</a> and select the server <i>The Mana World</i>.</p>
<h1>Troubleshooting</h1>
<p>Sometimes, you may get an error if you try to login right after registering. Common messages include "Password Incorrect" and "Account not found".</p>
<p>Just allow the system a little longer to synchronize; doing a password reset is NOT necessary.</p>
</div>

<h1>Registering without reCAPTCHA</h1>

If you would rather not use reCAPTCHA, you may register by contacting us by email.

We will create a new account for you and associate it with the email address that you used to contact us.

<address>registration@themanaworld.org</address>

<noscript>
<div class="dialog">JavaScript is required to complete registration (reCAPTCHA and form submission). You may also register by email as described above.</div>
</noscript>
