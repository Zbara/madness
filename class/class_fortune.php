<?php

class fortune extends Controller
{
    private $registry;
    var $lib = null;

    public function play()
    {
		/* Все данные */
        $time = (int)$this->datetime->time_server();
        $user = $this->cellId();
        $cell_xp = (int)$user[1]['cell_xp'] + 1;
        $cell_level = (int)$user[1]['cell_lvl'];

        /* Ошибка игра есть */
        if((int) $user[1]['cell_id']){
           return $this->functions->echoJson(['error' => ['message' => 'play_id_' . (int) $user[1]['cell_id'], 'code' => 0]]);
        };
        /* Рандом */
        $cell = $this->random();
        $this->lib = $this->parseLib();
        $fortune_id = $this->parseCube($cell);
        $cells = implode(",", $cell);

        //Если exp 7 обновляем в БД лвл
        if ($cell_xp >= (int)$this->lib->fortune_level->item[$cell_level - 1]->xp) {
            $cell_xp = 0;
            $cell_level = (int)$cell_level + 1;
        };

        /* Пишем в БД */
        $this->db->query("INSERT INTO `mad_fortune` SET `soc_id` = '{$this->params['json']['user_id']}', `user_id` = '{$this->params['json']['uid']}', `stamp` = '{$time}', `cell` = '{$cells}', `status` = '1'");

        $this->db->query("UPDATE `mad_users` SET `cell_id` = '{$this->db->insert_id()}', `cell_xp` = '{$cell_xp}', `cell_lvl` = '{$cell_level}' WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `id` = '{$this->params['json']['uid']}'");


        $_Api['fortune'] = ['cell1' => $cell[1], 'cell2' => $cell[2], 'cell3' => $cell[3], 'cell_count' => 0, 'fortune_id' => $fortune_id];


        return $this->functions->echoJson(['data' => $_Api]);
    }

    public function get_prize()
    {
        $time = (int)$this->datetime->time_server();
        $user = $this->cellId();
        $this->lib = $this->parseLib();

        $cell_id = (int)$user[1]['cell_id'];


        $cell_info = $this->db->super_query("SELECT  * FROM `mad_fortune` WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `user_id` = '{$this->params['json']['uid']}' AND `id` = '{$cell_id}'");

        $cell = explode(",", $cell_info['cell']);

        $drops = $this->parseCubeItem($cell);

        //$item = $this->randomItems($drops);

        $drops_array = [];
        foreach ($drops->drops->drop_item as $value) {


            $type = (string)$value->type;
            $id = (int)$value->id;
            $count = (int)$value->count;

            if (!$id) $drops_array[$type] = ['count' => $count];
            if ($id) $drops_array[$type] = [['id' => $id, 'count' => $count]];

        }
        $this->updateNewInfo($drops_array);

        return $this->functions->echoJson(['data' => ['drops' => $drops_array]]);
    }

    public function turn()
    {
        /* Юзер */
        $user = $this->cellId();
        $cell_level = (int)$user[1]['cell_lvl'];
        $cell_id_lib = (string)'cell' . (int)$this->params['json']['cell_id'];

        /* Если нет игры */
        if (!$user[1]) {
            return $this->functions->echoJson(['error' => ['message' => 'default', 'code' => 0]]);
        };

        /* Библиотека */
        $this->lib = $this->parseLib();
        $cell_fid = (int)$user[1]['cell_id'];

        /* Делаем запрос */
        $cell_info = $this->db->super_query("SELECT  * FROM `mad_fortune` WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `user_id` = '{$this->params['json']['uid']}' AND `id` = '{$cell_fid}'");

        /* Много перебросов или кости 	не возмо кидать */
        if ($cell_info['count'] >= (int)$this->lib->fortune_level->item[$cell_level - 1]->turn->count) {
            return $this->functions->echoJson(['error' => ['message' => 'count', 'code' => 0]]);
        } elseif ($this->lib->fortune_level->item[$cell_level - 1]->turn->$cell_id_lib == 'no') {
            return $this->functions->echoJson(['error' => ['message' => $cell_id_lib, 'code' => 0]]);
        };

        /* Старая комбинация */
        $cell_old = explode(",", $cell_info['cell']);

        /* Рандом */
        $cell_new = $this->random();
        $cell_id = (int)$this->params['json']['cell_id'] - 1;
        $cell_old[$cell_id] = $cell_new[$cell_id + 1];
        $fortune_id = $this->parseCube(['1' => (int)$cell_old[0], '2' => (int)$cell_old[1], '3' => (int)$cell_old[2]]);

        /* Обновляем данные в БД */
        $cells = implode(",", $cell_old);
        $this->db->query("UPDATE `mad_fortune` SET `cell` = '{$cells}', `count` = count+1 WHERE `user_id` = '{$this->params['json']['uid']}' AND `id` = '{$cell_fid}'");

        /* Выводим данные */
        $_Api['fortune'] = ['cell1' => (int)$cell_old[0], 'cell2' => (int)$cell_old[1], 'cell3' => (int)$cell_old[2], 'cell_count' => (int)$cell_info['count'] + 1, 'fortune_id' => (int)$fortune_id];

        return $this->functions->echoJson(['data' => $_Api]);
    }

