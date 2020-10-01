<?php
$err = "";
$title = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (
        !isset($_GET["date"]) || !isset($_GET["id"]) ||
        empty($_GET["date"]) || !is_numeric($_GET["id"])
    )
        $err = "Not Found";
    else {
        require_once "classes/database.php";
        $data = $database->readCurrencies($_GET["date"], $_GET["id"]);
        if (count($data) == 0)
            $err = "Not Found";
        else {
            $title = htmlspecialchars($_GET["date"]);
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/styles/main.css">

        <title><?php echo $err != "" ? $err : $title  ?></title>
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-9 col-sm-12">
                    <?php
                    if ($err != "") echo "<h1 class='text-center'>$err</h1>";
                    else { ?>
                        <h1 class="text-center"><?php echo htmlspecialchars($_GET["date"]) ?></h1>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <?php
                                    foreach ($data[0] as $key => $v) {
                                        if ($key == "dataId" || $key == "Id") continue;
                                        echo "<th scope='col'>" . ucfirst($key) . "</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($data as $_ => $currency) {
                                    echo "<tr>";
                                    foreach ($currency as $key => $value) {
                                        if ($key == "dataId" || $key == "Id") continue;
                                        echo "<td>$value</td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php }  ?>
                </div>
            </div>
            <a class="btn btn-primary link" href="./index.php">Go Back</a>
        </div>
    </body>

    </html>
<?php } ?>