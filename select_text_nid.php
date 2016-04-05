<?php
//Find the values of the entity_type field and the nodes that they correspond to
// This is like b and nbid..


/* Please Create a Content Type (bundle) containing a fields for the assigned names of
 * the text_field_name and term_reference_field_name variables.
 * If you create a new Content Type containing fields for the assigned names
 * and wish to migrate content to these fields from a content Type
 * containing only a text_field_name name please create a Node Convert Template.
 * This node convert template should map the field name assigned to text_field_name from
 * the source to target content type.
 * If you choose to work with a new content type, please go to Home>Administration>Content in your
 * Drupal 7 Installation after you have created  your Node Convert Template and select
 * your template name in the Update Options along with any checkboxes for the nodes
 * you wish to target for your new content type before running this script.
 */

// Access control to the database
$user = 'your_sql_username';
$pass = 'you_sql_password';

// The name or label of the text field. Please change this if it is different.
 $text_field_name = 'isp_column';
// The machine name of the text field. Please change this if it is different.
 $text_field_machine_name = 'field_'.$text_field_name;
 // The SQL table containing all data contained for all node instances of the text field
 $table_text_field_name = 'field_data_'.$text_field_machine_name;
 // The text contained in the text field for all node instances of the text field
 $table_text_field_name_value = $text_field_machine_name.'_value';
// Specifiy the node table
$pn = 'node';

// The content types of the nodes that you wish to convert. Please change to match your needs.
 $bundle_type = array('semantic_portal_page');
/* If you wish to convert all content types containing a particular text field and a term reference field
 * please use this assignment of the bundle_type variable instead of the one above. */
//$bundle_type = array('%');

// Specify a field name for the target Term Reference field
$term_reference_field_name = 'field_ispcolumn';
//$term_reference_field_name = '%';

// Define an array containing node ids for all nodes containing the text field
$nid_array_for_text_field = array();
// Define an array containing the content types for each node containing the text field
$content_type_array = array();
// Define an array containing the text for the text field amongst each node
$field_text_field_name_value_array_all = array();

// Set dbg to the integer 1 to allow for debugging
$dbg = 1;

