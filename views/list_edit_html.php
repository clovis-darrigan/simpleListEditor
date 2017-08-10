<?php
    /**
     *
     * providence
     *
     * 2017, idéesculture.
     *
     * This file : created on 05/08/2017 (08:55) by Gautier MICHELIN, gm@ideesculture.com
     * Contributions by : (add your name here, separated with commas)
     */
    require_once(__CA_MODELS_DIR__.'/ca_lists.php');
    require_once(__CA_MODELS_DIR__.'/ca_list_items.php');

    $list_id = $this->getVar("list_id");
    $list = new ca_lists($list_id);
?>
<!-- <link rel="stylesheet" href="//static.jstree.com/3.3.4/assets/bootstrap/css/bootstrap.min.css" />-->
<link rel="stylesheet" href="//static.jstree.com/3.3.4/assets/dist/themes/default/style.min.css" />
<script src="//static.jstree.com/3.3.4/assets/jquery-1.10.2.min.js"></script>
<script src="//static.jstree.com/3.3.4/assets/jquery.address-1.6.js"></script>
<script src="//static.jstree.com/3.3.4/assets/vakata.js"></script>
<script src="//static.jstree.com/3.3.4/assets/dist/jstree.min.js"></script>

<h1><small>Liste</small> <?php print $list->getLabelForDisplay(); ?></h1>
<script>
    var data = [
<?php
    foreach($list->getListItemsAsHierarchy() as $item) {
        $item = reset($item);
        $vt_item = new ca_list_items($item["item_id"]);
        print "\t\t{ 'id' : '".$item["item_id"]."', 'parent' : '".($item["parent_id"] > 0 ? $item["parent_id"] : "#")."', 'text' : '".$vt_item->getLabelForDisplay()."' },\n";
    }
    ?>
    ];
</script>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success btn-sm" onclick="concept_create();">Ajouter</button>
        <button type="button" class="btn btn-warning btn-sm" onclick="concept_rename();">Renommer</button>
        <button type="button" class="btn btn-danger btn-sm" onclick="concept_delete();">Supprimer</button>
        <input type="text" value="" style="box-shadow:inset 0 0 4px #eee; width:120px; margin:0; padding:6px 12px; border-radius:4px; border:1px solid silver;" id="concept_q" placeholder="Chercher" />
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="jstree_demo" class="demo" style="margin-top:1em; min-height:200px;"></div>
        <script>
            function concept_create() {
                var ref = $('#jstree_demo').jstree(true),
                    sel = ref.get_selected();
                if(!sel.length) { return false; }
                sel = sel[0];
                sel = ref.create_node(sel, {"type":"file"});
                if(sel) {
                    ref.edit(sel);
                }
            };
            function concept_rename() {
                var ref = $('#jstree_demo').jstree(true),
                    sel = ref.get_selected();
                if(!sel.length) { return false; }
                sel = sel[0];
                ref.edit(sel);
            };
            function concept_delete() {
                var ref = $('#jstree_demo').jstree(true),
                    sel = ref.get_selected();
                if(!sel.length) { return false; }
                ref.delete_node(sel);
            };
            function list_record() {
                $("#message").html("Saving ...");
                var ref = $('#jstree_demo').jstree(true),
                    sel = ref.get_selected(),
                    values = ref.get_json('#', {flat:true});
                if(!sel.length) { return false; }
                var new_values = values.map(function(d) {
                    return {"item_id":d.id, "parent_id":d.parent, "label": d.text}
                });
                console.log(new_values);
                $.ajax({
                    method: "POST",
                    url : "<?php print caNavUrl($this->request, "*", "*", "ListEditAjax",array("list"=>$list_id)); ?>",
                    data : {values:new_values}
                }).done(
                    function(data) {
                        console.log(data);
                    }
                );
                //console.log(new_values);
                $("#message").html("");
            };

            $(function () {
                var to = false;
                $('#concept_q').keyup(function () {
                    if(to) { clearTimeout(to); }
                    to = setTimeout(function () {
                        var v = $('#concept_q').val();
                        $('#jstree_demo').jstree(true).search(v);
                    }, 250);
                });

                $('#jstree_demo')
                    .jstree({
                        "core" : {
                            "animation" : 1,
                            "check_callback" : true,
                            'force_text' : true,
                            'data' : data
                        },
                        "types" : {
                            "#" : { "max_children" : 1, "max_depth" : 4, "valid_children" : ["root"] },
                            "root" : { "icon" : "https://www.jstree.com/static/3.3.4/assets/images/tree_icon.png", "valid_children" : ["default"] },
                            "default" : { "valid_children" : ["default","file"] },
                            "file" : { "icon" : "glyphicon glyphicon-file", "valid_children" : [] }
                        },
                        "plugins" : [ "contextmenu", "dnd", "search", "state", "types", "wholerow" ]
                    });
            });
        </script>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success btn-sm" onclick="list_record();">Enregistrer</button>
        <button class="btn btn-sm" onClick="window.history.go(-1);">Retour</button>
        <span id="message"></span>
    </div>
</div>
