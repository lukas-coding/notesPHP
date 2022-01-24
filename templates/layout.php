<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/templates/css/style.css">
    <title>Strona w PHP</title>
</head>

<body>
    <nav class="nav">
        <h1 class="nav__h1">notes PHP</h1>
    </nav>

    <main class="main">
        <section class="main_section">
            <?php include_once("templates/pages/$page.php") ?>
        </section>
    </main>
</body>

</html>