<?php
/*
Plugin Name: Slack Stairs Logger
Description: Backend implementation of the Slack /stairs command
Author: Exove
Version: 1.0.0
*/
require_once('config.php');
require_once('functions.php');

$conn = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
if(!$conn){
  die("connection error");
}

mysqli_select_db($conn, $config['db_name']);

// run the script only if the $_POST array is set and the Slack secret is correct
if($_POST && $_POST['token'] == $config['slack_secret']){
  $username = filter_var(trim(strtolower($_POST['user_name'])), FILTER_SANITIZE_STRING);
  $command = filter_var(trim(strtolower($_POST['text'])), FILTER_SANITIZE_STRING);

  if($command == "log"){
    // try to log the entry to the database
    $result = mysqli_query($conn, "SELECT * FROM stairs WHERE username = '".$username."' and date >= NOW() - INTERVAL 15 MINUTE");
    if($result->num_rows == 0){
      mysqli_query($conn,"INSERT INTO stairs VALUES (NULL,'".$username."',NOW())");
      print_success($username);
    }
    else{
      print_wait($username);
    }
  }
  else if($command == "list"){
    // show the list of current entries
    print_list($conn);
  }
  else{
    // invalid command, show help
    print_help();
  }

  die();
}

print_error();
