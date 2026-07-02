+++
title = "Migration"
template = "page.html"

[extra]
main_class = "migration"
account_form = true
+++

<div id="migration-intro">
<h1>Account Migration</h1>
<p>Use this form to migrate a TMW Legacy account to Vault.</p>
<p>Please note you may only have <b>one</b> Vault account.</p>
<p>Once complete, you'll need the Mana Launcher to login.</p>
</div>

<form id="migration-form" novalidate>
<h1>TMW Legacy Data</h1>
<p>Do note it validates the username, email and password.</p>
<p>Your Vault Password will be set to the same password you've used in TMW Legacy. Your Vault Username is your email.</p>
<p>If you opt for 2 Factor Authentication, you'll receive by email the secret to configure an authentication app e.g. Google Authenticator.</p>
<p>Once enabled, you will not be able to login without it.</p>
<label for="email">Enter your email address:</label>
<input name="email" type="email" maxlength="39" id="email" placeholder="you@mail.com" autocomplete="email" required>
<label for="user">Enter your username:</label>
<input name="user" type="text" maxlength="39" id="user" placeholder="username" autocomplete="username" required>
<label for="password">Enter your password:</label>
<input name="pass" type="password" maxlength="39" id="password" placeholder="password" autocomplete="current-password" required>
<label for="totp">Enable 2FA?</label>
<input name="totp" type="checkbox" id="totp">

{{ recaptcha_optin() }}

<div class="error form-error" role="alert" hidden></div>
<button type="submit" disabled>Submit &rarr;</button>
</form>

<div id="migration-success" hidden>
<h1>Migration process started</h1>
<p>Your account migration has been requested.</p>
<h1>Next steps</h1>
<p>If everything went well, you'll be able to login on Mana Launcher using the provided email and password.</p>
<p>If you enabled 2FA, you'll also need the token which has been sent to your email.</p>
<br><br><br>
<h1>Can't find the account you were looking for?</h1>
<p>If data inserted here is incorrect or a Vault account already exists bound to that email, the migration will not happen.</p>
<h1>Still need help?</h1>
<p>Feel free to <a href="/support/">contact us</a> for further assistance.</p>
</div>

<h1>Proceeding without reCAPTCHA</h1>

If you would rather not use reCAPTCHA, you may recover your account by contacting us by email.

<address>support@themanaworld.org</address>

<noscript>
<div class="dialog">JavaScript is required for account migration (reCAPTCHA and form submission). You may also contact us by email as described above.</div>
</noscript>
