<?php
/**
 * $Id: UC002est.class.php 202 2008-11-10 12:01:40Z Gurdeep Singh $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 *
 * @author Gurdeep Singh  <gurdeepsingh03@gmail.com>
 * This is the class to register as a Instructor.
 */

if (isset($_POST["username"])) {

    require_once 'infinitymetrics/controller/UserManagementController.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

    try {
        $instructor = UserManagementController::registerInstructor($_POST["username"], $_POST["password"],
                                        $_POST["email"], $_POST["firstName"],$_POST["lastName"],$_POST["institution"],$_POST["projectName"]);


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
    echo header('Location: login.php?newUser='.$instructor->getFirstName()." ".$instructor->getLastName() );
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
