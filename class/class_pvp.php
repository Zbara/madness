<?php

class pvp extends Controller
{
    private $registry;
    var $lib = null;

    public function start()
    {
		//Все данные 
        $time = (int)$this->datetime->time_server();
        $user = $this->userInfo();
		
        /* Ошибка */
        if(!$user['user']){
           return $this->functions->echoJson(['error' => ['message' => 'user_null', 'code' => 0]]); 
        };		
		
		
		$skill_my = explode(',', $user['my']['skill']);
		$skill_ow = explode(',', $user['ow']['skill']);
		
			
		for ($x = 0; $x < 16; $x++){
			 $skill_my[$x] = (int) $skill_my[$x];
			 $skill_ow[$x] = (int) $skill_ow[$x];
		}
		
		$sex_my_mad = ((int) $user['my']['sex'] == 2) ? 4 : 8;
		$sex_ow_mad = ((int) $user['ow']['sex'] == 2) ? 4 : 8;
		
		$user_health = (int) $skill_my[0]+$skill_my[$sex_my_mad]+$skill_my[12]*3;
		$enemy_health = (int) $skill_ow[0]+$skill_my[$sex_ow_mad]+$skill_my[12]*3;
		
		
		
        /* Пишем в БД */
        $this->db->query("INSERT INTO `mad_pvp` SET `soc_id` = '{$this->params['json']['user_id']}', `user_id` = '{$user['my']['id']}', `user_bid` = '{$user['ow']['id']}', `user_health` = '{$user_health}', `enemy_health` = '{$enemy_health}', `start` = '{$time}'");
		$pve_id = (int) $this->db->insert_id();
		
        $this->db->query("UPDATE `mad_users` SET `pvp_id` = '{$pve_id}', `pvp_user_id` = '{$user['ow']['id']}' WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `id` = '{$this->params['json']['uid']}'");		

		
        $_Api['user_ability'] = []; //[$skill_my, $skill_ow]; 
		 
		$_Api['pvp'] = ['id' => $pve_id, 'user_health' => $user_health, 'enemy_health' => $enemy_health];

 
        return $this->functions->echoJson(['data' => $_Api]);
    }
	
	
	public function use_ability(){
		//Все данные 
        $time = (int)$this->datetime->time_server();
        $user = $this->userInfo_pvp();
		
        /* Ошибка */
        if(!$user['user']){
           return $this->functions->echoJson(['error' => ['message' => 'no_pvp', 'code' => 0]]); 
        };	
		$pvp = $this->pvpInfo($user['my']['pvp_id']);
		
		
		
		$skill_my = explode(',', $user['my']['skill']);
		$skill_ow = explode(',', $user['ow']['skill']);
		
			
		for ($x = 0; $x < 16; $x++){
			 $skill_my[$x] = (int) $skill_my[$x];
			 $skill_ow[$x] = (int) $skill_ow[$x];
		}
		$sex_my_mad = ((int) $user['my']['sex'] == 2) ? 5 : 9;
		$sex_ow_mad = ((int) $user['ow']['sex'] == 2) ? 5 : 9;
		
		$user_damage = (int) floor($skill_my[1]+$skill_my[$sex_my_mad]+$skill_my[13]/2);
		$enemy_damage = (int) floor($skill_ow[1]+$skill_my[$sex_ow_mad]+$skill_my[13]/2);
		
		
		
		

		
		$_Api['effect_step'] = ['user_health' => (int) $pvp['user_health'], 'enemy_health' => (int) $pvp['enemy_health']];
	
		
		$_Api['user_step'] = ['effect' => $user_damage, 'user_health' => (int) $pvp['user_health']-$enemy_damage, 'enemy_health' => (int) $pvp['enemy_health']];
		
		
		$_Api['enemy_step'] = ['effect' => $enemy_damage, 'user_health' =>  (int) $pvp['user_health']-$enemy_damage, 'enemy_health' => (int) $pvp['enemy_health']-$user_damage];
		
		
		$pvp['user_health'] = (int) $pvp['user_health']-$enemy_damage;
		$pvp['enemy_health'] = (int) $pvp['enemy_health']-$user_damage; 
		
		
		if($pvp['user_health'] <= 0){
		   $_Api['rank'] = 500;
		   $_Api['win'] = 'no';
		   $_Api['drops_request'] = ['xp' => 10, 'currency1' => 20];
		} elseif ($pvp['enemy_health'] <= 0){
		   $_Api['rank'] = 1000;
		   $_Api['win'] = 'yes'; 
           $_Api['drops_request'] = ['xp' => 25, 'currency1' => 50];	   
		} else {
		   $_Api['win'] = 'batle';	
		} 
		
		
		
		
		
		 
        $this->db->query("UPDATE `mad_pvp` SET `user_health` = '{$pvp['user_health']}', `enemy_health` = '{$pvp['enemy_health']}' WHERE `id` = '{$pvp['id']}'");
		  
	
		$_Api['pvp'] = ['id' => (int) $pvp['id'], 'user_health' => (int) $pvp['user_health'], 'enemy_health' => (int) $pvp['enemy_health']];
		 
        return $this->functions->echoJson(['data' => $_Api]);		
	}
	
    private function pvpInfo($id)
    {
		$id = (int) $id;
		
        return $this->db->super_query("SELECT * FROM `mad_pvp` WHERE `id` = '{$id}'");
    }
	
    private function userInfo()
    {
		
		$this->params['json']['uid'] = (int) $this->params['json']['uid'];
		$this->params['json']['id'] = (int) $this->params['json']['id'];
		
		if(!$this->params['json']['uid'] OR !$this->params['json']['id']){
		   return ['user' => null];
		};
		
		$my = $this->db->super_query("SELECT id, skill, sex, pvp_id FROM `mad_users` WHERE `id` = '{$this->params['json']['uid']}'");
		
		$ow = $this->db->super_query("SELECT id, skill, sex FROM `mad_users` WHERE `id` = '{$this->params['json']['id']}'");
		
	  return ((int) $my['id'] AND (int) $ow['id']) ? ['user' => true, 'my' => $my, 'ow' => $ow] : ['user' => null];

    }	



    private function userInfo_pvp()
    {
		
		$this->params['json']['uid'] = (int) $this->params['json']['uid'];
		
		if(!$this->params['json']['uid']){
		   return ['user' => null];
		};
		
		$my = $this->db->super_query("SELECT id, skill, sex, pvp_id, pvp_user_id FROM `mad_users` WHERE `id` = '{$this->params['json']['uid']}'");
		$pvp_user_id = (int) $my['pvp_user_id'];
		$ow = $this->db->super_query("SELECT id, skill, sex FROM `mad_users` WHERE `id` = '{$pvp_user_id}'");
		
	  return ((int) $my['id'] AND (int) $ow['id']) ? ['user' => true, 'my' => $my, 'ow' => $ow] : ['user' => null];

    }
	
}