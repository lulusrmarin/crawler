<?php
require_once( "functions.php" );
require_once( "env.php" );
$conn = db($dbr); // New DB Connection

if( $_POST['message'] ) {
    $conn->query("INSERT INTO notes SET message = '{$_POST['message']}', created_on = NOW()");
}

$result = $conn->query("SELECT * FROM notes");
$rows = $result->fetch_all();

$i = 0;
foreach($rows as $row) {
    $data[$i]['messages']['note_id'] = $row[0];
    $data[$i]['messages']['message'] = $row[1];
    $i++;
}

if( $_POST ) { echo json_encode( $data ); }
if($conn->error) { echo $conn->error; }
?>

<?php if( !$_POST ): ?>
<link href="css/style.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/postit.js"></script>

</script>
<?php endif; ?>