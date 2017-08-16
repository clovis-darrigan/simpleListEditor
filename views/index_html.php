<?php
    /* ----------------------------------------------------------------------
     * simpleListEditor
     * ----------------------------------------------------------------------
     * List & list values editor plugin for Providence - CollectiveAccess
     * Open-source collections management software
     * ----------------------------------------------------------------------
     *
     * Plugin by idéesculture (www.ideesculture.com)
     * This plugin is published under GPL v.3. Please do not remove this header
     * and add your credits thereafter.
     *
     * File modified by :
     * ----------------------------------------------------------------------
     */

    $recordtype_lists = $this->getVar("recordtype_lists");
    $pa_recordtype_lists = $this->getVar("pa_recordtype_lists");
    $pa_recordtype_lists_info = $this->getVar("pa_recordtype_lists_info");
    $lists = $this->getVar("lists");
    $pa_lists = $this->getVar("pa_lists");
    $pa_lists_info = $this->getVar("pa_lists_info");

    require_once(__CA_MODELS_DIR__.'/ca_list_items.php');
?>
<h1>Simple List Editor</h1>
<h2>Record types</h2>
<?php
    foreach($pa_recordtype_lists as $ref=>$list_tree) {
        $list_id = $pa_recordtype_lists_info[$ref]["id"];
        print "<a href=".caNavUrl($this->request, "*", "*", "Edit", array("list"=>$list_id)).">".$ref."</a> ";
    }
?>

<h2>Lists</h2>
<?php
    foreach($pa_lists as $ref=>$list_tree) {
        $list_id = $pa_lists_info[$ref]["id"];
        print "<a href=".caNavUrl($this->request, "*", "*", "Edit", array("list"=>$list_id)).">".$ref."</a> ";
    }
?>

