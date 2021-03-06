<?php

class Shineisp_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

	protected $_auth;
	protected $_acl;
	protected $_action;
	protected $_controller;
	protected $_module;
	protected $_currentRole;

	public function __construct(Zend_Acl $acl, array $options = array()) {
		$this->_auth = Zend_Auth::getInstance();
		$this->_acl = $acl;
	}

	public function preDispatch(Zend_Controller_Request_Abstract $request) {

		$this->_init($request);
		
// 		echo Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
// 		Zend_Debug::dump(Zend_Controller_Front::getInstance()->getRequest()->getParams());
		
		// if the current user role is not allowed to do something
		$resource = $this->_module . ":" . $this->_controller;

		$allowed_resources[] = "default:error";
		$allowed_resources[] = "admin:index";
		$allowed_resources[] = "admin:login";
		$allowed_resources[] = "system:index";
		$allowed_resources[] = "system:cron";
		$allowed_resources[] = "system:tasks";
		$allowed_resources[] = "setup:index";
		$allowed_resources[] = "setup:checker";
		$allowed_resources[] = "setup:database";
		$allowed_resources[] = "setup:localization";
		$allowed_resources[] = "setup:preferences";
		$allowed_resources[] = "setup:summary";
		
		// Exclude the system index controller and the default error controller for a formal ACL check
		if(in_array($resource, $allowed_resources)){
			return true;
		}
		
// 		Zend_Debug::dump($resource);
// 		Zend_Debug::dump($this->_currentRole);

		// Check if the user is an Administrator and let him free to access to all the resources
		if($this->_currentRole == "administrator"){
			return true;
		}
		
		if($this->_currentRole == "guest" && $this->_module == "default"){
			return true;
		}
		
		if (!$this->_acl->isAllowed($this->_currentRole, $resource, "allow")) {
			if ('guest' == $this->_currentRole) {
				Shineisp_Commons_Utilities::log("Login: The role '" . $this->_currentRole . "' has not sufficient permissions to access the resource '$resource'. The user has been redirected to the login page.");
				$request->setControllerName('login');
				$request->setActionName('index');
			} else {
				Shineisp_Commons_Utilities::log("Login: The role '" . $this->_currentRole . "' is not allowed to access to the $resource. It is redirected to the no authentication page.");
				$request->setControllerName('login');
				$request->setActionName('noauth');
			}
		}
	}

	protected function _init($request) {
		$this->_action = $request->getActionName();
		$this->_controller = $request->getControllerName();
		$this->_module = $request->getModuleName();
		$this->_currentRole = $this->_getCurrentUserRole();
	}

	protected function _getCurrentUserRole() {
		$this->_auth->setStorage(new Zend_Auth_Storage_Session($this->_module));
		
		if ($this->_auth->hasIdentity()) {
			$authData = $this->_auth->getIdentity();
			$role = !empty($authData['AdminRoles']['name']) ? strtolower($authData['AdminRoles']['name']) : 'guest';
		} else {
			$role = 'guest';
		}

		return $role;
	}
}