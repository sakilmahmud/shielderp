<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PostsController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PostModel');
        $this->load->library('form_validation');
        $this->load->library('upload');
    }

    public function index()
    {
        $data['activePage'] = 'posts';
        $data['posts'] = $this->PostModel->getAllPosts();

        $this->render_admin('admin/posts/list', $data);
    }

    public function add()
    {
        $data['activePage'] = 'posts';

        if ($this->input->post()) {
            $this->form_validation->set_rules('post_title', 'Post Title', 'required');
            $this->form_validation->set_rules('post_content', 'Post Content', 'required');

            $upload_path = 'assets/uploads/posts/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            // File upload configuration
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|png|gif|pdf|mp3|mp4';  // Allowed file types
            $config['max_size'] = 10240;  // Maximum file size (10MB)

            $this->upload->initialize($config);

            if ($this->form_validation->run() === TRUE) {
                $post_data = [
                    'post_title' => $this->input->post('post_title'),
                    'post_content' => $this->input->post('post_content'),
                    'created_by' => $this->session->userdata('user_id')
                ];

                // Check if a file is uploaded
                if ($this->upload->do_upload('attached_file')) {
                    $upload_data = $this->upload->data();
                    $post_data['post_media_url'] = $upload_path . $upload_data['file_name'];  // Store file path in the database
                    $post_data['media_type'] = $upload_data['file_type'];  // Store file type in the database
                }

                if ($this->PostModel->insertPost($post_data)) {
                    $this->session->set_flashdata('message', 'Post added successfully.');
                } else {
                    $this->session->set_flashdata('error_message', 'Failed to add post.');
                }
                redirect('admin/posts');
            }
        }


        $this->render_admin('admin/posts/add', $data);
    }


    public function edit($id)
    {
        $data['activePage'] = 'posts';
        $data['post'] = $this->PostModel->getPostById($id);

        if ($this->input->post()) {
            $this->form_validation->set_rules('post_title', 'Post Title', 'required');
            $this->form_validation->set_rules('post_content', 'Post Content', 'required');

            if ($this->form_validation->run() === TRUE) {
                $post_data = [
                    'post_title' => $this->input->post('post_title'),
                    'post_content' => $this->input->post('post_content'),
                    'post_media_url' => $this->input->post('post_media_url'),
                    'media_type' => $this->input->post('media_type'),
                    'status' => $this->input->post('status') ? 1 : 0,
                ];

                if ($this->PostModel->updatePost($id, $post_data)) {
                    $this->session->set_flashdata('message', 'Post updated successfully.');
                } else {
                    $this->session->set_flashdata('error_message', 'Failed to update post.');
                }
                redirect('admin/posts');
            }
        }


        $this->render_admin('admin/posts/edit', $data);
    }

    public function delete($id)
    {
        if ($this->PostModel->deletePost($id)) {
            $this->session->set_flashdata('message', 'Post deleted successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Failed to delete post.');
        }
        redirect('admin/posts');
    }
}
