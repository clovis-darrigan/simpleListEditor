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
<h1>Élément de liste</h1>
<form action="<?php print caNavUrl($this->request, "*", "*", "AddItem",array("list"=>$list_id)); ?>" method="post">
    <div class="bundleLabel">
        <span class="formLabelText" id="ca_intrinsic_ListItemEditorForm_P34">Intitulé</span>
        <div>
            <div class="bundleContainer " id="P34ListItemEditorForm" style="">
                <div class="caItemList">
                    <div class="labelInfo">
                        <div class="formLabel">
                            <input type="text" name="label" value="" size="30" maxlength="30">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bundleLabel">
        <span class="formLabelText" id="ca_intrinsic_ListItemEditorForm_P34">Description</span>
        <div>
            <div class="bundleContainer " id="P34ListItemEditorForm" style="">
                <div class="caItemList">
                    <div class="labelInfo">
                        <div class="formLabel">
                            <textarea name="description" cols="80" rows="10" style="width: 98%"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bundleLabel">
        <span class="formLabelText" id="ca_intrinsic_ListItemEditorForm_P34">Description</span>
        <div>
            <div class="bundleContainer " id="P34ListItemEditorForm" style="">
                <div class="caItemList">
                    <div class="labelInfo">
                        <div class="formLabel">
                            <input type="checkbox" name="default" value="1" /> Valeur par défaut
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit">Enregistrer</button>
</form>
