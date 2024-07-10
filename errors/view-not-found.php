<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View File Not Found</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f2f2; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background-color: #fff; border: 1px solid #ccc; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        p { color: #666; }

        .link {
            border: 2px solid black;
            padding: .4em;
            text-decoration: none;
            border-radius: 12px;
        }
        .link:hover{
            background-color: red;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Error: View File Not Found</h2>
        <p><?php echo $message; ?></p>
        <p>Go back to <a  class="link" href="/">Homepage</a></p>
    </div>
</body>
</html>
