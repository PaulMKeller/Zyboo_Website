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
        $emailaddr = $firstname = $lastname = $nickname = $telno = "";
        
        validate_input();

        
        if ($errorsHaveOccured) {
            if ($websiteSubmission==FALSE) {
                echo "RETURNCODE=ERROR"
            }
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

            $sql = "EXEC sp_Zyboo_User_Insert @firstName=?, @lastName=?, @nickName=?, @telno=?, @emailAddr=?";
            $params = array($firstname, $lastname, $nickname, $telno, $emailaddr);
            $stmt = sqlsrv_query( $conn, $sql, $params);

            if( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true));
            }

            if ($websiteSubmission==FALSE) {
                echo "RETURNCODE=SUBMITTED"
            }
        }

        function clean_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }
        
        function validate_input() {
            global $emailaddr, $emailaddrErr, $firstname, $firstnameErr, $lastname, $lastnameErr, $telno, $telnoErr, $nickname, $nicknameErr $errorsHaveOccured, $websiteSubmission;
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST['websiteSubmission'])) {
                    $websiteSubmission = FALSE;
                } else {
                    $websiteSubmission = $_POST['websiteSubmission'];
                }
                
                if (empty($_POST['emailaddr'])) {
                    $emailErr = "Email is Required.";
                } else {
                    $email = clean_input($_POST['emailaddr']);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $emailErr = "Invalid email format.";
                    }
                }
                
                if ($emailaddrErr != "") {
                    $errorsHaveOccured = TRUE;
                }
            }
        }
        
        function formatErrors() {
            global $emailaddrErr;
            
            $formattedErrorText = "<ul>";
            $formattedErrorText .= "</ul>";
            
            return $formattedErrorText;
        }
        
        ?>
</body>

</html>