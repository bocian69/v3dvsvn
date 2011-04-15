<?php

Class View_MainView {

	public $args = null;
	private $moduleName = 'Content';

	function __construct(&$registry, $args) {
		$this->registry =& $registry;
		$this->args = $args;
		$this->attachments = main_path.'/attachments';
		if (empty($this->args) || !is_numeric($this->args[0])) return false;
		$this->content = new Content($this->registry);
		$content = $this->content->getContents($this->args[0]);
		$node = $this->content->getOneContent($this->args[0]);
		if (empty($content)) return false;
		$parents = $this->registry['menu']->getNodeParents($this->args[0]);
		if (!empty($parents)) {
			foreach ($parents as $v) {
				$this->registry->PathCreator->addStep($v['name'], $this->registry->PathCreator->prepareArgs(array($this->moduleName,$v['id_poz'])));
			}
		}
		$this->registry->PathCreator->addStep($node['name'], $this->registry->PathCreator->prepareArgs(array($this->moduleName,$this->args[0])));
		$this->registry['template']->assign('content', $content);
		$this->registry['template']->assign('attachments', $this->attachments);

		$this->registry['template']->assign('template', 'Content');
	}

}

?>