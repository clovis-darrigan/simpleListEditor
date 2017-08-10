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

    require_once(__CA_MODELS_DIR__.'/ca_lists.php');
    require_once(__CA_MODELS_DIR__.'/ca_list_items.php');

 	class ListController extends ActionController {
 		# -------------------------------------------------------
  		protected $opo_config;		// plugin configuration file
        protected $opa_list_of_lists; // list of lists
        protected $opa_listIdsFromIdno; // list of lists
        protected $opa_locale; // locale id

 		# -------------------------------------------------------
 		# Constructor
 		# -------------------------------------------------------

 		public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
            parent::__construct($po_request, $po_response, $pa_view_paths);
 			
 			if (!$this->request->user->canDoAction('can_use_simplelisteditor_plugin')) {
 				$this->response->setRedirect($this->request->config->get('error_display_url').'/n/3000?r='.urlencode($this->request->getFullUrlPath()));
 				return;
 			}
 			
 			$this->opo_config = Configuration::load(__CA_APP_DIR__.'/plugins/simpleListEditor/conf/simpleListEditor.conf');

            $vt_list = new ca_lists();
 			$this->opa_list_of_lists = $vt_list->getListOfLists();

            $t_locale = new ca_locales();
            $va_locales = Configuration::load()->getList('locale_defaults');
            $this->opa_locale = $t_locale->localeCodeToID($va_locales[0]);

            $this->opa_listIdsFromIdno=array();
 			foreach($this->opa_list_of_lists as $ref=>$item) {
                $this->opa_listIdsFromIdno[reset($item)["list_code"]] = $ref;
            }
 		}

 		# -------------------------------------------------------
 		# Functions to render views
 		# -------------------------------------------------------
 		public function Index($type="") {
            // Record types lists
            $recordtype_lists = $this->opo_config->get("recordtype_lists");
            $this->view->setVar("recordtype_lists", $recordtype_lists);

            $va_lists = array();
            foreach($recordtype_lists as $list) {
                $vt_list = new ca_lists($this->opa_listIdsFromIdno["$list"]);
                $va_lists[$list] = $vt_list->getListItemsAsHierarchy();
                $va_lists_info[$list] = array(
                    "id" => $this->opa_listIdsFromIdno["$list"],
                    "root" =>$vt_list->getRootItemIDForList()
                );
            }
            $this->view->setVar("pa_recordtype_lists", $va_lists);
            $this->view->setVar("pa_recordtype_lists_info", $va_lists_info);

            // Classic lists
            $lists = $this->opo_config->get("lists");
            $this->view->setVar("lists", $lists);

            $va_lists = array();
            foreach($lists as $list) {
                $vt_list = new ca_lists($this->opa_listIdsFromIdno["$list"]);
                $va_lists[$list] = $vt_list->getListItemsAsHierarchy();
                $va_lists_info[$list] = array(
                    "id" => $this->opa_listIdsFromIdno["$list"],
                    "root" =>$vt_list->getRootItemIDForList()
                );
            }
            $this->view->setVar("pa_lists", $va_lists);
            $this->view->setVar("pa_lists_info", $va_lists_info);

            // Thesaurus
            // to be implemented

            $this->render('index_html.php');
 		}

        public function Edit() {
            $list_idno=$this->request->getParameter('list', pString);

            $this->render('list_edit_html.php');
        }

        public function EditItem() {
            $list_id=$this->request->getParameter('list', pInteger);
            $item_id=$this->request->getParameter('item', pInteger);

            $vn_locale_id = $this->opa_locale;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if($_POST["id"] != $item_id) {die("Posted data is corrupt.");}
                // Posted data
                $vt_item = new ca_list_items($item_id);
                if($vt_item->getLabelForDisplay() != $_POST["label"]) {
                    // Updating label
                    $vt_item->setMode(ACCESS_WRITE);
                    $vt_item->removeAllLabels();
                    $vt_item->update();
                    $vt_item->addLabel(
                        array(
                            'name_singular' => $_POST["label"],
                            'name_plural' => $_POST["label"]
                        ), $vn_locale_id, null, true);
                    $vt_item->update();
                }
                //var_dump($_POST);
                //die();
                $this->response->setRedirect(caNavUrl($this->getRequest(), "*","*","Index"));
            } else {
                $this->view->setVar("list_id", $list_id);
                $this->view->setVar("item_id", $item_id);

                $this->render('edit_item_html.php');

            }
        }

        public function AddItem() {
            $list_id=$this->request->getParameter('list', pInteger);

            $vn_locale_id = $this->opa_locale;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $list = new ca_lists($list_id);
                $item = new ca_list_items();
                //$list->addItem()

                $vs_item_value = $_POST["label"];
                $vs_item_idno = $vs_item_value;
                $vs_type = null;
                $vs_status = 0;
                $vs_access = 0;
                $vs_rank = 0;
                $vn_enabled = 1;
                $vn_default = 0;
                $vn_type_id = $list->getItemIDFromList('list_item_types', 'concept');

                $t_item = $list->addItem($vs_item_value, $vn_enabled, $vn_default, 0, $vn_type_id, $vs_item_idno, '', (int)$vs_status, (int)$vs_access, (int)$vs_rank);

                if (($list->numErrors() > 0) || !is_object($t_item)) {
                    $this->addError("There was an error while inserting list item {$vs_item_idno}: ".join(" ",$list->getErrors()));
                    //return false;
                    $this->render('index_html.php');
                } else {
                    $this->logStatus(_t('Successfully updated/inserted list item with idno %1', $vs_item_idno));
                    $t_item->setMode(ACCESS_WRITE);
                    @$t_item->update();
                    @$t_item->addLabel(
                        array(
                            'name_singular' => $_POST["label"],
                            'name_plural' => $_POST["label"]
                        ), $vn_locale_id, null, true);
                    @$t_item->update();
                    //var_dump($t_item->getErrors());

                    //$this->response->setRedirect(caNavUrl($this->getRequest(), "*","*","Index"));
                    die("here");
                    //var_dump();
                    //die();


                    $this->view->setVar("list_id", $list_id);
                    $this->view->setVar("item_id", $t_item->get("item_id"));
                    $this->render("add_item_html.php");
                }
            } else {
                $this->view->setVar("list_id", $list_id);
                $this->render('add_item_html.php');
            }
        }
 	}
 ?>