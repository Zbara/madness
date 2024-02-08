<?php

/**
 * Class indexController
 */
class indexController extends Controller
{
    var $result = [];
    var $data = [];

    /**
     * @return array
     */
    public function get()
    {
        /** запуск скрипта */
        $this->post();

        /** проверка на регистрацию */
        if ($this->result['user_id']) {
            return $this->login();
        } else return $this->reg();
    }

    /**
     * @return array
     */
    private function post()
    {
        /** @post * */
        $this->data['platform_id'] = (int)$this->server->post->flashvars['viewer_id'];
        $this->data['referrer'] = $this->db->escape($this->server->post->flashvars['referrer']);
        $this->data['last_name'] = $this->db->escape($this->server->post->params['last_name']);
        $this->data['first_name'] = $this->db->escape($this->server->post->params['first_name']);
        $this->data['country'] = $this->db->escape($this->server->post->params['country']);
        $this->data['city'] = $this->db->escape($this->server->post->params['city']);
        $this->data['avatar'] = $this->db->escape($this->server->post->params['avatar']);
        $this->data['birthdate'] = $this->db->escape($this->server->post->params['birthdate']);
        $this->data['timezone'] = (int)$this->server->post->params['timezone'];
        $this->data['app_friends'] = $this->db->escape($this->server->post->params['app_friends']);
        $this->data['time'] = (int)unixTime();
        $this->data['friends_count'] = (int)$this->server->post->params['friends_count'];

        /** @sex * */
        $sex = $this->params->post->data['sex'];
        $this->data['sex'] = $sex == 'unknown' ? 'unknown' : $sex == 'female' ? 'female' : 'male';

        /** @country AND @city */
        $country_city[0] = ($this->data['country']) ? $this->data['country'] : 'unknown';
        $country_city[1] = ($this->data['city']) ? $this->data['city'] : 'unknown';
        $this->data['country_city'] = implode(",", $country_city);
        
        return $this->users();
    }

    /**
     * @return array
     */
    private function reg()
    {
        /** @var  $reg регистрация */
        $reg = $this->db->SQLquery("INSERT INTO `mad_users` SET `platform_id` = '{$this->data['platform_id']}', `user_creation` = '{$this->data['time']}', `user_last_date` = '{$this->data['time']}', `user_last_name` = '{$this->data['last_name']}', `user_first_name` = '{$this->data['first_name']}', `user_name` = '{$this->data['last_name']} {$this->data['first_name']}', `user_sex` = '{$this->data['sex']}', `user_country_city` = '{$this->data['country_city']}', `user_avatar` = '{$this->data['avatar']}', `user_birthdate` = '{$this->data['birthdate']}'", SQL_RESULT_INSERTED);

        /** проверка на регистрацию */
        if ($reg > 0) {
            $this->users();
            return $this->login();
        } else return ['data' => [], 'error' => ['message' => 'default', 'code' => 15]];
    } 

    /**
     * @param $user
     * @return array
     */
    private function login()
    {
        /** @var  $session - открытие новой сессии */
        $session = $this->session();
        /** @var  $energy */
        $energy = $this->energy(['work' => [$this->result['energy_work'], $this->result['energy_work_time'], 90, 90], 'pvp' => [$this->result['energy_pvp'], $this->result['energy_pvp_time'], 5, 300]]);

        /** @var  $music */
        $music = explode(',', $this->result['user_music']);

        /** @var up exp */
        // $this->result['user_xp_total'] = 62015;
        $level = $this->getLevel($this->result['user_xp_total']);

        /** @var $_Api - json массив */
        $_Api = [
            'uid' => (int)$this->result['user_id'],
            'platform_id' => (int)$this->result['platform_id'],
            'currency1' => (int)$this->result['user_currency1'],
            'currency2' => (int)$this->result['user_currency2'],
            'currency3' => (int)$this->result['user_currency3'],
            'level' => (int)$level[0],
            'xp' => (int)$level[1],
            'session' => $session[0],
            'sid' => $session[1],
            'settings' => [
                'sound' => ($music[0]) ? 'yes' : 'no',
                'sound_volume' => (int)$music[1],
                'music' => ($music[2]) ? 'yes' : 'no',
                'music_volume' => (int)$music[3],
                'game_url' => '{game_url}'
            ],
            'platform_last_name' => $this->result['user_last_name'],
            'platform_first_name' => $this->result['user_first_name'],
            'platform_avatar' => $this->result['user_avatar'],
            'creation' => (int)$this->result['user_creation'],
            'energy' => [
                'work' => [
                    'current' => (int)$energy['work'][0],
                    'max' => [
                        'base' => 90,
                        'temp' => 0
                    ],
                    'stamp' => ($this->result['energy_work'] < $energy['work'][0]) ? (int)$energy['work'][1] : (int)$this->result['energy_work_time']
                ],
                'pvp' => [
                    'current' => (int)$energy['pvp'][0],
                    'max' => [
                        'base' => 5,
                        'temp' => 0
                    ],
                    'stamp' => ($this->result['energy_pvp'] < $energy['pvp'][0]) ? (int)$energy['pvp'][1] : (int)$this->result['energy_pvp_time']
                ]
            ],
            'name' => $this->result['user_name'],
            'sex' => $this->result['user_sex'],
            'friend_count' => $this->data['friends_count'],
            'invite_count' => 0,
            'top' => [
                'xp' => [
                    'total' => (int)$this->result['user_xp_total'], //весь опыт
                ],
                'battle_rank' => [
                    'total' => (int)$this->result['user_battle_rank'], //весь опыт
                ]
            ],
            'mission' => $this->mission()
        ];
        $this->update([
            'energy' => $energy,
            'level' => $level
        ]);
        return ['data' => ['user' => $_Api, 'news' => []]];
    }


