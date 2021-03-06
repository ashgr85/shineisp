<?php

/**
 * Servers
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Servers extends BaseServers {
    
	/**
	 * Override of the relations between the 
	 * Servers and Custom_Attributes_Values table
	 * 
	 * Setup a new external relationship 
	 * between the Servers table class 
	 * and the CustomAttributesValues class
	 * 
	 * (non-PHPdoc)
	 * @see BaseServers::setUp()
	 */
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('CustomAttributesValues', array(
             'local' => 'server_id',
             'foreign' => 'external_id'));
    }
	
	/**
	 * grid
	 * create the configuration of the grid
	 */	
	public static function grid($rowNum = 10) {
		
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;

		// Return usage/max_services or usage/infinity if max_services is 0 or null 
		$sqlIF = "
			(IF ( s.max_services 
     			 ,CONCAT(s.services,'/',s.max_services,' (',ROUND(s.services*100/s.max_services),'%)')
     			 ,CONCAT(s.services,'/&infin;') 
			))
		";
		
		$config ['datagrid'] ['columns'] [] = array ('label' => null, 'field' => 's.server_id', 'alias' => 'server_id', 'type' => 'selectall' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'ID' ), 'field' => 's.server_id', 'alias' => 'server_id', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Name' ), 'field' => 'r.subject', 'alias' => 'servername', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'IP' ), 'field' => 's.ip', 'alias' => 'ip', 'sortable' => true, 'searchable' => true);
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Status' ), 'field' => 'stat.status', 'alias' => 'status', 'sortable' => true, 'searchable' => true );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Usage' ), 'field' => 's.usage', 'alias' => 'usage', 'sortable' => false, 'searchable' => false );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Panel' ), 'field' => 'panel.name', 'alias' => 'panel_name', 'sortable' => true, 'searchable' => true );

		$config ['datagrid'] ['fields'] = "s.server_id, s.name as servername, ".$sqlIF." AS usage, s.max_services AS max_services, s.ip as ip, stat.status as status, panel.name as panel_name";
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()->select ( $config ['datagrid'] ['fields'] )
																		->from ( 'Servers s' )
																        ->leftJoin ( 's.Isp i' )
																        ->leftJoin ( 's.Servers_Types st' )
																        ->leftJoin ( 's.Statuses stat' )
																		->leftJoin ( 's.Panels panel' );;
		
		$config ['datagrid'] ['rownum'] = $rowNum;
		
		$config ['datagrid'] ['basepath'] = "/admin/servers/";
		$config ['datagrid'] ['index'] = "server_id";
		$config ['datagrid'] ['rowlist'] = array ('10', '50', '100', '1000' );
		
		$config ['datagrid'] ['buttons'] ['edit'] ['label'] = $translator->translate ( 'Edit' );
		$config ['datagrid'] ['buttons'] ['edit'] ['cssicon'] = "edit";
		$config ['datagrid'] ['buttons'] ['edit'] ['action'] = "/admin/servers/edit/id/%d";
		
		$config ['datagrid'] ['buttons'] ['delete'] ['label'] = $translator->translate ( 'Delete' );
		$config ['datagrid'] ['buttons'] ['delete'] ['cssicon'] = "delete";
		$config ['datagrid'] ['buttons'] ['delete'] ['action'] = "/admin/servers/delete/id/%d";
		
		$config ['datagrid'] ['massactions'] = array ('massdelete'=>'Mass Delete');
		
		return $config;
	}	
	
	
	/**
	 * Save all the data
	 * @param array $params
	 */
	public static function saveAll(array $params) {
		
		if(!empty($params['server_id']) && is_numeric($params['server_id'])){
			$server = Doctrine::getTable ( 'Servers' )->find ( $params['server_id'] );
		}else{
			$server = new Servers();
		}
		
		$server->name         = $params ['name'];
		$server->ip           = $params ['ip'];
		$server->netmask      = $params ['netmask'];
		$server->host         = $params ['host'];
		$server->domain       = $params ['domain'];
		$server->description  = $params ['description'];
		$server->status_id    = intval($params ['status_id']);
		$server->isp_id       = intval($params ['isp_id']);
		$server->type_id      = intval($params ['type_id']);
		$server->panel_id     = (intval($params ['panel_id'])) ? intval($params ['panel_id']) : null;
		$server->cost         = (isset($params['cost']) && is_numeric($params['cost'])) ? $params['cost'] : 0;
		$server->max_services = (isset($params['max_services'])) ? intval($params['max_services']) : 0;
		$server->datacenter   = (isset($params['datacenter'])) ? $params['datacenter'] : '';
		$server->is_default   = (isset($params['is_default'])) ? intval($params['is_default']) : 0;
		$server->buy_date     = Shineisp_Commons_Utilities::formatDateIn ($params ['buy_date']);

		$server->save ();
		
		// TODO: clear other default servers for the same group. Only one server can be default in the group
		
		return $server['server_id'];
	}
	
	/**
     * Get a record 
     * @param $id
     * @return Doctrine_Record
     */
    public static function find($id) {
        return Doctrine::getTable ( 'Servers' )->find ( $id );
    }

	/**
     * Get all the information about the server 
     * @param $id
     * @return ArrayObject
     */
    public static function getAll($id) {
    	
        $record = Doctrine_Query::create ()
        			->from ( 'Servers s' )
        			->leftJoin ( 's.Servers_Types' )
        			->leftJoin ( 's.CustomAttributesValues' )
        			->where ( "s.server_id = ? ", $id)
        			->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        		
    	return !empty($record[0]) ? $record[0] : array();
    }
	
	/**
	 * getServers
	 * Get all servers
	 * @param $id
	 * @return ARRAY Record
	 */
	public static function getServers($locale = null) {
		if ( $locale === null ) {
			$Session = new Zend_Session_Namespace ( 'Admin' );
			$locale = $Session->langid;
		}
		
		try {
			$records = array();
			$items = Doctrine_Query::create ()->select ( '*' )
			->from ( 'Servers s' )
			->orderBy('s.name')
			->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );

			foreach ( $items as $c ) {
				$records [$c ['server_id']] = $c['name'] . " - (".$c ['host'].".".$c['domain'].")";
			}
			
			return $records;
			
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
	
	}
	
	/**
	 * extractServersFromGroup:
	 * extract servers from group accordingly to server group fill_type
	 * @param INT  $group_id:    id of the group to extract from
	 * @param INT  $server_type: optional server type id to extract. If empty, one server for each type is extracted
	 * @return ARRAY
	 * 
	 */
	public static function extractServersFromGroup($group_id, $server_type = null) {
		$arrOutput   = array();
		$group_id    = isset($group_id)    ? intval($group_id)    : null;
		$server_type = isset($server_type) ? intval($server_type) : null;
		
		if ( !$group_id ) {
			return $arrOutput;
		}

		// Get the fill_type for requested group		
		$ServersGroups = ServersGroups::find($group_id);
		if ( !$ServersGroups || !isset($ServersGroups->fill_type) ) {
			return $arrOutput;
		}
		$fill_type = (int)$ServersGroups->fill_type;
	
		/*
        	'1' => 'Create services on the least full server',
        	'2' => 'Fill default server until full then switch to next least used', 
        	'3' => 'Fill servers starting from the newest to the older',
        	'4' => 'Fill servers starting from the older to the newest',
        	'5' => 'Fill servers randomly',
        	'6' => 'Fill manually. Only default server will be used.',
        	'7' => 'Fill servers starting from the cheaper to the expensive.',
        	'8' => 'Fill servers starting from the expensive to the cheaper.',
		*/
		
		// Get all servers inside this group
		$serverIds = ServersGroups::getServers($group_id, 's.*');
		
		// Get servers details
		$ORM = Doctrine_Query::create ()
							 ->from ( 'Servers s' )
					         ->leftJoin ( 's.Servers_Types st' )
					         ->leftJoin ( 's.Statuses stat' )
							 ->leftJoin ( 's.Panels panel' )
							 ->leftJoin ( 's.Servers_Types' )
    			             ->leftJoin ( 's.CustomAttributesValues' )
							 ->whereIn ('s.server_id', $serverIds);

		if ( $server_type ) {
			$ORM = $ORM->andWhere('s.type_id = ?', $server_type);		
		}

		$ORM = $ORM->execute();
							 
		// Create an array with type_id as index
		$arrServers = array();
		foreach ( $ORM as $y ) {
			$type_id       = $y->type_id;
			$server_id     = $y->server_id;
			$services      = isset($y->services) ? intval($y->services) : 0;
			$max_services  = (isset($y->max_services) && $y->max_services > 0) ? intval($y->max_services) : 10000;
			$buy_timestamp = isset($y->buy_date) ? strtotime($y->buy_date) : 0;
			$is_default    = isset($y->is_default) ? intval($y->is_default) : 0;

			// Skip full servers
			if ( $services >= $max_services ) {
				Shineisp_Commons_Utilities::logs (__METHOD__ . " " . $server_type." is full!", 'servers.log');
				continue;
			}

			$usagePercent = round(($services*100)/$max_services);
			
			$arrServers[$type_id][$server_id] = $y->toArray();
			$arrServers[$type_id][$server_id]['is_default']    = $is_default;
			$arrServers[$type_id][$server_id]['_usagePercent'] = $usagePercent;
			$arrServers[$type_id][$server_id]['_buyTimestamp'] = $buy_timestamp;
		}
		
		// Cycle servers by type
		foreach ( $arrServers as $type_id => $serverType ) {
			$tmp = array(); // temporary array
			
			// Extract server
			switch ( $fill_type ) {
				case 1: // 'Create services on the least full server'
				case 2: // 'Fill default server until full then switch to next least used'
				 	$method = ($fill_type === 1) ? SORT_ASC : SORT_DESC;
				 	$tmp    = Shineisp_Commons_ArraySorter::multisort($serverType, '_usagePercent', $method);
					break;

				case 3: // 'Fill servers starting from the newest to the older'
				case 4: // 'Fill servers starting from the older to the newest'
				 	$method = ($fill_type === 3) ? SORT_DESC : SORT_ASC;
				 	$tmp    = Shineisp_Commons_ArraySorter::multisort($serverType, '_buyTimestamp', $method);
					break;
					
				case 5: // 'Fill servers randomly'
					$randKey = array_rand($serverType);
					$tmp     = array($randKey => $randKey);
					break;

				case 6: // 'Fill manually. Only default server will be used.'
					$tmp    = Shineisp_Commons_ArraySorter::multisort($serverType, 'is_default', SORT_DESC);
					break;
					
				case 7: // 'Fill servers starting from the cheaper to the expensive.'
				case 8: // 'Fill servers starting from the expensive to the cheaper.'
					$method = ($fill_type === 7) ? SORT_ASC : SORT_DESC;
					$tmp    = Shineisp_Commons_ArraySorter::multisort($serverType, 'cost', $method);
			}

			reset($tmp);
			$selectedServer = key($tmp);
		
			$arrOutput[$type_id][$selectedServer] = $arrServers[$type_id][$selectedServer];					
		}
		
		if (isset($server_type) && isset($arrOutput[$server_type])) {
			// return just the server array
			$key = key($arrOutput[$server_type]);
			$arrOutput = $arrOutput[$server_type][$key];
		}
		
		Shineisp_Commons_Utilities::logs (__METHOD__ . '('.$group_id.', '.$server_type.'): '.serialize($arrOutput), 'servers.log');
		
		// Return
		return $arrOutput;
		
	}
	
	
	
	/**
	 * getServerFromGroup:
	 * extract a webserver from group accordingly to server group fill_type
	 * @param INT     $group_id:    id of the group to extract from
	 * @param STRING  $server_type: server type to extract.
	 * @return ARRAY
	 * 
	 */
	public static function getServerFromGroup($group_id = null, $server_type = null) {
		$group_id    = isset($group_id)    ? intval($group_id)    : null;
		
		if ( !$group_id || !$server_type) {
			return array();
		}
		
		if ( $server_type == 'web' )      { $server_type_id = 1; }
		if ( $server_type == 'mail' )     { $server_type_id = 2; }
		if ( $server_type == 'database' ) { $server_type_id = 3; }
		if ( $server_type == 'dns' )      { $server_type_id = 4; }
		
		return self::extractServersFromGroup($group_id, $server_type_id);
	}
	

	
	
	/**
     * setStatus
     * Set a record with a status
     * @param $id, $status
     * @return Void
     */
    public static function setStatus($id, $status) {
        $dq = Doctrine::getTable ( 'Servers' )->find ( $id );
        $dq->status_id = $status;
        return $dq->save ();
    }
    
	/**
	 * findAllbyIsp
	 * Get a record by the ISP ID
	 * @param $isp_id
	 * @return Doctrine Record
	 */
	public static function findAllbyIsp($isp_id = "", $fields = "*", $retarray = false) {
		$isp_id = is_numeric($isp_id) ? $isp_id : Isp::getActiveISPID();
		$dq = Doctrine_Query::create ()->select ( $fields )->from ( 'Servers i' )->leftJoin ( 'i.Servers_Types st ON st.type_id = i.type_id' )->andWhere ( "isp_id = $isp_id" );
		return $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
	}

	/*
	 * Get Webserver information 
	 */
	public static function getWebserver(){
		$dq = Doctrine_Query::create ()->from ( 'Servers i' )
										->leftJoin ( 'i.Servers_Types st ON st.type_id = i.type_id' )
										->where ( "type_id = ?", 1 )
										->andWhere ( "isp_id = ?", Isp::getActiveISPID());
         $record = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
         return !empty($record[0]) ? $record[0] : false; 
	}

	/*
	 * Get Active Webserver information 
	 */
	public static function getActiveWebserver(){
		$dq = Doctrine_Query::create ()->from ( 'Servers i' )
										->leftJoin ( 'i.Servers_Types st ON st.type_id = i.type_id' )
										->where ( "type_id = ?", 1 )
										->where ( "status_id = ?", Statuses::id('active','servers') )
										->andWhere ( "isp_id = ?", Isp::getActiveISPID());
         $record = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
         return !empty($record[0]) ? $record[0] : false; 
	}

	
	/*
	 * Get DB Server information 
	 */
	public static function getDbserver(){
		$dq = Doctrine_Query::create ()->from ( 'Servers i' )
										->leftJoin ( 'i.Servers_Types st ON st.type_id = i.type_id' )
										->where ( "type_id = ?", 3 )
										->andWhere ( "isp_id = ?", Isp::getActiveISPID());
         $record = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
         return !empty($record[0]) ? $record[0] : false; 
	}
	
	/*
	 * Get Mail Server information 
	 */
	public static function getMailserver(){
		$dq = Doctrine_Query::create ()->from ( 'Servers i' )
										->leftJoin ( 'i.Servers_Types st ON st.type_id = i.type_id' )
										->where ( "type_id = ?", 2 )
										->andWhere ( "isp_id = ?", Isp::getActiveISPID());
        $record = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        return !empty($record[0]) ? $record[0] : false; 
	}
	
	/*
	 * Get DNS Server information 
	 */
	public static function getDnsserver(){
		$dq = Doctrine_Query::create ()->from ( 'Servers i' )
										->leftJoin ( 'i.Servers_Types st ON st.type_id = i.type_id' )
										->where ( "type_id = ?", 4 )
										->andWhere ( "isp_id = ?", Isp::getActiveISPID());
         return $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
	}
	

	/**
	 * massdelete
	 * delete the attributes selected 
	 * @param array
	 * @return Boolean
	 */
	public static function massdelete($items) {
		$retval = Doctrine_Query::create ()->delete ()->from ( 'Servers' )->whereIn ( 'server_id', $items )->execute ();
		return $retval;
	}	
	
	######################################### BULK ACTIONS ############################################
	
	
	/**
	 * massdelete
	 * delete the tickets selected 
	 * @param array
	 * @return Boolean
	 */
	public static function bulk_delete($items) {
		if(!empty($items)){
			return self::massdelete($items);
		}
		return false;
	}		
}