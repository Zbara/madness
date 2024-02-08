<?php

/**
 * Class nameController
 */
class doController extends Controller
{
    /**
     * @return array
     */
    public function get()
    {
        /** @var  $id */
        $id = (int) $this->server->post->params['id'];
        $lib = $this->xmlLib($id, 'do');

        
        /** проверка есть ли такой id в библиотеке */
        if ($lib) {
            /** @var  $energy */
            $energy = $lib->mission->energy;
            /** проверка на энергию */
            if ($this->user_info['energy_work'] >= $energy) {
                /** @var  $category_id */
                $category_id = $lib->mission->category_id;
                $max_count = $lib->mission->max_count;
                
                /** @var  $job  - была ли эта миссия хоть раз */
                $job = $this->job($category_id);
                
                /** проверка есть ли миссия */
                if ($job) {
                    return $this->jobMission([(int) $id, (int) $category_id, (int) $energy, (int) $max_count, (array) $lib, (array) $job ]);
                } else return $this->jobNew([(int) $id, (int) $category_id, (int) $energy, (int) $max_count, (array) $lib]);
            } else return ['data' => [], 'error' => ['message' => 'default', 'code' => 0]];
        } else return ['data' => [], 'error' => ['message' => 'default', 'code' => 0]];
    }

    
    /**
     * @param $params
     * @return array
     */
    private function jobMission($params){
        
        $getDrop = [];
        /** @var  $mission */
        $mission = explode('|', $params[5]['job_mission_list']);
        /** @var $mission_id */
        $mission_id = explode('|', $params[5]['job_mission_id']);
        
        /**
         * @var  $i
         * @var  $item
         */
        foreach ($mission_id as $i => $item){
            /** ищим миссию */
            if ($params[0] == $item) {
                /** проверка на работенку */
                if ($mission[$i] < $params[3]) {
                    
                    /** @var  $ElementRandom */
                    $ElementRandom = $this->randomElement($params[4]['collection']);
                    
                    /** проверка на элемент */
                    if($ElementRandom){
                        array_push($params[4]['mission']->drops->drop_item, (object) ['type' => 'element', 'id' => $ElementRandom, 'count' => 1]);
                    }
                    /** @var  $mission_list */
                    $mission_list = implode('|', array_replace_new($mission, $i, (int)++$mission[$i]));

                    /** обновляем миссию */
                    $this->db->SQLquery("UPDATE `mad_job` SET `job_mission_list` = '{$mission_list}' WHERE `job_user_id` = '{$this->user_info['user_id']}' and `job_cat_id` = '{$params[1]}'", SQL_RESULT_AFFECTED);
                    
                    /** @var  $mission_end - конец миссиии*/
                    $mission_end = implode('|', $params[4]['mission_list']);
                    
                    /** завершение drops_request_job_zone */
                    if($mission_list == $mission_end ){
                        
                        /** обвноялем и завершаем пишем новые значения */
                        $this->db->SQLquery("UPDATE `mad_job` SET `job_mission_list` = '0|0|0|0|0', `job_count` = job_count+1 WHERE `job_user_id` = '{$this->user_info['user_id']}' and `job_cat_id` = '{$params[1]}'", SQL_RESULT_AFFECTED);
                        
                        return ['data' => ['energy' => $this->energy((int)$params[2]), 'drops_request' => $params[4]['mission']->drops, 'drops_request_job_zone' => $params[4]['mission_category']->drops]];
                    } else return ['data' => ['energy' => $this->energy((int)$params[2]), 'drops_request' => $params[4]['mission']->drops]];
                } else return ['data' => [], 'error' => ['message' => 'default', 'code' => 200]];
            }
        }
    }

    /**
     * @param $element
     * @return array
     */
    private function randomElement($element){
        /** @var  $element */
        $element = explode(',', $element->collect);
        
        /** рандом  */
        if(rand(1,100) == rand(1,100)){
            return array_rand($element);
        }
    }
    
    /**
     * @param $params
     * @return array
     */
    private function jobNew($params)
    {
        /** @var  $mission_id */
        $mission_id = $this->xmlLib($params[1], 'donew');
        
        /**
         * @var  $i
         * @var  $item
         */
        foreach ($mission_id as $i => $item) {
            if ($params[0] == $item) {
                /** @var  $mission_list */
                $mission_list = implode('|', array_replace_new([0,0,0,0,0], $i, 1));
                
                /** @var  $mission_id */
                $mission_id = implode('|', $mission_id);
                
                /** INSERT */
                $this->db->SQLquery("INSERT INTO `mad_job` SET `job_user_id` = '{$this->user_info['user_id']}', `job_created` = UNIX_TIMESTAMP(), `job_cat_id` = '{$params[1]}', `job_mission_list` = '{$mission_list}', `job_mission_id` = '{$mission_id}'", SQL_RESULT_INSERTED);
                
                return ['data' => ['energy' => $this->energy((int) $params[2]), 'drops_request' => $params[5]['drops']]];
            }
        }
    }

    /**
     * @param $energy
     * @return array
     */
    private function energy($energy){
        /** обновляем количество энергии */
        $this->db->SQLquery("UPDATE `mad_users` SET `energy_work` = energy_work-{$energy}, `energy_work_time` = UNIX_TIMESTAMP() WHERE `user_id` = '{$this->user_info['user_id']}'", SQL_RESULT_AFFECTED);
        
        return ['work' => ['current' => (int) $this->user_info['energy_work']-$energy, 'stamp' => unixTime()]];
    }

    /**
     * @param $id
     * @return mixed
     */
    private function job($id)
    {
        return $this->db->SQLquery("SELECT * FROM `mad_job` WHERE `job_user_id` = '{$this->user_info['user_id']}' and `job_cat_id` = '{$id}' LIMIT 1");
    }

    /**
     * @param $id
     * @return bool
     */
    public function xmlLib($id, $type)
    {
        
        /** @var  $xml */
        $xml = json_decode(json_encode(simplexml_load_string(file_get_contents(root . '/library/ver2_lib.xml'))), false);

        
        switch ($type){
            /** просмотр миссии */
            case 'do':
                $array = (object) [
                    'mission' => [],
                    'mission_list' => [],
                    'collection' => [],
                    'mission_category' => []
                ];
                
                /** @var  $item */
                foreach ($xml->mission->item as $item) {
                    if ($item->id == $id) {
                        /** @var  $category_id */
                        $category_id = (int) $item->category_id;
                        $array->mission = (object) $item;
                        
                        /** @var  $element */
                        foreach ($xml->collection->item as $element){
                            if($category_id == $element->id) {
                                $array->collection = $element;
                            }
                        }
                        /** @var  $items */
                        foreach ($xml->mission->item as $items){
                            if ($items->category_id == $category_id) {
                                $array->mission_list[] = 
                                    //'id' => (int) $items->id,
                                    //'max_count' => 
                                        (int) $items->max_count
                                ;
                            }
                        }
                        /** @var  $mission_category */
                        foreach ($xml->mission_category->item as $mission_category){
                            if($mission_category->id ==  $category_id){
                                $array->mission_category = $mission_category;
                            }
                        }
                        return $array;
                    }
                     
                }
                return false;
                break;
                
             /** создание миссии */   
            case 'donew':
                /** @var  $mission_id */
                $mission_id = [];
                
                /** @var  $item */
                foreach ($xml->mission->item as $item) {
                    if ($item->category_id == $id) {
                        $mission_id[] = (int) $item->id;
                    }
                }
                return $mission_id;
                break;
        }
    }
}