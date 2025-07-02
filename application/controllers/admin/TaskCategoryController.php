<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TaskCategoryController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TaskCategoryModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'task-categories';
        $this->render_admin('admin/task_categories/index', $data);
    }

    public function add()
    {
        $data['isUpdate'] = false;
        $data['parents'] = $this->TaskCategoryModel->getAll();
        $this->render_admin('admin/task_categories/add', $data);
    }

    public function edit($id)
    {
        $data['isUpdate'] = true;
        $data['category'] = $this->TaskCategoryModel->getById($id);
        $data['parents'] = $this->TaskCategoryModel->getAllExcept($id);
        $this->render_admin('admin/task_categories/add', $data);
    }

    public function save()
    {
        $data = [
            'cat_name' => $this->input->post('cat_name'),
            'cat_descriptions' => $this->input->post('cat_descriptions'),
            'parent_id' => $this->input->post('parent_id') ?? 0,
            'cat_order' => $this->input->post('cat_order') ?? 0,
            'status' => $this->input->post('status') ?? 1
        ];

        $id = $this->input->post('id');
        if ($id) {
            $this->TaskCategoryModel->update($id, $data);
            $this->session->set_flashdata('success', 'Category updated successfully.');
        } else {
            $this->TaskCategoryModel->insert($data);
            $this->session->set_flashdata('success', 'Category added successfully.');
        }
        redirect('admin/task-categories');
    }

    public function delete($id)
    {
        $this->TaskCategoryModel->delete($id);
        echo json_encode(['success' => true]);
    }

    public function ajax_list()
    {
        $data = $this->TaskCategoryModel->getDataTables();
        echo json_encode($data);
    }
}
