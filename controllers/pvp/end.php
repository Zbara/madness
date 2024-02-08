<?php

/**
 * Class endController
 */
class endController extends Controller
{
    /** @var int */
    var $uid = 0;
    var $sid = 0;
    var $result;
    var $time = 0;
    var $start = 0;
    var $end = 0;
    var $pvp_id;
    var $unix_end = 0;
    /**
     * @return array
     */
    public function get()
    {
        /** @var  $uid and $sid параметры клиента */
        $this->time = (int)unixTime();
        $this->uid = (int)$this->server->post->params['uid'];
        $this->start = (float)$this->server->post->params['start'];
        $this->end = (float)$this->server->post->params['end'];
        $this->unix_end = microtime(true);
        

        /** проверка пользователя */
        $this->users();

        if ($this->result->pvp_id > 0) {
            $this->pvp();
            
            $end_time = round($this->end - $this->start, 5);
            $end_time_unix = round(microtime(true) - $this->pvp_id->unix_start, 5);
 
            $this->db->SQLquery("UPDATE `ign_pvp` SET `unix_total` = '{$end_time_unix}', `unix_end` = '{$this->unix_end}', `microtime_end` = '{$this->end}', `microtime_total` = '{$end_time}' WHERE `id` = '{$this->result->pvp_id}'", SQL_RESULT_AFFECTED);
            $this->db->SQLquery("UPDATE `ign_users` SET `pvp_id` = 0 WHERE `user_id` = '{$this->uid}'", SQL_RESULT_AFFECTED);


            $photo = $this->img_pvp($end_time);
            unlink(include_dir . '/temp/' . $this->result->pvp_id . '.png');
            
            
            return ['data' => ['pvp' => ['id' => (int)  $this->result->pvp_id , 'total' => (float) $end_time, 'pvp' => $photo]]];
        } else return ['data' => [], 'error' => ['message' => 'default', 'code' => 20]];
    }



    /**
     * @param $params
     * @return string
     */
    private function img_pvp($end)
    {

        /** подключаем класс для работы */
        include include_dir . '/core/class_img.php';
        
        $ttfImg = new ttfTextOnImage(include_dir . '/libs/item.png');
        // Имя
        $ttfImg->setFont(include_dir . '/libs/Tomato.ttf', 18, "#a5b764");
        $ttfImg->writeText(127, 110, $this->result->user_first_name);// . ' ' . $params[2]['my']['last_name']);

        $ttfImg->setFont(include_dir . '/libs/Tomato.ttf', 15, "#a5b764");
        $ttfImg->writeText(230, 203, 'iГений');

        $ttfImg->setFont(include_dir . '/libs/Tomato.ttf', 13, "#a5b764");

        $name_sec = $this->Datagram($end);

        $ttfImg->writeText(10, 174, 'Мой результат: ' . $end . ' ' . $name_sec);

        
        $ttfImg->writeImg(25,18, $this->result->user_avatar);
        
        $ttfImg->output(include_dir . '/temp/' . $this->result->pvp_id . '.png');

        return $this->zvImgURI(include_dir . '/temp/' . $this->result->pvp_id . '.png');
    }




    /**
     * @param $number
     * @return string
     */
    private function Datagram($number){
        $ts = (int) $number;
        if($ts < 1){
            $date = 'секунд';
        } elseif ($ts < 60) {
            $date = $this->gram($ts, ['секунду', 'секунды', 'секунд']);
        }
        return $date;
    }

    /**
     * @param $number
     * @param $titles
     * @param $no_number
     * @return mixed
     */
    private function gram($number, $titles, $no_number){
        $cases = [2, 0, 1, 1, 1, 2];
        return $titles[($number%100>4 && $number%100<20)? 2 : $cases[($number%10<5) ? $number%10:5]];
    }

    /**
     * @param $image
     * @param string $mime
     * @return string
     */
    private function zvImgURI($image, $mime = '') {
        return 'data: '.(function_exists('mime_content_type') ? mime_content_type($image) : $mime).';base64,'.base64_encode(file_get_contents($image));
    }





    /**
     * получаем информацю о игре
     * @return array
     */
    private function pvp()
    {
        return $this->pvp_id = (object)$this->db->SQLquery("SELECT * FROM  `ign_pvp` WHERE `id` = '{$this->result->pvp_id}' LIMIT 1");
    }

    /**
     * получаем информацю о игроке 
     * @return array
     */
    private function users()
    {
        return $this->result = (object)$this->db->SQLquery("SELECT * FROM  `ign_users` WHERE `user_id` = '{$this->uid}' LIMIT 1");
    }    
}