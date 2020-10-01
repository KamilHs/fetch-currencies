<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/styles/main.css">
    <link rel="stylesheet" href="assets/styles/home.css">

</head>

<body>
    <div class="container">
        <div class="row flex-column align-items-center">
            <?php
            require_once "classes/database.php";
            $dates = $database->readAllDates();
            if (is_array($dates) && count($dates) > 0) { ?>
                <h1 class="text-center my-5">Fetched Data</h1>
                <ul class='date-list text'>
                    <?php foreach ($dates as $_ => $date) { ?>
                        <li class='date-list-item'>
                            View currencies for
                            <a href="./show.php?date=<?php echo $date["date"] ?>&id=<?php echo $date["Id"] ?>"> <?php echo $date["date"] ?></a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else {
                echo "<h1>No Fetched Data Yet</h1>";
            }
            ?>
            <a class="btn btn-primary link" href="./fetchData.php">Fetch Data</a>
        </div>
    </div>
</body>

</html>