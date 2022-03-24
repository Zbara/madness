<?php

$start = microtime(true);


include "header.php";

$params = array_params($registry->request->post['params']);
$registry->params = ['load_page' => $start, 'load_mem' => sys_getloadavg(), 'json' => $params];


require_once(include_dir . '/classes/api/class_session.php');
$session = new session($registry);
 

 
 
if($registry->config[production] == 'no' OR $session->info() == 1){
   $params[method] = false;
};

 

switch ($params[method]) {



   

    case "pvp":
		require_once(include_dir . '/classes/api/class_pvp.php');
        $pvp = new pvp($registry);

        switch ($params[action]) {
			
            case "start":
                $API_ = $pvp->start();
                break; 
				
            case "use_ability":
                $API_ = $pvp->use_ability();
                break;				

            default:
			 
                $API_ = $registry->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
        }
        break;	
		
		
    case "contacts":
		require_once(include_dir . '/classes/api/class_contacts.php');
        $contacts = new contacts($registry);

        switch ($params[action]) {
			
            case "get":
                $API_ = $contacts->get();
                break; 
    
            default:
			
                $API_ = $registry->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
        }
        break;	
		
		
		
    case "fortune":
		require_once(include_dir . '/classes/api/class_fortune.php');
        $fortune = new fortune($registry);
		
		

        switch ($params[action]) {
			
            case "play":
                $API_ = $fortune->play();
                break; 
				
            case "get_prize":
                $API_ = $fortune->get_prize();
                break; 
				
            case "turn":
                $API_ = $fortune->turn();
                break;
				
            default:
			
                $API_ = $registry->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
        }
        break;
		 
		
    case "stat":
		require_once(include_dir . '/classes/api/class_stat.php');
        $stats = new stats($registry);
		
		

        switch ($params[action]) {
			
            case "raise":
                $API_ = $stats->raise();
                break; 

            default:
			
                $API_ = $registry->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
        }
        break;			
		
		
    case "room":
		require_once(include_dir . '/classes/api/class_room.php');
        $room = new room($registry);
		
        switch ($params[action]) {
			
            case "set":
                $API_ = $room->set();
                break; 

            default:
			
                $API_ = $registry->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
        }
        break;			
		
    case "currency3":
		require_once(include_dir . '/classes/api/class_currency3.php');
        $currency = new currency($registry);

        switch ($params[action]) {
			
            case "send":
                $API_ = $currency->send();
                break; 

            default:
			
                $API_ = $registry->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
        }
        break;		

    case "authorize":
		  require_once(include_dir . '/classes/api/class_authorize.php');
           $authorize = new authPlatform($registry);	
          $API_ = $authorize->authorize();
        break;	

		
    case "init":
		require_once(include_dir . '/classes/api/class_init.php');
        $init = new initPlatform($registry);
        $API_ = $init->init();
		
        break;			
	
    default:
	
    $API_ = $registry->functions->echoJson(['error' => ['message' => 'Unknown method', 'code' => 0]]);
	
}
if ($params[format] == 'xml') {
    header('Content-Type: application/xml');
	require_once(include_dir . '/classes/class_xml.php');
    $converter = new Array2XML();
    $xml_respo = $converter->convert($API_);
    echo $xml_respo;
} else {
    header('Content-type: application/json');
    echo json_encode($API_);
}