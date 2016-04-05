<?php

$user = 'your_sql_username';
$pass = 'your_sql_password';


// Dummy data for input. Uncomment the next three lines to run this script by itself.
//$destnidmap = array(65,65,65);
//$destmaptid = array(86,89,86);
//$term_reference_field_name = 'field_core_or_previou_utilized_r';
//$term_reference_field_name = 'field_ispcolumn';

/* $nid_that_do_not_exist and $tid_that_do_not_exist are populated with the contents of $destnidmap
* and $destmaptid respectively and then they are stripped to include only those pairs that do not exist
* In the database */
$nid_that_do_not_exist = array();
$tid_that_do_not_exist = array();

/* $nid_that_exist and $tid_that_exist are populated with nid, tid pairs that exist in the database */
$nid_that_exist = array();
$tid_that_exist = array();

// Set dbg equal to the integer 1 for debugging.
$dbg = 1;

// commenting stuff out for test*
// Acquire all of the node ids to be processed
foreach ($destnidmap as $i => $value) {
  array_push($nid_that_do_not_exist,$destnidmap[$i]);
}

// Acquire all of the taxonomy term ids to be processed
foreach ($destmaptid as $i => $value) {
  array_push($tid_that_do_not_exist,$destmaptid[$i]);
}

if($dbg == 1) {
  echo("Node ID that exist");
  echo("\r\n");
  foreach($nid_that_do_not_exist as $i => $value) {
     echo("hello");
     echo($nid_that_do_not_exist[$i].' '.$tid_that_do_not_exist[$i]);
   }
 }
 // end of commenting stuff out

// These are declared again in taxonomy-write-maps.drush.php , but it is called later
$table_data_taxonomy_tag_field_name = 'field_data_'.$term_reference_field_name;
$table_revision_taxonomy_tag_field_name = 'field_revision_'.$term_reference_field_name;
$field_taxonomy_term_name_tid = $term_reference_field_name.'_tid';

/*
// These are declared again in taxonomy-write-maps.drush.php , but it is called later
$table_data_taxonomy_tag_field_name = 'field_data_'.$term_reference_field_name;
$table_revision_taxonomy_tag_field_name = 'field_revision_'.$term_reference_field_name;
$field_taxonomy_term_name_tid = $term_reference_field_name.'_tid';
*/

try {
    $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
      foreach($dbh->query("SELECT `$table_data_taxonomy_tag_field_name`.`entity_id`,
`$table_data_taxonomy_tag_field_name`.`$field_taxonomy_term_name_tid`
from  `$table_data_taxonomy_tag_field_name`") as $row) {
//    foreach($dbh->query('SELECT * from `pan_node` LIMIT 10') as $row) {
        print_r($row);
        if($row['entity_id'] !== NULL) {
           array_push($nid_that_exist,$row['entity_id']);
          if($dbg == 1) {
           echo($row['entity_id']);
          }
        }
        if($row[$field_taxonomy_term_name_tid] !== NULL) {
          array_push($tid_that_exist,$row[$field_taxonomy_term_name_tid]);
          if($dbg == 1){
            echo($row[$field_taxonomy_term_name_tid]);
            echo("\r\n");
          }
        }
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

/*
// Acquire all of the existing nids and tids in the database for the target term reference field
$result = db_query('SELECT {'.$table_data_taxonomy_tag_field_name.'}.entity_id , {'.$table_data_taxonomy_tag_field_name.'}.'.$field_taxonomy_term_name_tid.'
FROM {'.$table_data_taxonomy_tag_field_name.'}');
foreach($result as $record) {
   if($record->entity_id !== NULL) {
   array_push($nid_that_exist,$record->entity_id);
     if($dbg == 1){
        echo($record->entity_id);
     }
   }
   if($record->$field_taxonomy_term_name_tid !== NULL) {
      array_push($tid_that_exist,$record->$field_taxonomy_term_name_tid);
      if($dbg == 1){
        echo($record->$field_taxonomy_term_name_tid);
        echo("\r\n");
       }
   }
 }
*/

 // commenting stuff out for debugging
 // Remove all of the node ids and term id pairs that already exist in the database table
 foreach($destnidmap as $i => $value_one) {
   foreach($nid_that_exist as $j => $value_two) {
     if( $destnidmap[$i] == $nid_that_exist[$j] && $destmaptid[$i] == $tid_that_exist[$j]) {
       if($dbg == 1) {
         echo("We are equal for ".$destnidmap[$i]." and ".$nid_that_exist[$j]);
         echo("\r\n");
       }
         unset($nid_that_do_not_exist[$i]);
         unset($tid_that_do_not_exist[$i]);

     } elseif ($destnidmap[$i] !== $nid_that_exist[$j] && $destmaptid[$i] !== $tid_that_exist[$j]) {

       if($dbg == 1) {
         echo("We are not equal for ".$destnidmap[$i]." and ".$nid_that_exist[$j]);
         echo("\r\n");
       }
     }
   }
 }

if($dbg == 1){
  echo("Node ID that exist");
  echo("\r\n");
  foreach($nid_that_do_not_exist as $i => $value) {
     echo($nid_that_do_not_exist[$i].' '.$tid_that_do_not_exist[$i]);
     echo("\r\n");
   }
 }

if($dbg == 1){
 print_r($destnidmap);
 print_r($destmaptid);
}

// Clear the existing arrays for the existing node ids and taxonomy id values
 foreach($destnidmap as $i => $value) {
   array_pop($destnidmap);
 }

 foreach($destmaptid as $i => $value) {
   array_pop($destmaptid);
 }

if($dbg == 1) {
 print_r($destnidmap);
 print_r($destmaptid);
}

 //end of commenting stuff out for debugging..

 /* Map the result to the original node id and term id mappings to have
  * a new set (for input to other functions) that excludes the node id and term id pair that already
  * occur in the database */
 // commenting stuff out for debugging
foreach($nid_that_do_not_exist as $i => $value) {
  array_push($destnidmap,$nid_that_do_not_exist[$i]);
}
foreach($tid_that_do_not_exist as $i => $value) {
  array_push($destmaptid,$tid_that_do_not_exist[$i]);
}

if($dbg == 1){
  print_r($destnidmap);
  print_r($destmaptid);
}
 // commenting stuff out for debugging
