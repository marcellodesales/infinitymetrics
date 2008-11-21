<?php

if (isset($_POST["username"])) {

    require_once 'infinitymetrics/controller/UserManagementController.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

    try {
        $student = UserManagementController::registerStudent($_POST["username"], $_POST["password"],
                                        $_POST["email"], $_POST["realName"], null, $_POST["studentSchoolId"],
                                        $_POST["leaderName"], $_POST["jn_project_name"]);

    } catch (InfinityMetricsException $ime) {
        $errors = $ime->getErrorList();
    }
}
?>
<html><title>Student Registration</title>
<body>

User registration<br>
<?php
if (isset($errors)) {
    echo "<font color='error'>";
    print_r($errors);
    echo "</font>";
} else
if (isset($student)) {
    echo header('Location: login.php?newUser='.$student->getName());
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    Java.net Username <input type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>"><BR>
    Java.net Password <input type="password" name="password"><BR>
    Your full name: <input type="text" name="realName"><BR>
    Email <input type="text" name="email"><BR>
    School Student ID <input type="text" name="studentSchoolId"><BR>
    Project name <input type="text" name="jn_project_name"><BR>
    Leader <input type="text" name="leaderName"><BR>
    <input type="submit" value="Register as Student">
</form>
</body>
</html>