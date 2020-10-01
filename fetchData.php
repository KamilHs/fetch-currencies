<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="assets/styles/main.css">
    <link rel="stylesheet" href="assets/styles/fetchData.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                maxDate: new Date(),
                dateFormat: "dd.mm.yy"
            });
        });
    </script>
</head>
<?php
$errMessage = null;
$resultString = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["date"])  || empty(trim($_POST["date"]))) $errMessage = "Date is Required";
    else {
        require_once("classes/database.php");
        require_once "classes/dailyData.php";
        require_once "classes/xmlParser.php";
        require_once("constants.php");


        $response = @simplexml_load_file(BASE_URL . htmlspecialchars($_POST["date"]) . ".xml");
        if (!$response) $resultString = "Failed to Fetch";
        else {
            $resultString = $database->writeData((new DailyData(xmlParser::parse($response)))->getData());
        }
    }
}
?>

<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <form id="fetch-form" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
                    <div class="form-group text-center position-relative">
                        <label for="datepicker">Pick a date</label>
                        <input name="date" type="text" id="datepicker" autocomplete="off">
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="error"> <?php echo $errMessage ?></span>
                        <button class="btn btn-primary" type="submit">Fetch</button>
                    </div>
                </form>
            </div>
        </div>
        <a class="btn btn-primary link" href="./index.php">Go Back</a>
    </div>
    <div id="modal" class="<?php echo $resultString === null ? "hidden" : "" ?> ">
        <div class="modal-content">
            <h2><?php echo $resultString === "" ? "Successfully Fetched" : $resultString ?></h2>
            <div class="d-flex justify-content-end align-items-center">
                <button id="modal-close" class="btn btn-primary" type="button">Close</button>
            </div>
        </div>
    </div>
</body>

<script>
    const closeBtn = document.getElementById("modal-close");
    const modal = document.getElementById("modal");
    closeBtn.addEventListener("click", (e) => {
        modal.classList.add("hidden");
    })
</script>

</html>