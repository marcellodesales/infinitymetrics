<?php
/**
 * @author Gurdeep Singh  <gurdeepsingh03@gmail.com>
 * This is the view to register as a Instructor.
 */

if (isset($_POST["username"])) {

    require_once 'infinitymetrics/controller/UserManagementController.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

    try {
        $instructor = UserManagementController::registerInstructor($_POST["username"], $_POST["password"],
                                        $_POST["email"], $_POST["firstName"],$_POST["lastName"],$_POST["projectName"],$_POST["institution"]);


    } catch (InfinityMetricsException $ime) {
        $errors = $ime->getErrorList();
    }
}
?>
<html><title>Instructor Registration</title>
<body>

  Instructor Registration<br><br>
<?php
if (isset($errors)) {
    echo "<font color='error'>";
    print_r($errors);
    echo "</font>";
} else
if (isset($instructor)) {
    echo header('Location: login.php?newUser='.$instructor->getName());
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
   Java.net Username : <input type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>"><BR><BR>
   Java.net Password : <input type="password" name="password"><BR><BR>
   Your First Name   : <input type="text" name="firstName"><BR><BR>
   Your Last Name    : <input type="text" name="lastName"><BR><BR>
   Email             : <input type="text" name="email"><BR><BR>
   Project Name      : <input type="text" name="projectName"><BR><BR>
   Institution       : <input type ="text" name ="institution"><BR><BR>
    <input type="submit" value="Register as Instructor">
</form>
</body>
</html>
