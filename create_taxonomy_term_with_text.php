<?php
// Specify username and password for database
$user = 'your_sql_username';
$pass = 'your_sql_password';


// Specify the vocabulary by ID. See the taxonomy_vocabulary SQL table for your Drupal Installation.
$specified_vid = 2;
// Set debugging by seeting to integer 1
$dbg = 1;

/*
// Acquire all existing taxonomy name, term ids, and vocabulary ids
*/
// Uncomment dummy input data when not running with select_text_nid.drush.php
// $nid_array = array('59','59','60','60','55','33');
// $field_text_field_name_value_array = array('Mars Probe','Mars Probe','Research Mission','Observatory','Mars Probe','Observatory');

// An array to hold all taxonomy names stored for a particular vocabulary
$taxonomy_name = array();
// An array to hold all of the term ids stored for a particular vocabulary
$tid = array();
// A debuggin array to hold all of the vocabulary ids for each taxonomy term
$vid = array();

try {
    $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
      foreach($dbh->query("SELECT `taxonomy_term_data`.`name`, `taxonomy_term_data`.`tid`, `taxonomy_term_data`.`vid` from `taxonomy_term_data` WHERE `taxonomy_term_data`.`vid` = '$specified_vid'") as $row) {
//    foreach($dbh->query('SELECT * from `pan_node` LIMIT 10') as $row) {
        print_r($row);
        echo($row['name']);
        array_push($taxonomy_name,$row['name']);
        echo($row['tid']);
        array_push($tid,$row['tid']);
        echo($row['vid']);
        array_push($vid,$row['vid']);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
/*
// SQL Query To find the existing taxonomy term names, term ids, and vocabulary ids for a specified_vid
$result = db_query('SELECT {taxonomy_term_data}.name, {taxonomy_term_data}.tid, {taxonomy_term_data}.vid FROM {taxonomy_term_data}
WHERE {taxonomy_term_data}.vid = '.$specified_vid.'');
// Result is returned as a iterable object that returns a stdClass object on each iteration
foreach ($result as $record) {
  // Perform operations on $record->title, etc. here.
  if($dbg == 1) {
    echo($record->name);
    echo("\r\n");
  }
  array_push($taxonomy_name, $record->name);
  array_push($tid, $record->tid);
  array_push($vid, $record->vid);

}
*/

if($dbg == 1) {
  echo("\r\n");
  echo("\r\n");
  echo("The existing taxonomy terms are");
  echo("\r\n");
  foreach($taxonomy_name as $i => $value) {
    echo($tid[$i]." ".$taxonomy_name[$i]." ".$vid[$i]);
    echo("\r\n");
  }
  echo("\r\n");
}

// Map to hold all of names from the text field
$field_text_field_name_value_array_map = array();
// Map to hold all of the node ids for each of the names of the text field
$nid_array_map = array();
// Array to hold the node ids that have a text field that has a name that matches an existing taxonomy name for a specified vid
$nid_array_match = array();
// Array to hold all of the existing taxonomy names from the specifed vid that have the same name as the text field
$match_taxonomy_name = array();
// Array to hold all of the existing taxonomy term ids from the specifed vid that have the same name as the text field
$match_tid = array();
// Final collection array for all processed taxonomy names created or mapped to from the text field
$destmap_taxonomy_name = array();
// Final collection array for node ids for all processed taxonomy names created or mapped to from the text field
$destnidmap = array();
// Final collection array for taxonomy term ids for all processed taxonomy names created or mapped to from the text field
$destmaptid = array();

// Populate the maps for the names and node ids for the text field
foreach($field_text_field_name_value_array as $i => $value) {
  array_push($field_text_field_name_value_array_map,$field_text_field_name_value_array[$i]);
  array_push($nid_array_map,$nid_array[$i]);
}

/* Find the names from the text field that match the existing taxonomy names for a specified vocabulary
* Match the term ids from the existing taxonomy names that correspond to names that match the text field to their node ids */
foreach( $field_text_field_name_value_array as $i => $value_one) {
  foreach($taxonomy_name as $j => $value_two) {
   if($specified_vid == $vid[$j]) {
    if( $field_text_field_name_value_array[$i] == $taxonomy_name[$j]) {
      // add another if statement checking for the appropriate vocabulary id, (so I need to read this into an array as well,
      // it will be for the vocabulary associated with the "i" index)
      if($dbg == 1){
        echo("We are equal for ".$field_text_field_name_value_array[$i]." and ".$taxonomy_name[$j]);
        echo("\r\n");
      }
      array_push($match_taxonomy_name,$taxonomy_name[$j]);
      array_push($match_tid,$tid[$j]);
      array_push($nid_array_match,$nid_array[$i]);
      unset($field_text_field_name_value_array_map[$i]);
      unset($nid_array_map[$i]);

    } elseif ($field_text_field_name_value_array[$i] !== $taxonomy_name[$j]) {
      if($dbg == 1){
        echo("We are not equal for ".$field_text_field_name_value_array[$i]." and ".$taxonomy_name[$j]);
        echo("\r\n");
      }
    }
   } // end of vid_array if statement
  }
}

// Push the matching taxnomy names to the final taxonomy name arrays since no new taxonomy name needs to be created
foreach($match_taxonomy_name as $i => $value) {
  array_push($destmap_taxonomy_name,$match_taxonomy_name[$i]);
}
// Push the tids for the matching taxnomy names to the final tid arrays since no new taxonomy name with tid needs to be created
foreach($match_tid as $i => $value) {
  array_push($destmaptid,$match_tid[$i]);
}
// Push the node ids to tho the final node ids array since there is now a mapping between a node id and a taxonomy term id
foreach($nid_array_match as $i => $value) {
  array_push($destnidmap,$nid_array_match[$i]);
}

if($dbg == 1) {
  echo("The matches are");
  echo("\r\n");
  foreach($match_taxonomy_name as $i => $value) {
    echo($match_taxonomy_name[$i]." ".$match_tid[$i]." ".$nid_array_match[$i]);
    echo("\r\n");
  }

  echo("The non-matches are");
  echo("\r\n");
  foreach($field_text_field_name_value_array_map as $i => $value) {
    echo($field_text_field_name_value_array_map[$i]." ".$nid_array_map[$i]);
    echo("\r\n");
   }
}

// Find the unique names of the text field
$uniq_unmatch_taxonomy_name =array_unique($field_text_field_name_value_array_map);

if($dbg == 1){
  echo("The uniqe matches are:");
  echo("\r\n");
  foreach($uniq_unmatch_taxonomy_name as $i => $value) {
    echo($uniq_unmatch_taxonomy_name[$i]);
    echo("\r\n");
  }
}

// commenting this out for debugging ..

// Create taxonomy terms here:
// Create a mapping for the unique names from the text field
$uniq_unmatch_taxonomy_name_map = array();
// Create an array to hold term ids corresponding to taxonomy terms created for unique names
$uniq_unmatch_taxonomy_tid_map = array();

// commenting this out to replace with the functional equivalent?
/*
// Create taxonomy terms for each unique text field name, and output arrays showing the term id and taxonomy name
foreach($uniq_unmatch_taxonomy_name as $i => $value) {
      array_push($uniq_unmatch_taxonomy_tid_map,custom_create_taxonomy_term($uniq_unmatch_taxonomy_name[$i], $specified_vid));
      array_push($uniq_unmatch_taxonomy_name_map,$uniq_unmatch_taxonomy_name[$i]);
}
*/

// start of functional equivlanet?
// find the maximum tid for a taxonomy term..
try {
    $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
      foreach($dbh->query("SELECT MAX(`taxonomy_term_data`.`tid`) from `taxonomy_term_data`") as $row) {
        print_r($row);
        echo($row[0]);
        $new_tid = $row[0] + 1;
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$weight = 0;

foreach($uniq_unmatch_taxonomy_name as $row => $name) {
  echo $uniq_unmatch_taxonomy_name[$row];
  $name = $uniq_unmatch_taxonomy_name[$row];
  echo($new_tid);
   try {
      $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
        foreach($dbh->query("INSERT INTO `taxonomy_term_data` (tid, vid, name, description, format, weight) VALUES ($new_tid, $specified_vid , '$name' , NULL , NULL, $weight)") as $row) {
      }
      $dbh = null;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

    try {
      $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
        foreach($dbh->query("INSERT INTO `taxonomy_term_hierarchy` (tid, parent) VALUES ($new_tid, 0)") as $row) {
      }
      $dbh = null;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }

  array_push($uniq_unmatch_taxonomy_tid_map,$new_tid);
  array_push($uniq_unmatch_taxonomy_name_map,$name);
  $new_tid = $new_tid + 1;
}


// end of functional equivalent..
if($dbg == 1){
  echo("The term ids for the unique taxonomy names are");
  echo("\r\n");
  foreach($field_text_field_name_value_array_map as $i => $value) {
    echo($uniq_unmatch_taxonomy_tid_map[$i].' '.$uniq_unmatch_taxonomy_name_map[$i]);
    echo("\r\n");
  }
}

// A taxonomy name array to map unique taxonomy names with all names of the text field
$match_taxonomy_name_uniq = array();
// A term id array to map term ids from unique taxonomy names to all occurences of the names in the text field
$match_tid_uniq = array();
// A node id array to hold node ids for all occurences of the names in the text field, to keep a consistent index with tid and name
$nid_array_match_uniq = array();

// Map the unique term ids and taxonomy names to the non-match arrays
if($dbg == 1) {
echo("Map the unique term ids and taxonomy names to the non-match arrays");
echo("\r\n");
}
foreach($field_text_field_name_value_array_map as $i => $value_one) {
foreach($uniq_unmatch_taxonomy_name_map as $j => $value_two) {
  if($field_text_field_name_value_array_map[$i] == $uniq_unmatch_taxonomy_name_map[$j]) {
    if($dbg == 1){
      echo("We are equal for ".$field_text_field_name_value_array_map[$i]." and ".$uniq_unmatch_taxonomy_name_map[$j]);
      echo(" The tid is".$uniq_unmatch_taxonomy_tid_map[$j]);
      echo("\r\n");
    }
    // match the term ids, taxonomy names, and node ids here...
    array_push($match_taxonomy_name_uniq,$field_text_field_name_value_array_map[$i]);
    array_push($match_tid_uniq,$uniq_unmatch_taxonomy_tid_map[$j]);
    array_push($nid_array_match_uniq,$nid_array_map[$i]);

  } elseif ($field_text_field_name_value_array_map[$i] !== $uniq_unmatch_taxonomy_name_map[$j]) {
    if($dbg == 1){
    echo("We are not equal for ".$field_text_field_name_value_array_map[$i]." and ".$uniq_unmatch_taxonomy_name_map[$j]);
    echo("\r\n");
   }
  }
}
}

// end
if($dbg == 1) {
  echo("The result of Map the unique term ids and taxonomy names to the non-match arrays");
  echo("\r\n");
  foreach ($match_taxonomy_name_uniq as $i => $value) {
    echo($match_tid_uniq[$i].' '.$match_taxonomy_name_uniq[$i].' '.$nid_array_match_uniq[$i]);
    echo("\r\n");
  }

    echo("The final mapping before adding the non-matchess is");
    echo("\r\n");
    echo("taxonomy_term_id"." "."taxonomy_term_name"." "."referencing_node_id");
    echo("\r\n");
    foreach($destmap_taxonomy_name as $i => $value) {
      echo($destmaptid[$i]." ".$destmap_taxonomy_name[$i]." ".$destnidmap[$i]);
      echo("\r\n");
    }
}


// Combined the processed non matching text names with taxonomy names and corresponing term ids and node ids
foreach($match_taxonomy_name_uniq as $i => $value) {
  array_push($destmaptid,$match_tid_uniq[$i]);
  array_push($destnidmap,$nid_array_match_uniq[$i]);
  array_push($destmap_taxonomy_name,$match_taxonomy_name_uniq[$i]);
}

if($dbg == 1) {
  echo("The count of the original array is ".count($taxonomy_name));
  echo("\r\n");

  echo("The combined matches are");
  echo("\r\n");
  echo("taxonomy_term_id"." "."taxonomy_term_name"." "."referencing_node_id");
  echo("\r\n");
  foreach($destmap_taxonomy_name as $i => $value) {
    echo($destmaptid[$i]." ".$destmap_taxonomy_name[$i]." ".$destnidmap[$i]);
    echo("\r\n");
  }
}


/* feed the non-matches into the custom create taxonomy term function
 *  Use the function posted by mr.york to save a taxonomy term at :
 * https://api.drupal.org/api/drupal/modules!taxonomy!taxonomy.module/function/taxonomy_term_save/7 */
// This function was replaced with its functional equivalent
/*
function custom_create_taxonomy_term($name, $vid, $parent_id = 0) {
  $term = new stdClass();
  $term->name = $name;
  $term->vid = $vid;
  $term->parent = array($parent_id);
  taxonomy_term_save($term);
  return $term->tid;
}
*/
