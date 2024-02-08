<?php
/**
 * Class indexController
 * класс для получения информации 
 * о своих друзьях
 */
class indexController extends Controller
{
    /**
     * @return array
     */
    public function get()
    {
        /** @var  $from - смещение*/
        $from = (int) $this->server->post->params['from'];

        /** @var  $app_friends - список друзей */
        $app_friends = explode(',', $this->session_auth['session_friends']);
        
        /** @var  $app_friends - смещаем массив */
        $app_friends = array_slice($app_friends, $from, 30);
 
        return $this->users($app_friends);
    }

    /**
     * @param $user_id
     * @return array 
     */
    public function users($user_id)
    {
        /** @var  $result */
        $result = [];
        
        /** @var  $uid = цикл */
        foreach ($user_id as $uid) {
            $data = $this->db->SQLquery("SELECT * FROM  `mad_users` WHERE `platform_id` = '{$uid}' LIMIT 1");
            if ($data) {
                $result[] = [
                    'uid' => (int) $data['user_id'],
                    'platform_id' => (int)$data['platform_id'],
                    'platform_first_name' => $data['user_first_name'],
                    'platform_last_name' => $data['user_last_name'],
                    'platform_avatar' => $data['user_avatar'],
                    'last_seen' => date('d-m-Y H:i:s', $data['	user_last_date']),
                    'level' => (int)$data['level'],
                    'xp' => (int)$data['xp'],
                    'pvp' => [] //$this->userPvp(1)//$data['user_id']
                ];
            }
        }
        return ['data' => ['contacts' => $result]];
    }

    /**
     * @param $id 
     * @return array
     */
    private function userPvp($id)
    {
        /** @var  $pvp -  запрос в БД */
        $pvp = $this->db->SQLquery("SELECT * FROM `ign_pvp` WHERE `user_id` = '{$id}' ORDER by `microtime_total` DESC LIMIT 100", SQL_RESULT_ITEMS);

        /** сортируем */
        usort($pvp, function ($a, $b) {
            return ($a['total'] < $b['total']) ? 1 : -1;
        });

        /** @var  $item = цикл */
        foreach ($pvp as $item) {
            $items[] = ['id' => (int)$item['id'],
                'uid' => (int)$item['user_id'],
                'start' => (int)$item['start'],
                'microtime_start' => (int)$item['microtime_start'],
                'microtime_end' => (int)$item['microtime_end'],
                'total' => $item['microtime_total']
            ];
        }

        return ['item' => $items, 'max' => $items[0]];
    }
}