<?php

class authPlatform extends Controller
{
    private $registry;
    var $auth_info = null;


    public function authorize()
    {
         $_Api = [];
		 $user = $this->userDBcheck();
		 
	     if($user['reg']){
			$_Api['user'] = $this->userDByes($user['row']);
	     } else {
			$_Api['user'] = $this->userDBno();
		 }	 
		 $_Api['news'] = [];
		
        return $this->functions->echoJson(['data' => $_Api]);
    }
	
    private function userDBno()
    {	
	
	        $reg_new = $this->config[reg_new];
			$time = $this->datetime->time_server();
			
		    //$this->db->query("INSERT INTO `mad_users` SET `soc_id` = '{$this->params['json']['user_id']}', `last_name` = '{$this->params['json']['last_name']}', `first_name` = '{$this->params['json']['first_name']}', `sex` = '{$this->params['json']['sex']}', `country` = '{$this->params['json']['country']}', `city` = '{$this->params['json']['city']}', `avatar` = '{$this->params['json']['avatar']}', `birthdate` = '{$this->params['json']['birthdate']}', `currency1` = '{$reg_new['currency1']}', `currency2` = '{$reg_new['currency2']}', `currency3` = '{$reg_new['currency3']}', `battle_rank` = '{$reg_new['battle_rank']}', `xp` = '{$reg_new['xp']}', `name` = '{$this->params['json']['first_name']} {$this->params['json']['last_name']}', `referrer_reg` = '{$this->params['json']['referrer']}', `referrer` = '{$this->params['json']['referrer']}', `level` = '{$reg_new['level']}', `creation` = '{$time}'");
			
			
			 
			
            $_Api = ['uid' => $this->db->insert_id(), 'platform_id' => 'male', 'currency1' => $reg_new['currency1'], 'currency2' => $reg_new['currency2'], 'currency3' => $reg_new['currency3'],
			              'energy' => ['work' => ['current' => 90, 'max' => ['base' => 90, 'temp' => 0], 'stamp' => $time], 'pve' => ['current' => 90, 'max' => ['base' => 90, 'temp' => 0], 'stamp' => $time]], 'session_count' => 1, 'drop_factor' => [],
						  
			              'settings' => ['room_id' => 1, 'sound' => 'yes', 'sound_volume' => 42, 'music' => 'yes', 'music_volume' => 23, 'prompt_referral' => ['level' => 0, 'invite_count' => 0, 'next_stamp' => 0, 'post_message' => 'yes']] ,
						  'platform_last_name' => $this->params['json']['last_name'], 'platform_first_name' => $this->params['json']['first_name'], 'creation' =>  $time, 'avatar' => $this->params['json']['avatar'], 'friend_count' => intval($this->params['json']['friends_count']), 'invite_count' => false,
						  'stat' => [['name' => 'skill1', 'base' => 1, 'store_male' => 1, 'store_female' => 1, 'temp' => 0],['name' => 'skill2', 'base' => 1, 'store_male' => 1, 'store_female' => 1, 'temp' => 0]], 'battle_rank' => $reg_new['battle_rank'],
						  'buff' => $this->userBuff(),
						  'room' => $this->userRoom(),
			];
		 
		return $_Api;
		
    }		
	
    private function userBuff()
    {
	    $buff = $this->db->super_query( "SELECT * FROM `mad_buff` WHERE `soc_id` = '{$this->params['json']['user_id']}'", 1);
		
		foreach ($buff as $item){
			     $items[] = ['id' => (int) $item['buff_id'], 'stamp' => (int) $item['buff_end']];
		}
		return $items;		
    }	
    private function userRoom()
    {
	    $room = $this->db->super_query( "SELECT room_id, stamp FROM `mad_room` WHERE `soc_id` = '{$this->params['json']['user_id']}'", 1);
		
		foreach ($room as $item){
			     $items[] = ['id' => (int) $item['room_id'], 'stamp' => (int) $item['stamp']];
		}
		return $items;		
    }
    private function userInventory()
    {
	    $inventory = $this->db->super_query( "SELECT inventory_id, count, stamp FROM `mad_inventory` WHERE `soc_id` = '{$this->params['json']['user_id']}'", 1);
		
		foreach ($inventory as $item){
			     $items[] = ['id' => (int) $item['inventory_id'], 'count' => (int) $item['count'], 'stamp' => (int) $item['stamp']];
		}
		return $items;		
    }
    private function userCollection()
    {
	    $collection = $this->db->super_query( "SELECT collection_id, count, stamp FROM `mad_collection` WHERE `soc_id` = '{$this->params['json']['user_id']}'", 1);
		
		foreach ($collection as $item){
			     $items[] = ['id' => (int) $item['collection_id'], 'count' => (int) $item['count'], 'stamp' => (int) $item['stamp']];
		}
		return $items;		
    }	
    private function userDByes($user)
    {
		    //0,3 base_skill, 4,7 store_male_skill, 8, 11, store_female_skill, 	12, 15 temp_skill_skill
			
			
			
		    $base_skill = explode(',', $user['skill']);
		

            $_Api = ['uid' => intval($user['id']),
            		 'platform_id' => intval($user['soc_id']),
					 'currency1' => intval($user['currency1']),
					 'currency2' => intval($user['currency2']),
					 'currency3' => intval($user['currency3']),
					 'room_id' => (int) $user['room_id'],
			              'energy' => ['work' => ['current' => intval($user['energy_work']),
                       				              'max' => ['base' => 90, 'temp' => 0],
												  'stamp' => intval($user['energy_work_time'])],
									   'pve' => ['current' => intval($user['energy_pve']),
    									         'max' => ['base' => 90,
												 'temp' => 0],
												 'stamp' => intval($user['energy_pve_time'])]
									  ],	
                          'stat' => [['name' => 'skill1',
						                         'base' => (int) $base_skill[0],
 						                         'store_male' => (int) $base_skill[4],
												 'store_female' => (int) $base_skill[8],
												 'temp' => (int) $base_skill[12]
												 ],
									 ['name' => 'skill2',
									             'base' => (int) $base_skill[1],
 						                         'store_male' => (int) $base_skill[5],
												 'store_female' => (int) $base_skill[9],
												 'stemp' => (int) $base_skill[13]
									 ]
						  ],													 
			              'session' =>  $this->userNewSession($user),
						  'fortune_level' => (int) $user['cell_lvl'],
                          'fortune_xp' => (int) $user['cell_xp'], 
						  'drop_factor' => [],	
                          'buff' => $this->userBuff(),
                          'room' => $this->userRoom(),	
                          'inventory' => $this->userInventory(),
						  'collection' => $this->userCollection(),
			              'settings' => ['prompt_referral' => ['level' => 0,
 						                                       'invite_count' => 0,
															   'next_stamp' => 0,
															   'post_message' => 'yes'
															  ]
										] ,
						  'platform_last_name' => $user['last_name'],
						  'platform_first_name' => $user['first_name'],
						  'creation' => $user['creation'],
						  'avatar' => $user['avatar'],
						  'friend_count' => $user['friend_count'],
						  'invite_count' => $user['invite_count']
			];
		 
		return $_Api;
		
    }	
	
