<?php

/**
 * Class nameController
 */
class nameController extends Controller
{
    /**
     * @return array
     */
    public function get()
    {
        $name = (string)$this->db->escape($this->server->post->params['name']);
        
        /**  Проверка имени */
        if (strlen($name) >= 2 and strlen($name) <= 32) {
            /** обновляем в БД */
            $this->db->SQLquery("UPDATE `mad_users` SET `user_name` = '{$name}' WHERE `user_id` = '{$this->user_info['user_id']}'", SQL_RESULT_AFFECTED);
            return ['data' => []];            
        } else return ['data' => [], 'error' => ['message' => 'Имя должно быть, от 2 символов и до 32. А у Вас их: ' . strlen($name), 'code' => 23]];
    }
}