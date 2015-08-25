<div id="error-wrapper">
    <div id="error-icon"><img src="/assets/images/icons/large_x.png" /></div>
    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'production'): ?>
        <h1>An unexpected error has occured</h1>
        <p>The issue has been logged and will be taken care of shortly.</p>
    <?php else: ?>
        <h1>A PHP Error was encountered</h1>
        <p>Severity: <?php echo $severity; ?></p>
        <p>Message:  <?php echo $message; ?></p>
        <p>Filename: <?php echo $filepath; ?></p>
        <p>Line Number: <?php echo $line; ?></p>
    <?php endif; ?>
</div>