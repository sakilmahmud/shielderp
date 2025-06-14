<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ContactsController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ContactsModel');
        $this->load->model('ContactsGroupModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'contacts';
        $data['contacts'] = $this->ContactsModel->getAll();


        $this->render_admin('admin/contacts/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'contacts';
        $data['groups'] = $this->ContactsGroupModel->getAll();

        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $this->form_validation->set_rules('contact', 'Contact', 'required');
        $this->form_validation->set_rules('contacts_group_id', 'Group', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/contacts/add', $data);
        } else {
            $contactData = [
                'contacts_group_id' => $this->input->post('contacts_group_id'),
                'full_name' => $this->input->post('full_name'),
                'contact' => $this->input->post('contact'),
                'dob' => $this->input->post('dob'),
                'address' => $this->input->post('address'),
                'status' => $this->input->post('status') ? 1 : 0,
            ];

            $this->ContactsModel->insert($contactData);

            $this->session->set_flashdata('message', 'Contact added successfully');
            redirect('admin/contacts');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'contacts';
        $data['groups'] = $this->ContactsGroupModel->getAll();

        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $this->form_validation->set_rules('contact', 'Contact', 'required');
        $this->form_validation->set_rules('contacts_group_id', 'Group', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['contact'] = $this->ContactsModel->getById($id);


            $this->render_admin('admin/contacts/add', $data);
        } else {
            $contactData = [
                'contacts_group_id' => $this->input->post('contacts_group_id'),
                'full_name' => $this->input->post('full_name'),
                'contact' => $this->input->post('contact'),
                'dob' => $this->input->post('dob'),
                'address' => $this->input->post('address'),
                'status' => $this->input->post('status') ? 1 : 0,
            ];

            $this->ContactsModel->update($id, $contactData);

            $this->session->set_flashdata('message', 'Contact updated successfully');
            redirect('admin/contacts');
        }
    }

    public function delete($id)
    {
        $this->ContactsModel->delete($id);
        $this->session->set_flashdata('message', 'Contact deleted successfully');
        redirect('admin/contacts');
    }

    public function bulkAdd()
    {
        $data['activePage'] = 'contacts';
        $data['error'] = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('contacts_file')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $fileData = $this->upload->data();
                $filePath = $fileData['full_path'];

                // Read and process the CSV file
                $this->load->library('csvreader');
                $csvData = $this->csvreader->parse_file($filePath);

                foreach ($csvData as $row) {
                    $insertData = [
                        'contacts_group_id' => $row['GroupID'],
                        'full_name' => $row['FullName'],
                        'contact' => $row['Contact'],
                        'dob' => $row['DOB'], // Ensure date format matches DB
                        'address' => $row['Address']
                    ];
                    $this->ContactsModel->insert($insertData);
                }

                $this->session->set_flashdata('message', 'Contacts uploaded successfully');
                redirect('admin/contacts');
            }
        }


        $this->render_admin('admin/contacts/bulk_add', $data);
    }

    public function getContacts()
    {
        // Get DataTable parameters
        $draw = intval($this->input->get('draw'));
        $start = intval($this->input->get('start'));
        $length = intval($this->input->get('length'));
        $searchValue = $this->input->get('search')['value']; // Search value

        // Fetch filtered and paginated data
        $contacts = $this->ContactsModel->getFilteredContacts($start, $length, $searchValue);

        // Get total records count
        $totalRecords = $this->ContactsModel->getTotalContactsCount();

        // Get total filtered records count
        $totalFilteredRecords = $this->ContactsModel->getFilteredContactsCount($searchValue);

        // Prepare data to send back to DataTable
        $data = [];
        foreach ($contacts as $contact) {
            $data[] = [
                $contact['id'],
                $contact['group_title'],
                $contact['full_name'],
                $contact['contact'],
                $contact['status'] ? 'Active' : 'Inactive',
                '<a href="' . base_url('admin/contacts/edit/' . $contact['id']) . '" class="btn btn-primary">Edit</a>
             <a href="' . base_url('admin/contacts/delete/' . $contact['id']) . '" class="btn btn-danger">Delete</a>'
            ];
        }

        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data' => $data
        ]);
    }
}
