<?php
// process.php
$postdata = file_get_contents ( "php://input" );
$request = json_decode ( $postdata );
$user = array('id'=>$request->username, 'role'=>'admin');
$data = array('id'=> $request->username, 'user'=>$user);
echo json_encode($data);
?>