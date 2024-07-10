<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            background-color: #fff;
        }
        h1 {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Database Connection Error</h1>
        <p>There was an issue connecting to the database.</p>
        <p><strong>Error:</strong> <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</body>
</html>
