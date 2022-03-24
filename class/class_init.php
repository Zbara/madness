<?php

class initPlatform extends Controller
{
    private $registry;

    public function init()
    {
		
		 $user = $this->db->super_query( "SELECT * FROM `mad_users` WHERE `soc_id` = '{$this->params['json']['user_id']}'");
		 		
         $_Api = [];
		 
		 $_Api['library_url'] = $this->request->server[REQUEST_SCHEME].$this->config[library_url].time();
		 //$_Api['library_url'] = 'http://dw.playflock.com/madnew-vk/control/lib_1466417222_ru_http.xml?'.time();
		 
		 
		 $_Api['locale']['js_init'] = ['error_default' => 'Произошла ошибка. Пожалуйста, перезапустите Безумие, если ошибка повторится, обратитесь в техническую поддержку по адресу:', 'error_button' => 'Перезагрузить игру'];
		 
		 $_Api['user'] = ['registered' => ($user) ? 'yes' : 'no'] ;
 
		 $API['controls'] = $_Api;
		 
        return $this->functions->echoJson(['data' => $API]);
    }
}