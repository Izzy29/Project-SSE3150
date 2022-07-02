<?php
session_start();
$managerID = $_SESSION['managerID'];
$projectName = "";
$owners = "";
$funds = "";
$duration = "";
$mode = "";
$errors = "";
$num = "";

?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Project Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <?php

    if (isset($_POST['projName']) && isset($_POST['owner']) && isset($_POST['funds']) && isset($_POST['duration']) && isset($_POST['mode'])) {

        $projectName = $_POST['projName'];
        $owners = $_POST['owner'];
        $funds = $_POST['funds'];
        $duration = $_POST['duration'];
        $mode = $_POST['mode'];

        // Validate project name
        $input_name = trim($_POST["projName"]);
        if (empty($input_name)) {
            $name_err = "Please enter the project name.";
            echo $name_err;
        } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
            $name_err = "Please enter a valid project name.";
            echo $name_err;
        } else {
            $name = $input_name;
        }

        // Validate owner
        $input_owner = trim($_POST["owner"]);
        if (empty($input_owner)) {
            $owner_err = "Please enter the owner.";
            echo $owner_err;
        } elseif (!filter_var($input_owner, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
            $owner_err = "Please enter a valid owner.";
            echo $owner_err;
        } else {
            $owner = $input_owner;
        }

        // Validate funds
        $input_funds = trim($_POST["funds"]);
        if (empty($input_funds)) {
            $funds_err = "Please enter the funds amount.";
            echo $funds_err;
        } elseif (!ctype_digit($input_funds)) {
            $funds_err = "Please enter a positive integer value.";
            echo $funds_err;
        } else {
            $funds = $input_funds;
        }

        // Validate duration
        $input_duration = trim($_POST["duration"]);
        if (empty($input_duration)) {
            $duration_err = "Please enter the project duration.";
            echo $duration_err;
        } elseif (!ctype_digit($input_duration)) {
            $duration_err = "Please enter a positive integer value.";
            echo $duration_err;
        } else {
            $duration = $input_duration;
        }

        // Validate mode
        $input_mode = trim($_POST["mode"]);
        if (empty($input_mode)) {
            $mode_err = "Please select a mode.";
            echo $mode_err;
        } else {
            $mode = $input_mode;
        }

        // Check input errors before inserting into database
        if (empty($name_err) && empty($owner_err) && empty($funds_err) && empty($duration_err) && empty($mode_err)) {
            require_once "pdo.php";
            // Prepare an insert statement
            $sql = "INSERT INTO project (projectName, owner, funds, duration, modeId, manager_ID) VALUES (:name, :owner, :funds, :duration, :mode, :manageID)";

            if ($stmt = $pdo->prepare($sql)) {
                // Set parameters
                $param_name = $name;
                $param_owner = $owner;
                $param_funds = $funds;
                $param_duration = $duration;
                $param_mode = $mode;
                $param_manager = $managerID;

                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":name", $param_name);
                $stmt->bindParam(":owner", $param_owner);
                $stmt->bindParam(":funds", $param_funds);
                $stmt->bindParam(":duration", $param_duration);
                $stmt->bindParam(":mode", $param_mode);
                $stmt->bindParam(":manageID", $param_manager);

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    $sql = "SELECT projectID FROM project Where manager_ID = :manageID AND projectName = :name ";
                    $stmt = $pdo->prepare($sql);

                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":manageID", $param_manager);
                    $stmt->bindParam(":name", $param_name);

                    if ($stmt->execute()) {
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {
                            $projectID = $row['projectID'];
                        }
                        // Records created successfully. Redirect to landing page
                        $_SESSION['projectName'] = $projectName;
                        $_SESSION['projectID'] = $projectID;
                        $num = 2;
                        $errors = "Registration Successfully!";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        }
    }
    ?>
    <div class="container">
        <h1>Register Project</h1>
        <?php
        if ($num == 2) {
            echo '<p style="color:green">' . $errors;
        }
        ?>
        <form method="POST" class="form-group">
            <table class="table">
                <tr>
                    <td>Project name: </td>
                    <td><input type="text" name="projName" /></td>
                </tr>
                <tr>
                    <td>Owner: </td>
                    <td><input type="text" name="owner" /></td>
                </tr>
                <tr>
                    <td>Financial/Funds: </td>
                    <td><input type="text" name="funds" /></td>
                </tr>
                <tr>
                    <td>Project duration: </td>
                    <td><input type="text" name="duration" /> weeks</td>
                </tr>
                <tr>
                    <td>Mode: </td>
                    <td><select name="mode">
                            <option value="">--Select Mode--</option>
                            <option value="1">Insource</option>
                            <option value="2">Outsource</option>
                            <option value="3">Co-source</option>
                            <option value="4">Unspecififed</option>
                        </select></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Register"> <a href="index.php">Cancel</a></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>