<?php

// Dummy Data to use this script in isolation. Uncomment for use.
//$destnidmap = array(60,59);
//$destmaptid = array(13,52);

// Store the values for the unix timestamp of the node creation date
$nid_created_array = array();

// Set dbg to the integer 1 for debugging
$dbg = 1;


foreach($destnidmap as $i => $value) {
  try {
      $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
        foreach($dbh->query("SELECT `$pn`.`created` from `$pn`
        WHERE `$pn`.`nid` = '$destnidmap[$i]'") as $row) {
   //    foreach($dbh->query('SELECT * from `pan_node` LIMIT 10') as $row) {
          print_r($row);
          echo($row['created']);
          array_push($nid_created_array,$row['created']);
      }
      $dbh = null;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
}

/*
// Populate the unix timestamp array by pulling values from the node table
foreach($destnidmap as $i => $value) {
$result = db_query('SELECT {node}.created FROM {node} WHERE {node}.nid = '.$destnidmap[$i].'')->fetchObject();

array_push($nid_created_array,$result->created);
}
*/

if($dbg == 1){
  echo('Node id'.' '.'Creation Date');
  echo("\r\n");
  foreach($nid_created_array as $i => $value) {
    echo($destnidmap[$i].' '.$nid_created_array[$i]);
    echo("\r\n");
  }
}


// Add each node id, the corresponding taxonomy term ids, and the unix timestamp for each node id
foreach($destnidmap as $i => $value) {
  add_taxonomy_term_to_index($destnidmap[$i],$destmaptid[$i],0,$nid_created_array[$i], $user, $pass);
}

// Function to insert the taxonomy term into the taxonomy index
function add_taxonomy_term_to_index($nodeid,$tid,$sticky,$created, $user, $pass) {
  try {
      $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
        foreach($dbh->query("INSERT INTO `taxonomy_index` (nid, tid, sticky, created) VALUES ($nodeid, $tid, $sticky, $created)") as $row) {
      }
      $dbh = null;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }


/*
 $nid = db_insert('taxonomy_index')
        ->fields(array(
          'nid' => $nodeid,
          'tid' => $tid,
          'sticky' => $sticky,
          'created' => $created,
        ))
        ->execute();
*/
}




 ?>
