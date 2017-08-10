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
        print "<div><span style=\"cursor:pointer;\" onclick=\"jQuery('#list_$ref').slideToggle();\">🔽 ".$ref."</span>";
        print "<ul id='list_$ref' style='display:none;'>"."\n";
        $list_id = $pa_recordtype_lists_info[$ref]["id"];
        $root_id = $pa_recordtype_lists_info[$ref]["root"];
        foreach ($list_tree as $item) {
            $item = reset($item);
            $vt_item = new ca_list_items($item["item_id"]);
            if($item["is_enabled"] == "1") {
                print "<li><a href=\"".caNavUrl($this->request, "*", "*", "EditItem", array("list"=>$list_id, "item"=>$item["item_id"]))."\">".$vt_item->getLabelForDisplay()." <small>[".$item["idno"]."]</small></a></li>\n";
            }
        }
        print "<p>".
            "<a class=\"button\" href=\"".caNavUrl($this->request, "*", "*", "AddItem", array("list"=>$list_id))."\">".
            "✚ Ajouter un concept</a></p></ul></div>\n";
    }
?>

<h2>Lists</h2>
<?php
    foreach($pa_lists as $ref=>$list_tree) {
        print "<div><span style=\"cursor:pointer;\" onclick=\"jQuery('#list_$ref').slideToggle();\">🔽 ".$ref."</span>";
        print "<ul id='list_$ref' style='display:none;'>"."\n";
        $list_id = $pa_lists_info[$ref]["id"];
        $root_id = $pa_lists_info[$ref]["root"];
        foreach ($list_tree as $item) {
            $item = reset($item);
            $vt_item = new ca_list_items($item["item_id"]);
            if($item["is_enabled"] == "1") {
                print "<li><a href=\"".caNavUrl($this->request, "*", "*", "EditItem", array("list"=>$list_id, "item"=>$item["item_id"]))."\">".$vt_item->getLabelForDisplay()." <small>[".$item["idno"]."]</small></a></li>\n";
            }
        }
        print "<p>".
            "<a class=\"button\" href=\"".caNavUrl($this->request, "*", "*", "AddItem", array("list"=>$list_id))."\">".
            "✚ Ajouter un concept</a></p></ul></div>\n";
    }
?>

