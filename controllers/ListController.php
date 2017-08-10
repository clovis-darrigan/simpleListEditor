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

            $this->opa_listIdsFromIdno=array();

 			foreach($this->opa_list_of_lists as $ref=>$item) {
                $this->opa_listIdsFromIdno[reset($item)["list_code"]] = $ref;
            }
 		}

 		# -------------------------------------------------------
 		# Functions to render views
 		# -------------------------------------------------------
 		public function Index($type="") {
 			//$universe=$this->request->getParameter('universe', pString);
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
                //var_dump($vt_list->getListItemsAsHierarchy());die();
            }
            $this->view->setVar("pa_recordtype_lists", $va_lists);
            $this->view->setVar("pa_recordtype_lists_info", $va_lists_info);

            //var_dump($this->opo_config->get("recordtype_lists"));die();
            $this->render('index_html.php');
 		}

        public function Edit() {
            $list_idno=$this->request->getParameter('list', pString);

            $this->render('list_edit_html.php');
        }

        public function EditItem() {
            $list_id=$this->request->getParameter('list', pInteger);
            $item_id=$this->request->getParameter('item', pInteger);

            $t_locale = new ca_locales();
            $va_locales = Configuration::load()->getList('locale_defaults');
            $vn_locale_id = $t_locale->localeCodeToID($va_locales[0]);

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
            }

            $this->view->setVar("list_id", $list_id);
            $this->view->setVar("item_id", $item_id);

            $this->render('edit_item_html.php');
        }
 	}
 ?>