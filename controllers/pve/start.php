<?php

/**
 * Class contacts
 */
class startController extends Controller
{
    var $boss_id = 1;


    /**
     * @return array
     */
    public function get()
    {
        /** @var  $b - успер перемменая */
        $b = [
            (int) unixTime(),
            (int) 1333,
            (int) unixTime() + 7200
        ];
        
        /** @var  $boss_id параметры клиента */
        $this->boss_id = (int) $this->server->post->params['boss_id'];

        /** проверка на бой */
        if($this->user_info['pvp_id'] == 0){
            /** @var  $pve_id */
            $pve_id = $this->db->SQLquery("INSERT INTO `mad_pve` SET `pve_created_id` = '{$this->user_info['user_id']}', `pve_start_batle` = '{$b[0]}', `pve_end_battle` = '{$b[2]}' , `pve_boss_id` = '{$this->boss_id}', `pve_health` = '{$b[1]}'", SQL_RESULT_INSERTED);
            $pve = $this->db->SQLquery("INSERT INTO `mad_pve_users` SET `pve_battle_id` = '{$pve_id}', `pve_user` = '{$this->user_info['user_id']}', `pve_battle_reg` = UNIX_TIMESTAMP(), `pve_last` = UNIX_TIMESTAMP(), `pve_health` = 51", SQL_RESULT_INSERTED);
            $this->db->SQLquery("UPDATE `mad_users` SET `pvp_id` = '{$pve_id}' WHERE `user_id` = '{$this->user_info['user_id']}'", SQL_RESULT_AFFECTED);
            return ['data' => ['pvp' => ['id' => (int)  $pve]]];
        } else return ['data' => ['pvp' => ['uid = ' => (int)  $this->user_info['pvp_id']]]];
    }
}