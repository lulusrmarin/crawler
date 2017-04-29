<?php
require_once( "functions.php" );
require_once( "env.php" );
$conn = db($dbr); // New DB Connection

$where = "";
// Handle User Interaction
if($_POST) {
    if ( $_POST['message'] ) {
        $conn->query("INSERT INTO notes SET message = '{$_POST['message']}', created_on = NOW(), x = {$_POST['x']}, y = {$_POST['y']}");
        $result = $conn->query("SELECT LAST_INSERT_ID() FROM notes;");
        $count = $result->fetch_row();
        $where = "WHERE note_id = '{$count[0]}'" ;
        $data['where'] = $where;
        $data['count'] = $count[0];
    }

    if( $_POST[ 'del' ] ) {
        $conn->query("DELETE FROM notes WHERE note_id = {$_POST[ 'del' ]}");
    }

    if( $_POST[ 'update' ] ) {
        $conn->query("UPDATE notes SET x = {$_POST[ 'x' ]}, y = {$_POST[ 'y' ]} WHERE note_id = {$_POST[ 'update' ]}");
    }
}

$result = $conn->query("SELECT * FROM notes {$where}");
$rows = $result->fetch_all();

$i = 0;
foreach($rows as $row) {
    $data['messages'][$i]['note_id'] = $row[0];
    $data['messages'][$i]['message'] = $row[1];
    $data['messages'][$i]['x'] = $row[3];
    $data['messages'][$i]['y'] = $row[4];
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
<?php endif; ?>