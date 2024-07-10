<div class="container">
    <div class="jumbotron text-danger">
        <h4><?= $errorMessages ?></h4>
        <?php if (isset($sqlQuery)) : ?>
            <p><strong>SQL Query:</strong></p>
            <pre><?= $sqlQuery ?></pre>
        <?php endif; ?>
        <p><a href="/">Back to Home</a></p>
    </div>
</div>