<?php

class Manage42 extends Controller {

    function Manage42() {
        parent::Controller();
    }

    function index() {
        $this->load->view('manage_list');
    }

    function edit() {
        $this->load->helper('form');
        $this->load->view('manage_edit');
    }

    function add() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation'); // проверяем данные
        $config = array(
            array(
                'field' => 'post_url',
                'label' => 'Url',
                'rules' => 'required'
            ),
            array(
                'field' => 'post_title',
                'label' => 'Title',
                'rules' => 'required|max_length[100]'
            ),
            array(
                'field' => 'post_text',
                'label' => 'Text',
                'rules' => 'required|max_length[330]'
            ),
            array(
                'field' => 'post_tags',
                'label' => 'Tags',
                'rules' => 'required'
            ),
            array(
                'field' => 'post_image',
                'label' => 'Image URL',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="error">(', ')</span>');
        if ($this->form_validation->run() == FALSE) { // показываем страницу добавления
            $tags = $this->Get->tags();
            $tags = $tags->result();
            $data = array('tags' => $tags);
            $this->parser->parse('manage_add', $data);

            //echo mb_strlen($this->input->post('post_title'));
            //echo '-'.mb_strlen($this->input->post('post_text'));
        } else { // тогда уже заносим пост
            // получаем данные
            $post_blog = $this->input->post('post_blog');
            $post_url = $this->input->post('post_url');
            $post_title = $this->input->post('post_title');
            $post_text = $this->input->post('post_text');
            $post_tags = $this->input->post('post_tags');
            $post_image = $this->input->post('post_image');

            /* Сначала разбираемся к картинкой */
            /* Узнаём ID этого поста */
            $this->db->select_max('post_id', 'last_post');
            $this->db->from('posts');
            $query = $this->db->get();
            $this_post = $query->row()->last_post + 1; // наш пост - это последний + 1

            /* Назначаем всякие переменные */
            $image_path = $_SERVER['DOCUMENT_ROOT'] . '/images/'; // путь к папке с картинками

            list($width, $height, $type, $attr) = getimagesize($post_image); // смотрим атрибуты загружаемой картинки
            if ($type == 1) {
                $ext = '.jpg';
            }
            if ($type == 2) {
                $ext = '.jpg';
            }
            if ($type == 3) {
                $ext = '.png';
            }
            if ($type == 6) {
                $ext = '.bmp';
            }

            $original_image = $image_path . '/original/' . $this_post . $ext; // путь к оригиналу
            $copped_file = $this_post . '-c' . $ext; // имя кропнутого файла
            $cropped_image = $image_path . '/original/' . $this_post . '-c' . $ext; // путь к крапнутому файлу
            $new_image = $image_path . '/' . $post_blog . '/' . $this_post . $ext; // путь к маленькой
            $new_image2 = $image_path . '/' . $post_blog . '/' . $this_post . '-2' . $ext; // к большой

            /* Получаем и сохраняем картинку */
            $this->load->library('curl');
            $remote_image = $this->curl->simple_get($post_image); // получаем картинку с удалённого сайта

            if (!$remote_image) {
                echo "<p>Unable to get remote file.\n"; // зырим не проеблась ли...
                exit;
            }

            $this->load->helper('file');
            write_file($original_image, $remote_image, 'w+'); // сохраняем полученную картинку

            $this->load->library('image_lib');

            // сначала кропаем
            if ($width > $height) {
                $config['image_library'] = 'gd2';
                $config['x_axis'] = round(($width - $height) / 2);
                $config['y_axis'] = 0;
                $config['maintain_ratio'] = FALSE;
                $config['width'] = $height;
                $config['height'] = $height;
                $config['source_image'] = $original_image;
                $config['new_image'] = $copped_file;
                $this->image_lib->initialize($config);
                $this->image_lib->crop();
                echo $this->image_lib->display_errors();
                $this->image_lib->clear();
            } else {
                $config['image_library'] = 'gd2';
                $config['y_axis'] = round(($height - $width) / 2);
                $config['x_axis'] = 0;
                $config['maintain_ratio'] = FALSE;
                $config['width'] = $width;
                $config['height'] = $width;
                $config['source_image'] = $original_image;
                $config['new_image'] = $copped_file;
                $this->image_lib->initialize($config);
                $this->image_lib->crop();
                echo $this->image_lib->display_errors();
                $this->image_lib->clear();
            }
            // теперь ресайзим
            $config['image_library'] = 'gd2';
            $config['quality'] = 81;
            $config['source_image'] = $cropped_image;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 100;
            $config['height'] = 100;
            $config['new_image'] = $new_image;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
            }
            $this->image_lib->clear();
            // и побольше
            $config['image_library'] = 'gd2';
            $config['quality'] = 81;
            $config['source_image'] = $cropped_image;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 200;
            $config['height'] = 200;
            $config['new_image'] = $new_image2;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
            }
            $this->image_lib->clear();

            // только если оба фйала создались
            if (file_exists($new_image) && file_exists($new_image2)) {

                // удаляем полученные картинки
                //delete_files($original_image);
                //delete_files($cropped_image);

                /* Чистим введённые данные */
                $this->load->model('Sanitize');
                $post_url = $this->Sanitize->text($post_url);
                $post_title = $this->Sanitize->text($post_title);
                $post_text = $this->Sanitize->text($post_text);
                $post_tags = $this->Sanitize->text($post_tags);

                $post_tags = str_replace('  ', '', $post_tags);
                $post_tags = str_replace(', ', ',', $post_tags);
                $post_tags = str_replace(' ,', ',', $post_tags);
                $post_tags = str_replace(' , ', ',', $post_tags);

                $tags_array = explode(',', $post_tags);

                function array_trim($a) {
                    $j = 0;
                    for ($i = 0; $i < count($a); $i++) {
                        if ($a[$i] != "") {
                            $b[$j++] = $a[$i];
                        }
                    } return $b;
                }

                //функция чистки
                $tags_array = array_trim($tags_array); // чистим тэги

                /* Заносим пост */
                $this->db->set('post_blog', $post_blog)
                        ->set('post_url', $post_url)
                        ->set('post_title', $post_title)
                        ->set('post_text', $post_text)
                        ->insert('posts');
                $post_id = mysql_insert_id();

                /* Разбираемся с тэгами */
                foreach ($tags_array as $row) {
                    $this->db->select('tag_id')
                            ->from('tags')
                            ->where('tag_name =', $row);
                    $result = $this->db->get();

                    if ($result->num_rows() == 0) {
                        $this->db->set('tag_name', $row)
                                ->insert('tags');

                        $tag_id = mysql_insert_id();

                        $this->db->set('tag_id', $tag_id)
                                ->set('post_id', $post_id)
                                ->insert('post2tag');
                    } else {
                        $tag_id = $result->row()->tag_id;

                        $this->db->set('tag_id', $tag_id)
                                ->set('post_id', $post_id)
                                ->insert('post2tag');
                    }
                }

                $this->load->view('manage_success');
            } else {
                echo 'Что-то не так...';
            }
        }

        //$this->db->set('post_text', "This function returns the query result as an array of objects, or an empty array on failure. Typically you'll use this in a foreach loop, like this:");
        //$this->db->update('posts');
    }

}

?>