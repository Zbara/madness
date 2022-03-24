<?php

class room extends Controller
{
    private $registry;


    public function set()
    {
		 $_Api = []; 
		 $room_id = (int) $this->params['json']['id'];
		 
         $room = $this->db->super_query( "SELECT room_id, stamp FROM `mad_room` WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `room_id` = '{$room_id}'"); 
		 
	     $name = ($room['room_id']) ? 'yes' : 'no';	
		 
         switch ($name) { 

             case "yes":
                 $_Api = [];
                 break; 
				 
             default:	
			 
			return $this->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
         }
		 
		 $this->db->query("UPDATE `mad_users` SET `room_id` = '{$room_id}' WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `id` = '{$this->params['json']['uid']}'");
		 
		 
        return $this->functions->echoJson(['data' => $_Api]);
    }	
	
    private function newStat($base_skill)
    {
           return ['stat' => [['name' => 'skill1', 'base' => (int) $base_skill[0], 'store_male' => (int) $base_skill[4], 'store_female' => (int) $base_skill[8], 'temp' => (int) $base_skill[12]], ['name' => 'skill2', 'base' => (int) $base_skill[1], 'store_male' => (int) $base_skill[5], 'store_female' => (int) $base_skill[9], 'stemp' => (int) $base_skill[13]]]];
    }	
	
    private function userDBcheck()
    {
	     $user = $this->db->super_query( "SELECT skill, id, currency3, level FROM `mad_users` WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `id` = '{$this->params['json']['uid']}'");
		 $reg = ($user['id']) ? 1 : 0;
		 
		return ['row' => $user, 'reg' => $reg];
    }
	
    private function maxSkill($level, $old_skill, $name)
    {
		 $code = 1;
		 
		 $library = simplexml_load_string($this->load->library('1469566932_lib.xml'));
		 
		 
		 $max_skill = explode(',', $library->level->item[$level]->max_skill);

		 if($name == 'skill1'){
			$code = ($old_skill[0] < $max_skill[0]) ? 0 : 1;
		 } elseif($name == 'skill2'){
			$code = ($old_skill[1] < $max_skill[1]) ? 0 : 1;
		 };

		return (int) $code; //$skill;
    }		
}