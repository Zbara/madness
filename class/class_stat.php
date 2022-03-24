<?php

class stats extends Controller
{
    private $registry;


    public function raise()
    {
		 $_Api = []; 
		 $name = $this->params['json']['name'];
		 
		 $user = $this->userDBcheck();
		 $skill = explode(',', $user['row']['skill']);

		 $price = (int) $user['row']['currency3'];
		 $level = (int) $user['row']['level']-1;
		 
		 $newSkill = $this->maxSkill($level, $skill, $name);
		 
		 
		 
		 
		 
		 if($price < 66){
		    $name = 'price';
		 } elseif($newSkill == 1){
			 $name = 'level';
		 };	 
		 
         switch ($name) { 
			
             case "skill1":
                 $skill[0] = (int) $skill[0]+1;
                 break; 
				 
             case "skill2":
                 $skill[1] = (int) $skill[1]+1;
                 break; 
				 
             default:	
			 
			return $this->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
         }
	     $base_skill = $this->newStat($skill);
		 $skill = implode(",", $skill);
		
		
		 $this->db->query("UPDATE `mad_users` SET `skill` = '{$skill}', `currency3` = currency3-66 WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `id` = '{$this->params['json']['uid']}'");
		 
		  
        return $this->functions->echoJson(['data' => $base_skill]);
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