// change dbname to your database name for all instances in the code 
foreach($bundle_type as $k => $type) {
  try {
      $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
        foreach($dbh->query("SELECT `$pn`.`nid`, `$pn`.`type`, `$table_text_field_name`.`$table_text_field_name_value` from `$pn`
  JOIN `$table_text_field_name` ON `$pn`.`nid` = `$table_text_field_name`.`entity_id`
  WHERE `$pn`.`type` LIKE '$bundle_type[$k]'") as $row) {
  //    foreach($dbh->query('SELECT * from `pan_node` LIMIT 10') as $row) {
//          print_r($row);
          if($row['nid'] !== NULL) {
          echo($row['nid']);
          array_push($nid_array_for_text_field, $row['nid']);
          }
          if($row['type'] !== NULL) {
          echo($row['type']);
          array_push($content_type_array, $row['type']);
          }
          if($row[$table_text_field_name_value] !== NULL) {
          echo($row[$table_text_field_name_value]);
          array_push($field_text_field_name_value_array_all, $row[$table_text_field_name_value]);
          }
      }
      $dbh = null;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
}
/*
// This Returns the Node ID, Content Type, and Text Field Value for each Node
foreach($bundle_type as $k => $type) {
  $result = db_query('SELECT {node}.nid, {node}.type, {'.$table_text_field_name.'}.'.$table_text_field_name_value.'
  FROM {node}
  JOIN {'.$table_text_field_name.'} ON
  {node}.nid = {'.$table_text_field_name.'}.entity_id
  WHERE {node}.type LIKE \''.$bundle_type[$k].'\'');

foreach($result as $record) {
   if($record->nid !== NULL) {
   array_push($nid_array_for_text_field, $record->nid);
   }
   if($record->$table_text_field_name_value !== NULL) {
   array_push($field_text_field_name_value_array_all, $record->$table_text_field_name_value);
   }
   if($record->type !== NULL) {
   array_push($content_type_array, $record->type);
   }
 }

}
*/

// This is debugging for the SQL query for the Node ID, Content Type, and Text Field Value for each Node
if($dbg == 1) {

   if($nid_array_for_text_field == NULL) {
      echo("Nothing to display");
    }


    print_r($nid_array_for_text_field);
    print_r($field_text_field_name_value_array_all);
    print_r($content_type_array);

    echo("The contents of the nid array before are");
    echo("\r\n");
    foreach($nid_array_for_text_field as $i => $value) {
      echo($nid_array_for_text_field[$i]);
      echo("\r\n");
    }

   foreach($nid_array_for_text_field as $i => $value) {
     echo($nid_array_for_text_field[$i]);
     echo("\r\n");
   }
}

 // Create temporary storage arrays for the nodes containing both the text field and Term Reference Fields
  $nid_array_for_text_and_term_ref_field = array();
  $bundle_name = array();
  $term_field_name = array();

foreach($nid_array_for_text_field as $i => $value) {
  try {
      $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
        foreach($dbh->query("SELECT `field_config_instance`.`field_name`, `field_config_instance`.`bundle`, `$pn`.`nid` from `$pn`
  JOIN `field_config_instance` ON `$pn`.`type` = `field_config_instance`.`bundle`
  WHERE `$pn`.`nid` LIKE '$nid_array_for_text_field[$i]' AND `field_config_instance`.`field_name`
  LIKE '$term_reference_field_name' AND `field_config_instance`.`bundle` LIKE '$content_type_array[$i]'") as $row) {
  //    foreach($dbh->query('SELECT * from `pan_node` LIMIT 10') as $row) {
          print_r($row);
          if($row['nid'] !== NULL) {
           echo($row['nid']);
           array_push($nid_array_for_text_and_term_ref_field, $row['nid']);
          }
          if($row['bundle'] !== NULL) {
           echo($row['bundle']);
           array_push($bundle_name, $row['bundle']);
          }
          if($row['field_name'] !== NULL) {
           echo($row['field_name']);
           array_push($term_field_name, $row['field_name']);
          }
      }
      $dbh = null;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
}

/*
// Select the Nodes containing both the targeted Text Fields and Term Reference Fields
foreach($nid_array_for_text_field as $i => $value) {
  $result_tr = db_query('SELECT {field_config_instance}.field_name, {field_config_instance}.bundle, {node}.nid
  FROM {node}
  JOIN {field_config_instance} ON
  {node}.type = {field_config_instance}.bundle
  WHERE {node}.nid LIKE '.$nid_array_for_text_field[$i].' AND {field_config_instance}.field_name LIKE \''.$term_reference_field_name.'\' AND {field_config_instance}.bundle LIKE \''.$content_type_array[$i].'\'');

foreach($result_tr as $record) {
   if($record->nid !== NULL) {
   array_push($nid_array_for_text_and_term_ref_field, $record->nid);
   }
   if($record->bundle !== NULL) {
   array_push($bundle_name, $record->bundle);
   }
   if($record->field_name !== NULL) {
   array_push($term_field_name, $record->field_name);
   }
}
}
*/

if($dbg == 1) {
   if($nid_array_for_text_and_term_ref_field == NULL) {
   echo("Nothing to display for ".$term_reference_field_name);
  }
  echo("The nid array, bundle (content type), and term reference field name for those with the term reference field and text field are");
  echo("\r\n");
  print_r($nid_array_for_text_and_term_ref_field);
  print_r($bundle_name);
  print_r($term_field_name);
}


/* Create Storage Arrays that contain the node and text fields that are fed to compare with existing taxonomy terms for a specified
vocabulary */
$nid_array = array();
$field_text_field_name_value_array = array();

// Reduce by removing elements that have a text field, but no term reference field
foreach( $nid_array_for_text_field as $i => $value_one) {
  foreach($nid_array_for_text_and_term_ref_field as $j => $value_two) {

    if( $nid_array_for_text_field[$i] == $nid_array_for_text_and_term_ref_field[$j]) {
      if($dbg == 1) {
        echo("We are equal for ".$nid_array_for_text_field[$i]." and ".$nid_array_for_text_and_term_ref_field[$j]);
        echo("\r\n");
        array_push($nid_array,$nid_array_for_text_field[$i]);
        array_push($field_text_field_name_value_array,$field_text_field_name_value_array_all[$i]);
      }
    } elseif ($nid_array_for_text_field[$i] !== $nid_array_for_text_and_term_ref_field[$j]) {
    //  unset($nid_array[$i]);
    //  unset($field_text_field_name_value_array[$i]);
      if($dbg == 1) {
        echo("We are not equal for ".$nid_array_for_text_field[$i]." and ".$nid_array_for_text_and_term_ref_field[$j]);
        echo("\r\n");
      }
    }
  }
}

if($dbg == 1){
    echo("The node id and text field values for those with the desired text and term reference field:");
    echo("\r\n");
    foreach($nid_array as $i => $value) {
      echo($nid_array[$i].' '.$field_text_field_name_value_array[$i]);
     echo("\r\n");
    }
}

echo('end of select_text_nid');
