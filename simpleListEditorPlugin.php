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
 
	class simpleListEditorPlugin extends BaseApplicationPlugin {
		# -------------------------------------------------------
		protected $description = 'SimpleListEditor for CollectiveAccess';
		# -------------------------------------------------------
		private $opo_config;
		private $ops_plugin_path;
		# -------------------------------------------------------
		public function __construct($ps_plugin_path) {
			$this->ops_plugin_path = $ps_plugin_path;
			$this->description = _t('Simple list and list item editor');
			parent::__construct();
			$this->opo_config = Configuration::load($ps_plugin_path.'/conf/simpleListEditor.conf');
		}
		# -------------------------------------------------------
		/**
		 * Override checkStatus() to return true - the statisticsViewerPlugin always initializes ok... (part to complete)
		 */
		public function checkStatus() {
			return array(
				'description' => $this->getDescription(),
				'errors' => array(),
				'warnings' => array(),
				'available' => ((bool)$this->opo_config->get('enabled'))
			);
		}
		# -------------------------------------------------------
		/**
		 * Insert activity menu
		 */
		public function hookRenderMenuBar($pa_menu_bar) {
			if ($o_req = $this->getRequest()) {
				//if (!$o_req->user->canDoAction('can_use_simplelisteditor_plugin')) { return true; }

				$pa_menu_bar['simpleListEditorMenu'] = array(
					'displayName' => 'Lists',
                    'navigation'=> array(
                        'Lists' => array(
                            'displayName' => 'Lists',
                            "default" => array(
                                'module' => 'simpleListEditor',
                                'controller' => 'List',
                                'action' => 'Index'
                            )
                        )
                    )
                );
			} 
			
			return $pa_menu_bar;
		}
		# -------------------------------------------------------
		/**
		 * Add plugin user actions
		 */
		static function getRoleActionList() {
			return array(
				'can_use_simplelisteditor_plugin' => array(
						'label' => _t('Can use simpleListEditor functions'),
						'description' => _t('User can use all simpleListEditor plugin functionality.')
					)
			);
		}
		
	}
?>