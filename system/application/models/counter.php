<?php

class Counter extends Model {

    function Counter()
    {
        parent::Model();
    }
    function blog_out($blog_id) // переходы с системы на блог
    {	
		$this->db->where('blog_id =', $blog_id);
		$this->db->set('blog_out', 'blog_out + 1', FALSE);
		$this->db->set('blog_rating', 'blog_rating - 1', FALSE);
		$this->db->update('blogs');
    }
	function blog_in($blog_id) // переходы с блога в систему
    {	
		$this->db->where('blog_id =', $blog_id);
		$this->db->set('blog_in', 'blog_in + 1', FALSE);
		$this->db->set('blog_rating', 'blog_rating + 1', FALSE);
		$this->db->update('blogs');
    }
	function post_out($post_id) // переходы с системы на пост
    {	
		$this->db->where('post_id =', $post_id);
		$this->db->set('post_out', 'post_out + 1', FALSE);
		$this->db->set('post_rating', 'post_rating - 1', FALSE);
		$this->db->update('posts');
    }
	function post_in($post_id) // переходы с поста в систему
    {	
		$this->db->where('post_id =', $post_id);
		$this->db->set('post_in', 'post_in + 1', FALSE);
		$this->db->set('post_rating', 'post_rating + 1', FALSE);
		$this->db->update('posts');
    }	
}

?>