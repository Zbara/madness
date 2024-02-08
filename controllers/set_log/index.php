<?php
class indexController extends Controller
{
    var $data = [];
    
    public function get()
    {
        /** data */
        $this->data['platform_id'] = (int)$this->server->post->flashvars['viewer_id'];
        $this->data['reason'] = $this->db->escape($this->server->post->params['reason']);
        $this->data['type'] = $this->db->escape($this->server->post->params['type']);
        
        $this->db->SQLquery("INSERT INTO `mad_set_log` SET `log_platform_id` = '{$this->data['platform_id']}', `log_type` = '{$this->data['type']}', `log_reason` = '{$this->data['reason']}', `log_time` = '{$this->config['unixtime']}'", SQL_RESULT_INSERTED);
        
        return [
            'data' => [
                $this->data
            ]
        ];
    }
}
