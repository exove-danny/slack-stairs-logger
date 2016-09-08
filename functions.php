<?php
// prints out the list of all standings so far
function print_list($conn){
  echo "Here are the standings so far:\r\n";
  $result = mysqli_query($conn, "SELECT username, count(*) as total FROM stairs GROUP BY username ORDER BY total DESC, username ASC");
  $counter = 0;
  $last_score = 99999999;
  while($row = mysqli_fetch_array($result)){
    $counter++;
    $index = "-";
    $suffix = "times";
    if($row['total'] < $last_score){
      $last_score = $row['total'];
      $index = $counter;
    }

    if($row['total'] == 1)
      $suffix = "time";

    echo "*{$index}.* {$row['username']} _{$row['total']} {$suffix}_\r\n";
  }
}

// prints a random wait message for the current user
function print_wait($username){
  $outputs = array();
  $outputs[] = "Oops, you already logged one entry in the last 15 minutes,  _*{$username}*_. Take some rest before climbing the stairs again!\r\n";
  $outputs[] = "Calm down,  _*{$username}*_ ! One entry in 15 minutes is enough, don't overdo it.\r\n";
  $outputs[] = "Take a break,  _*{$username}*_ , you can log only once per 15 minutes.\r\n";

  echo $outputs[array_rand($outputs, 1)];
}

// prints a random success message for the current user
function print_success($username){
  $outputs = array();
  $outputs[] = "Good job, _*{$username}*_ ! Do you feel fitter already?\r\n";
  $outputs[] = "There you go, _*{$username}*_ ! How does that feel?\r\n";
  $outputs[] = "Someone has been working out... that was some awesome stairs climbing, _*{$username}*_ !\r\n";

  echo $outputs[array_rand($outputs, 1)];
}

// prints the help message
function print_help(){
  echo "You gave an invalid command\r\n";
  echo "Valid commands are:\r\n";
  echo "*/stairs log* _to log your entry_\r\n";
  echo "*/stairs list* _to see current standings_\r\n";
}

// prints the general error message
function print_error(){
  echo "Unfortunately something went wrong. Please check your settings.\r\n";
}
