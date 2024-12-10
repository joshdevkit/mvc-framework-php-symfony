<style>
    h1 {
        font-size: 48px;
        margin: 0;
        color: #e74c3c;
    }

    p {
        font-size: 18px;
        margin: 10px 0 20px;
    }
</style>

<h1>404 - Not Found</h1>
<p><?= isset($errorMessage) ? htmlspecialchars($errorMessage) : 'The requested resource was not found.' ?></p>
<a href="/">Return to Home</a>