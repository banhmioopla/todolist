<?php

use AppToDo\Core\Loader;

class Work extends Loader{
    public function index(){
        $this->load_view("components/header");

        $this->load_view("work/index",[
            'title' => "HELLO"
        ]);
        $this->load_view("components/footer");
    }

    public function insert(){
        $WorkModel = $this->load_model("WorkModel");
        $post_data = $_POST;
        $result_id = $WorkModel->insert([
            'name' => "{$post_data['title']}",
            'starting_date' => strtotime($post_data['start']),
            'ending_date' => strtotime($post_data['end']),
            'status' => "{$post_data['status']}",
        ]);

        echo json_encode([
            'status' => true,
            'new_id' => $result_id
        ]); die;
    }

    public function update(){
        $WorkModel = $this->load_model("WorkModel");
        $post_data = $_POST;
        $WorkModel->updateById($_POST['id'], [
            'name' => "{$post_data['title']}",
            'starting_date' => strtotime($post_data['start']),
            'ending_date' => strtotime($post_data['end']),
            'status' => $post_data['status'],
        ]);
        echo json_encode([
            'status' => true,
        ]); die;
    }

    public function delete(){
        $WorkModel = $this->load_model("WorkModel");
        $WorkModel->deleteById($_POST['id']);
        echo json_encode([
            'status' => true,
        ]); die;
    }

    public function getList(){
        $WorkModel = $this->load_model("WorkModel");
        $list = $WorkModel->select();
        $data = [];
        $color_list = $WorkModel->getListColorStatus();

        foreach ($list as $item){
            $data[] = [
                'id' => $item['id'],
                'title' => $item['name'],
                'end' => date('Y-m-d', (int) $item['ending_date']),
                'start' => date('Y-m-d', (int) $item['starting_date']),
                'color' => $color_list[$item['status']],
                'status' => $item['status'],
            ];
        }
        echo json_encode([
            'status' => true,
            'data' => $data,
            'html_option_status' => $this->getListStatus()['html_option']
        ]); die;
    }

    private function getListStatus(){
        $WorkModel = $this->load_model("WorkModel");
        $list_status = $WorkModel->getListStatus();
        $html_option = '';
        $color_list = $WorkModel->getListColorStatus();
        foreach ($list_status as $item){
            $html_option .= '<option data-color="'.$color_list[$item].'" value="'.$item.'">'.$item.'</option>';
        }

        return [
            'data' => $list_status,
            'html_option' =>$html_option,
        ];
    }

}