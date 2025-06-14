<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CategoriesController extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CategoryModel');
        $this->load->library('form_validation');
        $this->load->library('upload');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'categories';
        $data['categories'] = $this->CategoryModel->get_all_categories();


        $this->render_admin('admin/categories/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'categories';

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/categories/add', $data);
        } else {
            $upload_path = './uploads/categories/';
            // Check if the folder exists, if not create it
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $featured_image = '';

            // Upload Featured Image
            if (!empty($_FILES['featured_image']['name'])) {
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . '_' . $_FILES['featured_image']['name'];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('featured_image')) {
                    $uploadData = $this->upload->data();
                    $featured_image = $uploadData['file_name'];
                }
            }

            $categoryData = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description'),
                'featured_image' => $featured_image
            );

            $this->CategoryModel->insert_category($categoryData);
            redirect('admin/categories');
        }
    }

    public function addAjax()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Category Name', 'required|is_unique[categories.name]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['success' => false, 'errors' => validation_errors()]);
        } else {
            $name = $this->input->post('name');
            $slug = create_slug($name);
            $data = [
                'name' => $name,
                'slug' => $slug,
            ];

            $cat_id = $this->CategoryModel->insert_category($data);

            echo json_encode([
                'success' => true,
                'category' => [
                    'id' => $cat_id,
                    'name' => $this->input->post('name')
                ]
            ]);
        }
    }


    public function edit($id)
    {
        $data['activePage'] = 'categories';

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['category'] = $this->CategoryModel->get_category($id);


            $this->render_admin('admin/categories/add', $data);
        } else {
            $upload_path = './uploads/categories/';
            // Check if the folder exists, if not create it
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            // Handle file upload for featured image
            $featured_image = '';

            if (!empty($_FILES['featured_image']['name'])) {
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . '_' . $_FILES['featured_image']['name'];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('featured_image')) {
                    $uploadData = $this->upload->data();
                    $featured_image = $uploadData['file_name'];
                }
            } else {
                // Keep the existing image if no new image is uploaded
                $featured_image = $this->input->post('existing_featured_image');
            }

            $categoryData = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description'),
                'featured_image' => $featured_image
            );

            $this->CategoryModel->update_category($id, $categoryData);
            redirect('admin/categories');
        }
    }

    public function delete($id)
    {
        $this->CategoryModel->delete_category($id);
        redirect('admin/categories');
    }
}
