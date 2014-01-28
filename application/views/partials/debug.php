<div id="debug">
    <p>Debug information - only shown for admins</p>
    <br />

    <h5>Page Data</h5>
    <pre>
        <?php print_r($page_data); ?>
    </pre>

    <h5>Session Variables</h5>
    <pre>
        <?php print_r($session); ?>
    </pre>

    <h5>Cookie Variables</h5>
    <pre>
        <?php print_r($_COOKIE); ?>
    </pre>

    <h5>Server Variables</h5>
    <pre>
        <?php print_r($_SERVER); ?>
    </pre>
</div>