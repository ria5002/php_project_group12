<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }

        .footer {
            padding: 20px 0;
            background-color: #f1f1f1;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="content">
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Microwave Store. All rights reserved.</p>
    </footer>
</body>

</html>