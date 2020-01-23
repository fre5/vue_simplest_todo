<?php
$host = "";
$user = "";
$password = "";
$dbname = "";
$id = '';

$con = mysqli_connect($host, $user, $password,$dbname);

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
//$input = json_decode(file_get_contents('php://input'),true);


if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}


switch ($method) {
    case 'GET':
      $id = $_GET['id'];
      $sql = "select * from todo"; 
      break;


	case 'POST':

	$action = $_POST["action"];
	$name = $_POST["name"];
	$crossed = $_POST["crossed"];
	$deleted = $_POST["deleted"];

	if($action!=null)
	{
		$sql = "insert into todo (action) values ('$action')";
	}

	if($name!=null)
	{
		if($crossed!=null && $crossed=="0") { $sql = "update todo set crossed = 0 where action = ('$name')"; }
		else if($crossed!=null && $crossed=="1") { $sql = "update todo set crossed = 1 where action = ('$name')"; }

		else if($deleted!=null) { $sql = "delete from todo where crossed = 1"; }
	}

	break;

}

// run SQL statement
$result = mysqli_query($con,$sql);

// die if SQL statement failed
if (!$result) {
  http_response_code(404);
  die(mysqli_error($con));
}

if ($method == 'GET') {
    if (!$id) echo '[';
    for ($i=0 ; $i<mysqli_num_rows($result) ; $i++) {
      echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }
    if (!$id) echo ']';
  } elseif ($method == 'POST') {
    echo json_encode($result);
  } else {
    echo mysqli_affected_rows($con);
  }

$con->close();
