<?php

class View extends Controller {

    function View() {
        parent::Controller();
    }

    function index($offset_posts = 0) {
        $this->load->library('pagination');
        $config['base_url'] = site_url();
        $config['total_rows'] = $this->db->count_all('posts');
        $config['per_page'] = 9;
        $config['num_links'] = 5;
        $config['uri_segment'] = 1;
        $this->pagination->initialize($config);

        $this->db->order_by('post_id', 'desc');
        $posts = $this->db->get('posts', 9, $offset_posts);
        $posts = $posts->result();

        $tags = $this->Get->tags();
        $tags = $tags->result();

        $data = array('page_title' => 'Последнее в блогах', 'posts' => $posts, 'paginator' => $this->pagination->create_links(), 'tags' => $tags);

        $this->parser->parse('view_main', $data);
    }

    function tag($page_tag, $offset_posts = 0) {
        //ищем id тэга
        $this->db->select('tag_id')
                ->from('tags')
                ->where('tag_name', $page_tag);
        $result = $this->db->get();
        $tag_id = $result->row()->tag_id;

        $posts = $this->Get->tag($tag_id, $offset_posts);
        $posts = $posts->result();

        $tag_posts = $this->Get->tag_posts($tag_id);
        $all_posts = $tag_posts->num_rows();

        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'tag/' . $this->uri->segment(2);
        $config['total_rows'] = $all_posts;
        $config['per_page'] = 9;
        $config['num_links'] = 5;
        $this->pagination->initialize($config);

        

        $tags = $this->Get->tags();
        $tags = $tags->result();

        $data = array('page_title' => $page_tag, 'posts' => $posts, 'paginator' => $this->pagination->create_links(), 'tags' => $tags);

        $this->parser->parse('view_main', $data);
    }

    function post($blog_from, $blog_id, $post_id) {
        $this->load->model('Counter');
        $this->Counter->blog_in($blog_from);
        $this->Counter->post_in($post_id);

        $tags = $this->Get->tags();
        $tags = $tags->result();

        $post = $this->Get->post($post_id);
        $post_title = $post->row()->post_title;
        $post = $post->result();

        $posts = $this->Get->links_out($blog_from, $blog_id, 5); // сколько постов ещё
        $posts = $posts->result();

        $data = array('single_post_title' => $post_title, 'post' => $post, 'posts' => $posts, 'tags' => $tags);

        $this->parser->parse('view_post', $data);
    }

    function widget($blog_id, $num_links, $direction = 1) {
        $links = $this->Get->links_in($blog_id, $num_links);
        $links = $links->result();
        $data = array('blog_id' => $blog_id, 'site_url' => site_url(), 'links' => $links);
        $this->parser->parse('blogs/' . $blog_id . '.php', $data);
    }

}

?>