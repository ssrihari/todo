<?php

if( isset($_POST['name']) && isset($_POST['id']) ) {
  $list_id = $_POST['id'];
  $list_name = $_POST['name'];
  echo "saved $list_id - $list_name";
  addList($list_id, $list_name);
  ($list_name == "") ? deleteList($list_id) : addList($list_id, $list_name);
}

if( isset($_GET['all']) ) {
  allLists();
}

function connect() {
  $host="localhost";
  $username="root";
  $password="srihari";
  $db_name="todo";

  $con = mysql_connect("$host", "$username", "$password")or die("cannot connect");
  mysql_select_db("$db_name")or die("cannot select DB");
  return $con;
}

function mysql_fetch_all($result) {
  $rows = array();
  while($r = mysql_fetch_assoc($result)) {
    $rows[] = $r;
  }
  print json_encode($rows);
}

function addList($id, $name) {
  $table_name="lists";
  $con = connect();
  $query = "INSERT INTO $table_name (id, name) VALUES ($id, '$name') ON DUPLICATE KEY UPDATE name='$name'";
  mysql_query($query);
  mysql_close($con);
}

function deleteList($id) {
  $table_name="lists";
  $con = connect();
  $query = "DELETE FROM $table_name WHERE id=$id";
  mysql_query($query);
  mysql_close($con);
}

function allLists() {
  $table_name="lists";
  $con = connect();
  $query = "SELECT * FROM $table_name";
  $result = mysql_query($query);
  $res = mysql_fetch_all($result);
  mysql_free_result($result);
  mysql_close($con);
  return $res;
}
?>
