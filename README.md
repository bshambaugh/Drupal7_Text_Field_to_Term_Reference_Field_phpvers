Specific Instructions as used:

1. Clone the repository (be sure the fields isp_column [text], ispcolumn [term reference], entity_type [text], and field_vie_reference_tags [term_reference]    exist in your content type)
2. Change the username an password to thos appropriate to the database in each of the contained files.
3. Change the text field name and the term reference field name to the appropriate values in each of the contained files.
   (see the database with a tool like phpmyadmin to see that the tables and fields are the correct names) ..
    In select_text_nid.php:
    $text_field_name = 'isp_column';
    $term_reference_field_name = 'field_ispcolumn';

    or

    $text_field_name = 'entity_type';
    $term_reference_field_name = 'field_vie_reference_tags';
4. In each PDO MySQL query change the dbname to be dbname={your_data_base_name}
5. Run the script with php taxonomy-term-create.php
6. Clear the Drupal cache with drush cache-clear all

Discussions:

This is a script to convert a Text Field to a Term Reference Field for Nodes of Specified Content Types. It was built for Drupal 7.

Please Create a Content Type (bundle) containing a fields for the assigned names of the text_field_name and term_reference_field_name variables.

If you create a new Content Type containing fields for the assigned names and wish to migrate content to these fields from a content Type containing only a text_field_name name please create a Node Convert Template. This node convert template should map the field name assigned to text_field_name from the source to target content type.

If you choose to work with a new content type, please go to Home>Administration>Content in your Drupal 7 Installation after you have created your Node Convert Template and select your template name in the Update Options along with any checkboxes for the nodes you wish to target for your new content type before running this script.

Initiate this script by first setting the Text Field, Term Reference, and Bundle Type Variables in select_text_nid.php. Then type drush scr taxonomy-term-create.php to run the script.
You will also need to change to your database name, set your sql password and your sql
ername for all instances in the code.
The scripts contained include:

    taxonomy-term-create.php (the one you run)
   
    php files required by taxonomy-term-create:
  
    select_text_nid.php
    remove-duplicate-text-nid-pairs.php
    create_taxonomy_term_with_text.php
    remove-duplicate-nid-tid-pairs.php
    taxonomy-write-maps.php
    taxonomy-add-to-index.php
  

    Wrapper function that calls select_text_nid.php , create_taxonomy_term_with_text.php , taxonomy-write-maps.php , and taxonomy-add-to-index.php and
    select_text_nid.php

drush cache-clear all will need to be run after the script.

    Input: Text Field Name, Term Reference Field Name, Bundle Types (Content Types) Output: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types, Text field Contents
    remove-duplicate-text-nid-pairs.php

    Input: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types, Text field Contents Output: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types, Text field Contents (without duplicates)
    create_taxonomy_term_with_text.php

    Input: Node IDs with the Text Field and Term Reference Fields with the Specified Bundle Types, Text field Contents (without duplicates) Output: Taxonomy Term IDs, Taxonomy Term IDs, and Corresponding Node IDs (with duplicates), New Taxonomy Terms for each Text Field string created in the taxonomy_term_data SQL table if they do not already exist.
    remove-duplicate-nid-tid-pairs.php

    Input: Taxonomy Term IDs, Taxonomy Term IDs, and Corresponding Node IDs (with duplicates) Output: Taxonomy Term IDs, Taxonomy Term IDs, and Corresponding Node IDs (without duplicates)
    taxonomy-write-maps.php

    Input: Taxonomy Term IDs and Corresponding Node IDs Output: Taxonomy terms added to the Term Reference Field SQL Table
    taxonomy-add-to-index.php

    Input: Taxonomy Term IDs and Corresponding Node IDs Output: Taxonomy terms added to taxonomy_index SQL table


