<?php

/**
 * Class contacts
 */
class startController extends Controller
{
    var $uid = 0;
    var $sid = 0;
    var $result;
    var $time = 0;
    var $start = 0;
    var $microtime;
    
    /** 
     * @return array
     */
    public function get()
    { 
        /** @var  $uid and $sid параметры клиента */
        $this->uid = (int) $this->server->post->params['uid'];
        $this->sid = (int) $this->server->post->params['sid'];
        $this->start = (float) $this->server->post->params['start'];
        $this->microtime = (float) microtime(true);
        
        /** проверка пользователя */
        $this->users();
        
        /** @var  $new */
        $pve_id = $this->db->SQLquery("INSERT INTO `ign_pvp` SET `user_id` = '{$this->uid}', `unix_start` = '{$this->microtime}', `microtime_start` = '{$this->start}', `sid` = '{$this->sid}'", SQL_RESULT_INSERTED);

        /** обнволяем pvp_id */
        $this->db->SQLquery("UPDATE `ign_users` SET `pvp_id` = '{$pve_id}' WHERE `user_id` = '{$this->uid}'", SQL_RESULT_AFFECTED);
        
        return ['data' => ['pvp' => ['id' => (int)  $pve_id, 'unix' => $this->microtime, 'start' => $this->start]]];
    }
    /**
     * получаем информацю о игроке
     * @return array
     */
    private function users(){
        return $this->result = (object) $this->db->SQLquery("SELECT * FROM  `ign_users` WHERE `user_id` = '{$this->uid}' LIMIT 1");
    }
}