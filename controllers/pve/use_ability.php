<?php

/**
 * Class contacts
 */
class use_abilityController extends Controller
{
    /**
     * @return array
     */
    public function get()
    {
        /** @var  $b */
        $b = [
            (int)unixTime(),
            (int)1333,
            (int)301,
            (int)rand(300, 302)
        ];
        
        /** проверка на бой */
        if ($this->user_info['pvp_id'] > 0) {

            /** @var  $pvp_id */
            $pvp_id = $this->db->SQLquery("SELECT * FROM  `mad_pve` WHERE `pve_id` = '{$this->user_info['pvp_id']}' LIMIT 1");

            /** проверка на время */
            if ($pvp_id['pve_end_battle'] >= $b[0]) {
                /** проверка на здоровье */
                if ($pvp_id['pve_health'] > 0) {
                    
                    /** @var  $pve_user */
                    $pve_user = $this->db->SQLquery("SELECT * FROM `mad_pve_users` WHERE `pve_battle_id` = '{$this->user_info['pvp_id']}' and `pve_user` = '{$this->user_info['user_id']}' LIMIT 1");
                    
                    /** если 0 то не бьем */
                    if ($pve_user['pve_health'] > 0) {
                        /** обновление таблицы босса */
                        $this->db->SQLquery("UPDATE `mad_pve` SET pve_health = pve_health-{$b[2]} WHERE pve_id = '{$this->user_info['pvp_id']}'", SQL_RESULT_AFFECTED);
                        /** обновление в таблице участники */
                        $this->db->SQLquery("UPDATE `mad_pve_users` SET pve_user_damage = pve_user_damage+{$b[2]}, `pve_last` = UNIX_TIMESTAMP(), pve_health = pve_health-{$b[3]} WHERE `pve_battle_id` = '{$this->user_info['pvp_id']}'", SQL_RESULT_AFFECTED);
                        return ['data' => ['pvp' => $b]];
                    } else return ['data' => ['pvp' => 'health = 0']];
                } else return $this->battle();
            } else return ['data' => ['pvp' => ['time end = ' => (int)$this->user_info['pvp_id']]]];
        } else return ['data' => ['pvp' => ['no pvp' => false]]];
    }
    
    private function battle(){
        
    }
}