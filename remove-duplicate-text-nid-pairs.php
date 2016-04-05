<?php

// Dummy data. Uncomment the next two lines to run the script by itself.
// $nid_array = array('33','50','88','22','33');
// $field_text_field_name_value_array = array('Brave Saint Saturn','Cookies','Emmanuel','Ball Point Pen','Brave Saint Saturn');
$nid_w_names = array();
$uniq_nid_w_names = array();
$nid_clean_map = array();
$names_clean_map = array();

// Set to the integer 1 for debugging
$dbg = 1;

if($dbg == 1){
  echo("The dirty data is:");
  echo("\r\n");
  foreach($nid_array as $i => $value) {
    echo($field_text_field_name_value_array[$i].' '.$nid_array[$i]);
    echo("\r\n");
  }
}

// Concatenate the node ids with the text in the text field
foreach($nid_array as $i => $value) {
  array_push($nid_w_names,$nid_array[$i].$field_text_field_name_value_array[$i]);
}

if($dbg == 1){
  echo("The non-uniqe node id with name elements are");
  echo("\r\n");
  foreach($nid_w_names as $i => $value) {
    echo($nid_w_names[$i]);
    echo("\r\n");
  }
}

// Find unique concatenations of node ids with the text in the text field
$uniq_nid_w_names = array_unique($nid_w_names);

if($dbg == 1){
  echo("The unique node id array elements are");
  echo("\r\n");
  foreach($uniq_nid_w_names as $i => $value) {
    echo($uniq_nid_w_names[$i]);
    echo("\r\n");
  }
}

// Map the unique concatenations of the node ids and corresponding text fields with
// the node id values and the text field contents
foreach($uniq_nid_w_names as $i => $value_one) {
        $count = 0;
  foreach($nid_w_names as $j => $value_two) {
    if($uniq_nid_w_names[$i] == $nid_w_names[$j]) {
      if($count == 0) {
          array_push($names_clean_map,$field_text_field_name_value_array[$i]);
          array_push($nid_clean_map,$nid_array[$i]);
          if($dbg == 1) {
            echo("The count is".$count);
            echo("\r\n");
          }
    }
      $count = $count + 1;
      if($dbg == 1){
        echo("We are equal for ".$uniq_nid_w_names[$i]." and ".$nid_w_names[$j]);
        echo("\r\n");
      }
    } elseif ($uniq_nid_w_names[$i] !== $nid_w_names[$j]) {
       if($dbg == 1){
        echo("We are not equal for ".$uniq_nid_w_names[$i]." and ".$nid_w_names[$j]);
        echo("\r\n");
       }
    }
  }
}

if($dbg == 1) {
  echo("The names and nid maps are");
  echo("\r\n");
  foreach($names_clean_map as $i => $value){
    echo($names_clean_map[$i].' '.$nid_clean_map[$i]);
    echo("\r\n");
  }
}

// Clear the existing arrays for the existing node ids and text field values

 foreach($nid_array as $i => $value) {
   array_pop($nid_array);
 }

 foreach($field_text_field_name_value_array as $i => $value) {
   array_pop($field_text_field_name_value_array);
 }


// Map the arrays that remove duplicates for the node ids and text field values
foreach($nid_clean_map as $i => $value) {
  array_push($nid_array,$nid_clean_map[$i]);
}

foreach($names_clean_map as $i => $value) {
  array_push($field_text_field_name_value_array,$names_clean_map[$i]);
}

if($dbg == 1){
  echo("The cleaned data is:");
  echo("\r\n");
  foreach($nid_array as $i => $value) {
    echo($field_text_field_name_value_array[$i].' '.$nid_array[$i]);
    echo("\r\n");
  }
}