    private function randomItems($drops)
    {
        $item = $drops->drops;


        foreach ($item->drop_item as $key => $value) {
            $type = (string)$value->type;
            $id = (int)$value->id;
            $count = (int)$value->count;

            if (!$id) $drops_array[$type] = ['count' => $count];
            if ($id) $drops_array[$type] = [['id' => $id, 'count' => $count]];

        }

    }


    private function updateNewInfo($drops)
    {
        $this->db->query("UPDATE `mad_users` SET `cell_id` = null WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `id` = '{$this->params['json']['uid']}'");

    }

    private function parseLib()
    {
        $library = simplexml_load_string($this->load->library('1469566932_lib.xml'));

        return $library;
    }

    private function parseCubeItem($cell)
    {

        $cell[0] = (int)$cell[0];
        $cell[1] = (int)$cell[1];
        $cell[2] = (int)$cell[2];
        $fortune = [];

        $library = $this->lib->fortune;

        foreach ($library->item as $key => $value) {
            //Все три кобика одинаковые:)
            if ($value->cell1 == $cell[0] AND $value->cell2 == $cell[1] AND $value->cell3 == $cell[2]) {
                $fortune[$key] = $value;
            };
            //Первый и второй одинаковые:)
            if ($value->cell1 == $cell[0] AND $value->cell2 == $cell[1] AND $value->cell3 == 0) {
                $fortune[$key] = $value;
            };
            //Второй и третий одинаковые:)
            if ($value->cell1 == $cell[1] AND $value->cell2 == $cell[2] AND $value->cell3 == 0) {
                $fortune[$key] = $value;
            };
            //Первый и третий одинаковые:)
            if ($value->cell1 == $cell[0] AND $value->cell2 == $cell[2] AND $value->cell3 == 0) {
                $fortune[$key] = $value;
            };
        }

        return ($fortune) ? $fortune['item'] : $library->item[0];
    }

    private function parseCube($cell)
    {
        $fortune = [];
        $library = $this->lib->fortune;
        foreach ($library->item as $key => $value) {
            //Все три кобика одинаковые:)
            if ($value->cell1 == $cell[1] AND $value->cell2 == $cell[2] AND $value->cell3 == $cell[3]) {
                $fortune[$key] = $value->id;
            };
            //Первый и второй одинаковые:)
            if ($value->cell1 == $cell[1] AND $value->cell2 == $cell[2] AND $value->cell3 == 0) {
                $fortune[$key] = $value->id;
            };
            //Второй и третий одинаковые:)
            if ($value->cell1 == $cell[2] AND $value->cell2 == $cell[3] AND $value->cell3 == 0) {
                $fortune[$key] = $value->id;
            };
            //Первый и третий одинаковые:)
            if ($value->cell1 == $cell[1] AND $value->cell2 == $cell[3] AND $value->cell3 == 0) {
                $fortune[$key] = $value->id;
            };
        }
        return ($fortune) ? (int)$fortune['item'] : 1;
    }

    private function cellId()
    {
        $user = $this->db->super_query("SELECT cell_id, cell_xp, cell_lvl FROM `mad_users` WHERE `soc_id` = '{$this->params['json']['user_id']}' AND `id` = '{$this->params['json']['uid']}'");
        $cell = ($user['cell_id']) ? 1 : 0;

        return [$cell, $user];
    }

}