    private function userNewSession($user)
    { 	
	   $user['id'] = (int) $user['id'];
	   $time = (int) $this->datetime->time_server();
	
	   /* Узнаем сколько  сессий */
	   $session_count = $this->db->super_query("SELECT COUNT(*) AS cnt FROM `mad_session_auth` WHERE session_auth_user_id = '{$user['id']}'");
	   $session_count['cnt'] = (int) $session_count['cnt'] + 1;
	   
	   /* Закрываем всё старые сессии */
	   $this->db->query("UPDATE `mad_session_auth` SET session_status = '0' WHERE session_auth_user_id = '{$user['id']}'");
	   
	   /* Новый hash */
	   $session_hash = md5($user['id'] . '_' . $this->request->server['HTTP_USER_AGENT'] . '_' . $this->request->server['REMOTE_ADDR'] . '_' . $time);
	   
	   /* Пишем в БД */
	   $this->db->query("INSERT INTO mad_session_auth (session_auth_key, session_auth_user_id, session_auth_ua, session_auth_ip, session_status, session_auth_time, session_count) VALUES('{$session_hash}', '{$user['id']}', '{$this->request->server[HTTP_USER_AGENT]}', '{$this->request->server[REMOTE_ADDR]}', '1', '{$time}', '{$session_count['cnt']}')");
	   
	   return ['session_hash' => $session_hash, 'session_count' => $session_count['cnt'], 'session_time' => $time];
	
	}
  
	
    private function userDBcheck()
    {
		$time = $this->datetime->time_server();
	    $user = $this->db->super_query( "SELECT * FROM `mad_users` WHERE `soc_id` = '{$this->params['json']['user_id']}'");
		$reg = ($user) ? 1 : 0;
    		
		
		//Обновляем данные! На лету...
		if($reg){
		   $_work_energy  = energy(['total' => intval($user['energy_work']), 'energy_min' => 0, 'energy_max' => 90, 'time_low' => intval($user['energy_work_time']), 'time' => 90]); //energy  work 
		   
		   
		   $this->db->query("UPDATE `mad_users` SET `cell_id` = null WHERE `soc_id` = '{$this->params['json']['user_id']}'");
		   
		   
		  // if($_work_energy['update']){
			 // echo $time;
			 //  $this->db->query("UPDATE  `mad_users` SET  `energy_work` = '{$_work_energy['new']}', `energy_pve` = '{$_pve_energy['new']}', `energy_work_time` = '{$time}', `energy_pve_time` = '{$time}' WHERE  `soc_id` = '{$this->params['json']['user_id']}'");
			   
		   //}
		   //$_pve_energy  = energy(['total' => intval($user['energy_pve']), 'energy_min' => 0, 'energy_max' => 5, 'time_low' => intval($user['energy_pve_time']), 'time' => 300]); //energy  pve
		   
		   
		   
		  // $this->db->query("UPDATE  `mad_users` SET  `energy_work` = '{$_work_energy['new']}', `energy_pve` = '{$_pve_energy['new']}', `energy_work_time` = '{$time}', `energy_pve_time` = '{$time}' WHERE  `soc_id` = '{$this->params['json']['user_id']}'");
			
		   $user['energy_work'] = $_work_energy['new'];
		   $user['energy_pve'] = $_pve_energy['new'];
			
		}
		 
		return ['row' => $user, 'reg' => $reg];
		
    }		
}

  