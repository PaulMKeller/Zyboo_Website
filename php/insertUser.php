<?php
/* Set Connection Credentials */
$serverName="db638520808.db.1and1.com";
$uid = "dbo638520808";
$pwd = "Register2016!";
$database = "db638520808";
$connectionInfo = array( "UID"=>$uid,
                         "PWD"=>$pwd,
                         "Database"=>$database,
                         "CharacterSet"=>"UTF-8");
//$connectionInfo = array( "Database"=>"db638520808", "UID"=>"dbo638520808", "PWD"=>"Register2016!");
 
/* Connect using SQL Server Authentication. */
$conn = sqlsrv_connect( $serverName, $connectionInfo);
 
if( $conn === false ) {
     echo "Unable to connect.</br>";
     die( print_r( sqlsrv_errors(), true));
}
 
/* TSQL Query */
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$nickName = $_POST['nickName'];
$telNo = $_POST['telNo'];
$emailAddr = $_POST['emailAddr'];

$tsql = "EXEC sp_Zyboo_User_Insert @firstName=?, @lastName=?, @nickName=?, @telNo=?, @emailAddr=?";
$params = array($firstName, $lastName, $nickName, $telNo, $emailAddr);
$stmt = sqlsrv_query( $conn, $tsql, $params);
 
if( $stmt === false ) {
     echo "Error in executing query.</br>";
     die( print_r( sqlsrv_errors(), true));
}

/* Process results */

$json = array();

do {
     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
     $json[] = $row;
     }
} while ( sqlsrv_next_result($stmt) );

/* Run the tabular results through json_encode() */
/* And ensure numbers don't get cast to trings */
//echo json_encode($json,<code>JSON_NUMERIC_CHECK</code>);

echo json_encode($json);

/* Free statement and connection resources. */
sqlsrv_free_stmt( $stmt);
sqlsrv_close( $conn);
 
?>