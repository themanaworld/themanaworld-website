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
<div class="field">
<label for="email">Email address</label>
<p class="hint" id="email-hint">The email address of your TMW Legacy account. It will become your Vault username.</p>
<input name="email" type="email" maxlength="39" id="email" autocomplete="email" autocapitalize="none" spellcheck="false" aria-describedby="email-hint" required>
</div>

<div class="field">
<label for="user">Username</label>
<p class="hint" id="user-hint">Your TMW Legacy username.</p>
<input name="user" type="text" maxlength="39" id="user" autocomplete="username" autocapitalize="none" spellcheck="false" aria-describedby="user-hint" required>
</div>

<div class="field">
<label for="password">Password</label>
<p class="hint" id="password-hint">Your TMW Legacy password. Your Vault password will be set to the same password.</p>
<div class="password-box">
<input name="pass" type="password" maxlength="39" id="password" autocomplete="current-password" aria-describedby="password-hint" required>
<button type="button" class="toggle-password" aria-pressed="false" aria-label="show password">👁</button>
</div>
</div>

<div class="checkbox-field">
<input name="totp" type="checkbox" id="totp">
<label for="totp">Enable two-factor authentication (2FA)</label>
<p class="hint">You'll receive by email the secret to configure an authentication app, e.g. Google Authenticator. Once enabled, you will not be able to login without it.</p>
</div>

{{ recaptcha_optin(id="migration-consent") }}

<div class="error form-error" role="alert" hidden></div>
<button type="submit" disabled>Migrate account</button>
</form>

<div id="migration-success" tabindex="-1" hidden>
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
