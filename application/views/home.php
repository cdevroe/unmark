
<?php $csrf_token = $_SESSION['csrf_token']; ?>

<form method="post" action="/login" class="form-inline">
    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php print $csrf_token; ?>">
    <input type="text" class="input-small" name="email" id="email" placeholder="Email Address">
    <input type="password" class="input-small" name="password" id="password" placeholder="Password">
    <input type="submit" value="Log in" name="login" id="login" class="btn">
</form>