    private function mission(){
        /** @var  $job */
        $job = $this->db->SQLquery("SELECT * FROM `mad_job` WHERE `job_user_id` = '{$this->result['user_id']}'", SQL_RESULT_ITEMS);
        
        /** @var  $item */    
        foreach($job  as $item){
            
            /** @var  $mission */
            $mission = explode('|', $item['job_mission_list']);
            $mission_id = explode('|', $item['job_mission_id']);
            
            foreach ($mission as $i => $items){
                

                $job_list[] = ['count' => (int) $items[0], 'id' => (int) $mission_id[$i]];
            }
        }
        return $job_list;
    }
    
    
    /**
     * @param $total
     * @return array|int
     */
    public function getLevel($total)
    {
        $list = [0, 28, 56, 84, 112, 140, 224, 308, 448, 588, 728, 952, 1176, 1400, 1624, 1848, 2072, 2296, 2520, 2744, 2968, 3360, 3752, 4144, 4536, 4928, 5320, 5712, 6104, 6496, 6888, 7365, 7840, 8315, 8792, 9268, 9744, 10220, 10696, 11172, 11648, 12684, 13720, 14756, 15792, 16828, 17864, 18900, 19936, 20972, 22008, 23800, 25592, 27384, 29176, 30968, 32760, 34160, 37240, 40320, 43400, 49364, 55328, 61292];
        $sum = 0;
        foreach ($list as $level => $exp) {
            $sum += $exp;
            if ($sum >= $total) {
                $a = $sum - $total;
                return [$level, $exp - $a];
            }
        }
        return -1;

    }

    /**
     * @param $params
     */
    private function update($params)
    {
        /** @var иницизация массива для хранения даных */
        $arr = [];

        /** @var  $platform_id */
        $platform_id = (int)$this->data['platform_id'];

        /** Массив данных */
        $arr['user_avatar'] = $this->data['avatar'];
        $arr['user_last_date'] = $this->data['time'];
        $arr['user_last_name'] = $this->data['last_name'];
        $arr['user_first_name'] = $this->data['first_name'];
        
        /** Обновляем основную энергию */
        if ($this->result['energy_work'] < $params['energy']['work'][0]) {
            $arr['energy_work'] = (int)$params['energy']['work'][0];
            $arr['energy_work_time'] = (int)$params['energy']['work'][1];
        };

        /** Для боев */
        if ($this->result['energy_pvp'] < $params['energy']['pvp'][0]) {
            $arr['energy_pvp'] = (int)$params['energy']['pvp'][0];
            $arr['energy_pvp_time'] = (int)$params['energy']['pvp'][1];
        };
        /** @var  $update level xp */
        $arr['user_xp_total'] = (int)$this->result['user_xp_total'];
        $arr['user_xp_level'] = (int)$params['level'][1];
        $arr['user_level'] = (int)$params['level'][0];


        $sql = "UPDATE `mad_users`";
        if (!empty($arr)) {
            $count = count($arr);
            $sql .= " SET";
            foreach ($arr as $key => $value) {
                $sql .= " {$key} = '{$value}'";

                $count--;
                if ($count > 0) $sql .= ",";
            }
        }

        $sql .= " WHERE `platform_id` = '{$platform_id}'";

        $this->db->SQLquery($sql, SQL_RESULT_AFFECTED);
    }


    /**
     * @return array
     */
    private function session()
    {
        /** @var  $session_count */
        $session_count = $this->db->SQLquery("SELECT `session_count` FROM `mad_session_auth` WHERE session_auth_user_id = '{$this->result['user_id']}'");
        $session_count['session_count'] = (int)$session_count['session_count'] + 1;

        /** @var  db zg_session_auth */
        $this->db->SQLquery("DELETE FROM `mad_session_auth` WHERE session_auth_user_id = '{$this->result['user_id']}'", SQL_DELETE);

        /** @var  $session_hash */
        $session_hash = md5($this->result['user_id'] . '_' . $this->data['time'] . '_' . $this->data['referrer']);

        /** INSERT */
        $this->db->SQLquery("INSERT INTO `mad_session_auth` SET session_auth_key = '{$session_hash}', session_auth_user_id = '{$this->result['user_id']}', session_status = '1', 	session_auth_time = '{$this->data['time']}', session_count = '{$session_count['session_count']}', session_friends = '{$this->data['app_friends']}', 	session_referrer = '{$this->data['referrer']}'", SQL_RESULT_INSERTED);

        /** @var return */
        return [$session_hash, $session_count['session_count']];
    }

    /**
     * @return array
     */
    private function users()
    {
        /** @var  $platform_id */
        $platform_id = (int)$this->data['platform_id'];

        /** @var return users array */
        return $this->result = $this->db->SQLquery("SELECT * FROM `mad_users` WHERE `platform_id` = '{$platform_id}'");
    }

    /**
     * @param $params
     * @return array
     */
    private function energy($params)
    {
        /** @var  $data */
        $data = [];

        foreach ($params as $key => $value) {
            $minE = $value[0];
            $maxE = $value[2];
            $timeLimit = $value[3];
            $timeStatic = $value[1];
            $timeDynamic = microtime(true);
            $nowE = round(($timeDynamic - $timeStatic) / $timeLimit, 2) + $minE;
            $nowE = ($nowE > $maxE) ? $maxE : $nowE;
            $data[$key] = [round($nowE), $timeDynamic];
        }
        return $data;
    }
}