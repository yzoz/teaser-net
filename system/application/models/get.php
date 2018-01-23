<?php

class Get extends Model {

    function Get() {
        parent::Model();
    }

    function tags() {
        $this->db->select('tags.tag_name, COUNT(post2tag.tag_id) AS tag_count')
                ->from('post2tag')
                ->join('tags', 'post2tag.tag_id = tags.tag_id', 'left')
                ->order_by('tags.tag_name', 'asc')
                ->group_by('tags.tag_id');
        return $this->db->get();
    }

    function tag($tag_id, $offset_posts) {
        $this->db->select('posts.post_id, posts.post_url, posts.post_title, posts.post_blog')
                ->from('posts')
                ->join('post2tag', 'posts.post_id = post2tag.post_id', 'left')
                ->where('post2tag.tag_id', $tag_id)
                ->order_by('posts.post_id', 'desc')
                ->limit(9, $offset_posts);
        return $this->db->get();
    }

    function tag_posts($tag_id) {
        $this->db->from('posts')
                ->join('post2tag', 'posts.post_id = post2tag.post_id', 'left')
                ->where('post2tag.tag_id', $tag_id)
                ->order_by('posts.post_id', 'asc');
        return $this->db->get();
    }

    function post($post_id) {
        $result = $this->db->get_where('posts', array('post_id' => $post_id));
        return $result;
    }

    function links_in($blog_id, $num_links) { // ссылки по которым приходят
        // отбираем блоги, у которых выше рейтинг (пришло в систему минус ушло из ситемы
        $this->db->select('blog_id');
        $this->db->from('blogs');
        $this->db->where('blog_id !=', $blog_id); // это не наш блог
        $this->db->order_by('blog_rating', 'desc'); //сортируем по убыванию рейтинга
        $this->db->limit($num_links); //сколько ссылок нужно
        $blogs_ids = $this->db->get();

        $query = array();
        foreach ($blogs_ids->result_array() as $row) {
            $query[] = '(
				SELECT post_id, post_title, post_text, post_blog
				FROM posts
				WHERE post_id=(
					SELECT MAX(post_id)
					FROM posts
					WHERE post_blog = ' . $row['blog_id'] . '
					AND post_rating=(
						SELECT MIN(post_rating)
						FROM posts
						WHERE post_blog = ' . $row['blog_id'] . '
					)
				)
			)'; //выбираем пост с самыми большими post_rating и самым большим id из них, из тех которые относятся к данному блогу
        }
        $query = implode(' UNION ', $query);
        //$this->db->cache_on();
        $result = $this->db->query($query);
        //$this->db->cache_off();
        return $result;
    }

    function links_out($blog_from, $blog_id, $num_links) { // ссылки по которым уходят
        // отбираем блоги, у которых выше рейтинг (пришло в систему минус ушло из ситемы
        $this->db->select('blog_id');
        $this->db->from('blogs');
        $this->db->where('blog_id !=', $blog_id); // это не наш блог
        $this->db->where('blog_id !=', $blog_from); // и это не блог с которого только что пришли
        $this->db->order_by('blog_rating', 'desc'); //сортируем по убыванию рейтинга
        $this->db->limit($num_links); //сколько ссылок нужно
        $blogs_ids = $this->db->get();

        $query = array();
        foreach ($blogs_ids->result_array() as $row) {
            $query[] = '(
				SELECT post_id, post_title, post_text, post_blog, post_out, post_rating
				FROM posts
				WHERE post_id=(
					SELECT MAX(post_id)
					FROM posts
					WHERE post_blog = ' . $row['blog_id'] . '
				)
			)'; //выбираем пост с самым большим id, из тех которые относятся к данному блогу
        }
        $query = implode(' UNION ', $query);
        //$this->db->cache_on();
        $result = $this->db->query($query);
        //$this->db->cache_off();
        return $result;
    }

    function post_url($post_id) {
        $this->db->select('post_url');
        $this->db->from('posts');
        $this->db->where('post_id =', $post_id);
        $query = $this->db->get();
        $result = $query->row()->post_url;
        return $result;
    }

}

?>