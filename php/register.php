<!DOCTYPE html>
<html>
    <head>
        <title>www.gratuityp.com</title>
        <link rel="stylesheet" href="../css/main.css">
    </head>
    <body>
        <?php
        
        $errorsHaveOccured = FALSE;
        $websiteSubmission = FALSE;
        $emailErr = $firstnameErr = $lastnameErr = $countryErr = "";
        $email = $firstname = $lastname = $country = "";
        
        validate_input();
        
        echo "<img id=\"tipsImage\" src=\"../images/Tips_Jar.jpg\" />";
        
        if ($errorsHaveOccured) {
            if ($websiteSubmission==FALSE) {
                echo "RETURNCODE=ERROR"
            }
            echo "<h1>Thanks for trying to connect with Gratuityp.com</h1>";
            echo "<h3>Unfortunately your registration has Errors.</h3>";
            echo "Click back in your browser and correct the following errors:";
            echo "<br />";
            echo formatErrors();   
        } else {
            $serverName = "db638520808.db.1and1.com"; //serverName\instanceName
            $connectionInfo = array( "Database"=>"db638520808", "UID"=>"dbo638520808", "PWD"=>"Register2016!");
            $conn = sqlsrv_connect( $serverName, $connectionInfo);

            if( $conn ) {
                //echo "Connection established.<br />";
            }else{
                 echo "Connection to database could not be established.<br />";
                 die( print_r( sqlsrv_errors(), true));
            }

            $sql = "EXEC sp_gratuityp_registration_Insert @EmailAddress=?, @FName=?, @LName=?, @Country=?";
            $params = array($email, $firstname, $lastname, $country);
            $stmt = sqlsrv_query( $conn, $sql, $params);

            if( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true));
            }

            if ($websiteSubmission==FALSE) {
                echo "RETURNCODE=SUBMITTED"
            }
            echo "<h1>Thanks for registering your interest, we will contact you soon</h1>";
        }

        function clean_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }
        
        function validate_input() {
            global $email, $emailErr, $firstname, $firstnameErr, $lastname, $lastnameErr, $country, $countryErr, $errorsHaveOccured, $websiteSubmission;
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST['websiteSubmission'])) {
                    $websiteSubmission = FALSE;
                } else {
                    $websiteSubmission = $_POST['websiteSubmission'];
                }
                
                if (empty($_POST['email'])) {
                    $emailErr = "Email is Required.";
                } else {
                    $email = clean_input($_POST['email']);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $emailErr = "Invalid email format.";
                    }
                }

                if (empty($_POST['firstname'])) {
                    $firstnameErr = "First Name is Required.";
                } else {
                    $firstname = clean_input($_POST['firstname']);
                    if (!preg_match("/^[a-zA-Z ]*$/",$firstname)) {
                      $firstnameErr = "First Name can only contain letters and white space."; 
                    } elseif (strlen($firstname) > 50) {
                        $firstnameErr = "First Name cannot be longer than 50 characters.";
                    }
                }

                if (empty($_POST['lastname'])) {
                    $lastnameErr = "Last Name is Required.<br />";
                } else {
                    $lastname = clean_input($_POST['lastname']);
                    if (strlen($lastname) > 50) {
                        $lastnameErr = "Last Name cannot be longer than 50 characters.";
                    }
                }

                if (empty($_POST['country'])) {
                    $countryErr = "Country is Required.<br />";
                } else {
                    $country = clean_input($_POST['country']);
                    if (!preg_match("/^[a-zA-Z ]*$/",$country)) {
                      $countryErr = "Country can only contain letters and white space."; 
                    } elseif (strlen($country) > 50) {
                        $countryErr = "Country cannot be longer than 50 characters.";
                    }
                }
                
                if ($emailErr != "" or $firstnameErr != "" or $lastnameErr != "" or $countryErr != "") {
                    $errorsHaveOccured = TRUE;
                }
            }
        }
        
        function formatErrors() {
            global $emailErr, $firstnameErr, $lastnameErr, $countryErr;
            
            $formattedErrorText = "<ul>";
            $errors = array($emailErr, $firstnameErr, $lastnameErr, $countryErr);
            
            foreach ($errors as $value) {
                if (!$value == "") {
                    $formattedErrorText .= "<li>";
                    $formattedErrorText .= $value;
                    $formattedErrorText .= "</li>";
                }
            }
            
            $formattedErrorText .= "</ul>";
            
            return $formattedErrorText;
        }
        
        ?>
    </body>
</html>