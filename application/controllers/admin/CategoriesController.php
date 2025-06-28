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

    public function export_import()
    {
        $data['activePage'] = 'categories_export_import';
        $this->render_admin('admin/categories/export_import', $data);
    }

    public function export_csv()
    {
        $this->load->helper('download');
        $this->load->dbutil();

        $categories = $this->CategoryModel->get_all_categories();

        $delimiter = ",";
        $newline = "\r\n";
        $filename = "categories_export_" . date('Ymd_His') . ".csv";

        $csv_data = "name,slug,description\n";
        foreach ($categories as $row) {
            $csv_data .= '"' . $row['name'] . '","' . $row['slug'] . '","' . $row['description'] . '"' . $newline;
        }

        force_download($filename, $csv_data);
    }

    public function import_csv()
    {
        $this->load->library('upload');

        $config['upload_path'] = './uploads/csv/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 2048;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('csv_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/categories-export-import');
        } else {
            $file_data = $this->upload->data();
            $file_path = $file_data['full_path'];

            $csv_array = array_map('str_getcsv', file($file_path));
            $header = array_map('trim', $csv_array[0]);

            if ($header !== ['name', 'slug', 'description']) {
                $this->session->set_flashdata('error', 'Invalid CSV format. Required headers: name, slug, description');
                redirect('admin/categories-export-import');
            }

            unset($csv_array[0]); // Remove header

            $insert_data = [];
            $skipped = 0;
            $inserted = 0;

            foreach ($csv_array as $row) {
                if (count($row) < 2) continue;

                $name = trim($row[0]);
                $slug = trim($row[1]);
                $description = isset($row[2]) && trim($row[2]) != '' ? trim($row[2]) : $name;

                // Check for duplicate name or slug
                $this->db->group_start()
                    ->where('name', $name)
                    ->or_where('slug', $slug)
                    ->group_end();
                $existing = $this->db->get('categories')->row();

                if ($existing) {
                    $skipped++;
                    continue;
                }

                $insert_data[] = [
                    'name'        => $name,
                    'slug'        => $slug,
                    'description' => $description,
                    'created_at'  => date('Y-m-d H:i:s')
                ];
                $inserted++;
            }

            if (!empty($insert_data)) {
                $this->CategoryModel->bulk_insert($insert_data);
            }

            $this->session->set_flashdata('message', "Import completed. Inserted: <strong>$inserted</strong>, Skipped (duplicates): <strong>$skipped</strong>");
            redirect('admin/categories-export-import');
        }
    }
}
