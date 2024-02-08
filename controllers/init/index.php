<?php
/**
 * Class indexController
 */
class indexController extends Controller
{
    /**
     * @return array
     * @method load lib client
     */
    public function get()
    {
        /** @var  $soc_id  клиент $language язык  */
        $platform_id = (int) $this->server->post->flashvars['viewer_id'];

        /** @var  $user проверка есть ли такой человек в БД*/
        $user = $this->db->SQLquery("SELECT `user_id` FROM `mad_users` WHERE `platform_id` = '{$platform_id}'");

        /** @var library_url*/
        $_Api['library_url'] = 'https://zbara.ru/library/ver2_lib.xml';

        $_Api['top_url'] = ['by_level' => 'https://zbara.pro/library/by_level.xml?' . time(),
            'by_rate' => 'https://zbara.pro/library/by_rate.xml?' . time()
        ];
        
        
        $_Api['user'] = [
            'registered' => ($user) ? 'yes' : 'no'
        ];

        return ['data' => ['controls' => $_Api]];
    }
}