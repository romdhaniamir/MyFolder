<?php

class Lists_Contacts extends CI_Controller {

    public $list_id = "";
    public $phone_to_update = "";
    public $session_local = array();
    public $total_count_contact;
    public $total_count_lists;
    public $session_admin = false;

    /**
     * construct
     */
    public function __construct() {
        parent::__construct();
        $this->session_local = $this->session->userdata('session_login_in');
        if ($this->session->userdata('session_admin')['logged_in']) {
            $this->session_admin = TRUE;
        }
    }

    /* ========================================================================== Index page          
      /**
     * Index page for web controller.
     */

    public function index() {

        $this->load->helper('url');
        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            $this->load->view('bootstrap/header');
            $this->load->library('form_validation');
            $this->load->library('table');

            if (isset($_POST['name_to_edit'])) {
                $form_valid = $this->update_list();
            } else if (isset($_POST['name_list_add'])) {
                $this->add_list_contact();
            }
            $lists_names = $this->get_lists_index($this->session_local['user_id']);
            $this->load->view('list_views/lists_contacts_view', array("names_view" => $lists_names, "count" => $this->total_count_contact, "count_list" => $this->total_count_lists, "session_admin" => $this->session_admin));
            $ajax_url = array(
                'ajax_url' => TRUE,
                'object_services_urls' => json_encode(array('ajax_lists' => base_url() . "index.php/lists_contacts/ajax_lists")),
                'ajax_sript' => array('ajax_lists.js', 'contacts.js')
            );
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }

    /**
     * 
     * @param type $lists
     * @param type $list_id
     * @return boolean
     */
    private function add_list_contact() {

        $this->load->model('List_contact');
        $this->form_validation->set_rules(array(
            array(
                'field' => 'name_list_add',
                'label' => 'nom de liste',
                'rules' => 'required|callback_key_exists',
            )
        ));
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            return False;
        } else {
            $list_contact = new List_contact();
            $list_contact->name = $this->input->post('name_list_add');
            $list_contact->user_id = $this->session_local['user_id'];
            $list_contact->insert();
            return true;
        }
    }

    /**
     * get lists  of contacts
     * @param int id
     * @return lists array
     */
    private function get_lists($id_user, $list_id = "") {

        $this->load->model('List_contact');
        $lists_table = array();
        $where_array = array(array(
                'where_tag' => "user_id",
                'where_value' => $id_user
        ));
        $c_view = $this->List_contact->get(0, 0, $where_array);

        foreach ($c_view as $list) {
            ($list_id == $list->list_id ) ? $class = "active-list" : $class = "";

            $lists_table[] = array(' <a href="' . base_url() . 'index.php/lists_contacts/contacts/' . $list->list_id . '" class="lab-liste ' . $class . '" id="lab' . $list->list_id . '"><i class="fa fa-eye ' . $class . '"></i> ' . $list->name . '</a><form  method="post"  id="liste' . $list->list_id . '" class="form-inline edit-list animated pulse"> <input type="text" class="form-control nom" name="name_to_edit" value="' . $list->name . '" placeholder="Nom de La liste">
                <button class="update_btn"><i class="fa fa-check"></i></button><i class="fa fa-trash-o close_update"></i><input type="hidden" name="list_id" value="' . $list->list_id . '"></form>',
                '<div id="' . $list->list_id . '"  class="hide-delete "><i class="fa fa-trash-o"></i></div><div class="action-delete animated fadeInDown delete-' . $list->list_id . ' " ><span class=""> Tous les contacts dans la liste ( ' . $list->name . ' )  seront supprimées définitivement !</span><br>' . anchor('lists_contacts/delete_list/' . $list->list_id, 'Oui') . '<label class="close-delete">Non<label></div>', '<div class="form-hide"  id="' . $list->list_id . '"><i class="fa fa-pencil-square-o"></i></div>',
                '<a class="move-all-list" id="' . $list->list_id . '"><i class="fa fa-random"></i> </a>'
            );
        }
        return $lists_table;
    }

    /**
     * get lists  of contacts
     * @param int id
     * @return lists array
     */
    private function get_lists_index($id_user, $list_id = "") {

        $this->load->model('List_contact');
        $lists_table = array();
        $where_array = array(array(
                'where_tag' => "user_id",
                'where_value' => $id_user
        ));
        $c_view = $this->List_contact->get(0, 0, $where_array);
        $this->total_count_contact = 0;
        if ($this->session_admin) {
            foreach ($c_view as $list) {
                $this->total_count_lists+=1;
                $count_number = $this->get_number_rows('Contact', "list_id", $list->list_id);
                $this->total_count_contact += $count_number;
                ($list_id == $list->list_id ) ? $class = "active-list" : $class = "";
                $lists_table[] = array(' <a href="' . base_url() . 'index.php/lists_contacts/contacts/' . $list->list_id . '" class="lab-liste ' . $class . '" id="lab' . $list->list_id . '"><i class="fa fa-eye ' . $class . '"></i> ' . $list->name . '</a><form  method="post"  id="liste' . $list->list_id . '" class="form-inline edit-list animated pulse"> <input type="text" class="form-control nom" name="name_to_edit" value="' . $list->name . '" placeholder="Nom de La liste">
                <button class="update_btn"><i class="fa fa-check"></i></button><i class="fa fa-times close_update"></i><input type="hidden" name="list_id" value="' . $list->list_id . '"></form>',
                    $count_number . '<span> Contacts</span>',
                    '<a data-id="' . $list->list_id . '" class="export-list"> <i class="fa fa-download"></i>  Exporter en Excel(xls)</a>',
                    '<div id="' . $list->list_id . '"  class="hide-delete "><i class="fa fa-trash-o"></i></div><div class="action-delete animated fadeInDown delete-' . $list->list_id . ' " ><span class=""> Tous les contacts dans la liste ( ' . $list->name . ' )  seront supprimées définitivement !</span><br>' . anchor('lists_contacts/delete_list/' . $list->list_id, 'Oui') . '<label class="close-delete">Non<label></div>',
                    '<div class="form-hide"  id="' . $list->list_id . '"><i class="fa fa-pencil-square-o"></i></div>',
                    '<a class="move-all-list" id="' . $list->list_id . '"><i class="fa fa-random"></i> </a>'
                );
            }
        } else {
            foreach ($c_view as $list) {
                $this->total_count_lists+=1;
                $count_number = $this->get_number_rows('Contact', "list_id", $list->list_id);
                $this->total_count_contact += $count_number;
                ($list_id == $list->list_id ) ? $class = "active-list" : $class = "";
                $lists_table[] = array(' <a href="' . base_url() . 'index.php/lists_contacts/contacts/' . $list->list_id . '" class="lab-liste ' . $class . '" id="lab' . $list->list_id . '"><i class="fa fa-eye ' . $class . '"> </i> &nbsp; ' . $list->name . '</a><form  method="post"  id="liste' . $list->list_id . '" class="form-inline edit-list animated pulse"> <input type="text" class="form-control nom" name="name_to_edit" value="' . $list->name . '" placeholder="Nom de La liste">
                <button class="update_btn"><i class="fa fa-check"></i></button><i class="fa fa-times close_update"></i><input type="hidden" name="list_id" value="' . $list->list_id . '"></form>',
                    $count_number . '<span> Contacts</span>',
                    '<a href="lists_contacts/contact_insert/' . $list->list_id . '" class="export-list"> <i class="fa fa-user-plus"> </i> &nbsp; Ajouter des contacts</a>',
                    '<div id="' . $list->list_id . '"  class="hide-delete "><i class="fa fa-trash-o"></i></div><div class="action-delete animated fadeInDown delete-' . $list->list_id . ' " ><span class=""> Tous les contacts dans la liste ( ' . $list->name . ' )  seront supprimées définitivement !</span><br>' . anchor('lists_contacts/delete_list/' . $list->list_id, 'Oui') . '<label class="close-delete">Non<label></div>',
                    '<div class="form-hide"  id="' . $list->list_id . '"><i class="fa fa-pencil-square-o"></i></div>',
                    '<a class="move-all-list" id="' . $list->list_id . '"><i class="fa fa-random"></i> </a>'
                );
            }
        }
        return $lists_table;
    }

    /**
     * update list
     * @param string $lists_table lists contacts
     * @param string $lists_id to return same view
     */
    private function update_list() {

        $this->load->model('List_contact');

        $user_list = new List_contact();
        $user_list->load($this->input->post('list_id'));
        if ($user_list->user_id == $this->session_local['user_id']) {
            $this->form_validation->set_rules(array(
                array(
                    'field' => 'name_to_edit',
                    'label' => 'nom de liste',
                    'rules' => 'required|callback_key_exists',
                )
            ));
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            if (!$this->form_validation->run()) {
                return False;
            } else {
                $list_contact = new List_contact();
                $id = $this->input->post('list_id');
                $list_contact->load($id);
                $list_contact->name = $this->input->post('name_to_edit');
                $list_contact->update();
                return true;
            }
        }
    }

    /**
     * delete list
     * @param int $list_id
     */
    public function delete_list($list_id) {
        $this->load->helper('url');
        $this->load->model('List_contact');
        $user_list = new List_contact();
        $user_list->load($list_id);
        if ($user_list->user_id == $this->session_local['user_id']) {

            $this->load->model('Contact');


            $where_array = array(array(
                    'where_tag' => "list_id",
                    'where_value' => $list_id
            ));
            $contacts = $this->Contact->get(0, 0, $where_array);

            foreach ($contacts as $contact) {
                $contact->delete();
            }

            $list_contact = new List_contact();
            $list_contact->load($list_id);
            $list_contact->delete();
        }
        redirect('/lists_contacts');
    }

    /* ========================================================================== Contacts
      /**
     * view one  list contacts
     * @param type $list_id
     */

    public function contacts($list_id) {


        $this->load->helper('url');


        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            $this->load->helper('url');
            $this->load->model(array('Contact', 'List_contact'));
            $this->load->library('table');
            $this->load->library('form_validation');
            $this->load->view('bootstrap/header');
            $list = new List_contact();
            $list->load($list_id);
            $table_list = $this->get_lists($this->session_local['user_id'], $list->list_id);

            $table_contacts = $this->get_table_with_limit('Contact', "list_id", $list_id, 10, 0);

            $url = base_url();

            if (isset($_POST['name_to_edit'])) {
                $this->update_list();
            } else if (isset($_POST['name_list_add'])) {
                $valide = $this->add_list_contact();
            }
            $table_list = $this->get_lists($this->session_local['user_id'], $list->list_id);
            $this->load->view('contact_views/contacts_view', array("name_list" => $list->name, "contacts_table" => $table_contacts, "count" => 1, "list_id" => $list_id, "names_view" => $table_list, "url" => $url));

            $ajax_url = array(
                'ajax_url' => TRUE,
                'object_services_urls' => json_encode(array('ajax_lists' => base_url() . "index.php/lists_contacts/ajax_lists", 'ajax_contact' => base_url() . "index.php/lists_contacts/view_ajax")),
                'ajax_sript' => array('ajax_lists.js', 'ajax_contact.js', 'contacts.js')
            );
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }

    /**
     * insert contact .
     * @param int $list_id
     */
    public function contact_insert($list_id) {


        $this->load->helper('url');


        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            $this->load->model('List_contact');
            $this->load->model('Contact');
            $this->load->library('form_validation');
            $this->load->library('table');
            $this->load->view('bootstrap/header');
            //company for path
            $this->load->model('User');
            $user = new User();
            $user->load($this->session_local['user_id']);
            $company = $user->company;
            $config = array(
                'upload_path' => "upload_files/{$company}",
                'allowed_types' => 'xlsx|xml|xls|ods',
                'max_size' => 50000,
            );



            $this->load->library('upload', $config);
            $list = new List_contact();
            $list->load($list_id);
            $this->list_id = $list_id;
            if ($list->user_id == $this->session_local['user_id']) {


                if (isset($_POST["phone_number"])) {
                    $this->manual_contact_insert($list, $list_id);
                } else if (isset($_FILES["file_excel"])) {
                    $this->excel_contact_insert($list, $list_id, $company);
                } else {
                    $this->load->view("contact_views/insert_contact", array("name_list" => $list->name));
                }
            } else {
                $this->load->view("error_400.php");
            }
            $ajax_url = array('ajax_url' => False);
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }

    /**
     * 
     * @param array $list
     * @param int $list_id
     * @param string $company
     */
    public function excel_contact_insert($list, $list_id, $company) {


        $check_file_upload = FALSE;

        if (!is_dir('upload_files/' . $company)) {
            mkdir('upload_files/' . $company, 0777, TRUE);
        }

        if (isset($_FILES['file_excel']['error']) && ($_FILES['file_excel']['error'] !== '4')) {
            $check_file_upload = TRUE;
        }

        if (!$this->upload->do_upload('file_excel') && $check_file_upload) {

            $this->load->view("contact_views/insert_contact", array("name_list" => $list->name));
        } else {
            $upload_data = $this->upload->data();
            $this->load->library('PHPExcel');
            $objPHPExcel = PHPExcel_IOFactory::load('upload_files/' . $company . '/' . $upload_data['file_name']);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $i = 1;
            foreach ($sheetData as $line) {
                if ($i !== 1) {
                    $result = $this->Contact->var_exists_for_user_join($this->List_contact, "user_id", $this->session_local['user_id'], "phone_number", $line['A'], "name");
                    $pattern = array(2,4,5,9);
                    if (!$result && !((trim($line['A']) == FALSE)) && is_numeric($line['A']) && in_array(($line["A"]/10000000)%10, $pattern, true) ) {
                        $contact = new Contact();
                        $contact->phone_number = $line['A'];
                        $contact->mail = isset($line['B']) ? $line['B'] : '';
                        $contact->f_name = isset($line['C']) ? $line['C'] : '';
                        $contact->l_name = isset($line['D']) ? $line['D'] : '';
                        $contact->country = isset($line['E']) ? $line['E'] : '';
                        $contact->address =isset($line['F']) ? $line['F'] : '';
                        $contact->sex = isset($line['G']) ? $line['G'] : '';

                        $contact->date_created = date("Y-m-d H:i:s");
                        $contact->date_modified = date("Y-m-d H:i:s");
                        $contact->list_id = $list_id;
                        $contact->trash = FALSE;
                        $contact->show = TRUE;
                        $contact->insert();
                    }
                }
                $i = 2;
            }


            $this->load->view("alert_view", array("message" => "Des contacts ont été enrgistrés depuis un fichier excel.", 'alert' => 'alert-success'));
            $this->load->view("contact_views/insert_contact", array("name_list" => $list->name));
        }
    }

    /**
     * @param type $contact
     * @return boolean/object
     */
    private function contact_update_form($contact_id) {
        $contact = new Contact();
        $contact->load($contact_id);
        $this->phone_to_update = $contact->phone_number;
        $this->form_validation->set_rules(array(
            array(
                'field' => 'phone_number',
                'label' => 'Numéro téléphone',
                'rules' => 'required|callback_key_exists_contact_update',
            ),
            array(
                'field' => 'mail',
                'label' => 'adresse email',
                'rules' => 'valid_email',
            )
        ));
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            return false;
        } else {
            $contact->phone_number = $this->input->post('phone_number');
            $contact->mail = $this->input->post('mail');
            $contact->f_name = $this->input->post('f_name');
            $contact->l_name = $this->input->post('l_name');
            $contact->address = $this->input->post('address');
            $contact->country = $this->input->post('country');
            $contact->sex = $this->input->post('sex');
            $contact->date_modified = date("Y-m-d H:i:s");
            $contact->update();
            $contact->show = TRUE;
            $this->load->view("alert_view", array("message" => "Un contact a été mise à jour ", 'alert' => 'alert-success'));
            return true;
        }
    }

    /**
     * @param type $list
     * @param type $list_id
     */
    public function manual_contact_insert($list, $list_id) {
        $this->form_validation->set_rules(array(
            array(
                'field' => 'phone_number',
                'label' => 'Numéro téléphone',
                'rules' => 'required|callback_key_exists_contact',
            ), array(
                'field' => 'mail',
                'label' => 'Adresse mail',
                'rules' => 'valid_email',
            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {

            $this->load->view("contact_views/insert_contact", array("name_list" => $list->name));
        } else {
            $contact = new Contact();
            $contact->phone_number = $this->input->post('phone_number');
            $contact->mail = $this->input->post('mail');
            $contact->f_name = $this->input->post('f_name');
            $contact->l_name = $this->input->post('l_name');
            $contact->country = $this->input->post('country');
            $contact->address = $this->input->post('address');
            $contact->sex = $this->input->post('sex');
            $contact->date_created = date("Y-m-d H:i:s");
            $contact->date_modified = date("Y-m-d H:i:s");
            $contact->trash = False;
            $contact->show = TRUE;
            $contact->list_id = $list_id;
            $contact->insert();
            $this->load->view("alert_view", array("message" => "Contact a été enrgistré.", 'alert' => 'alert-success'));
            $this->load->view("contact_views/insert_contact", array("name_list" => $list->name));
        }
    }

    /**
     * update contact  , requires function edit_contact.
     * @param type $list_id
     */
    public function update_contact($contact_id) {
        if (is_numeric($contact_id) && $contact_id != '0' && $this->if_contact_of_user($contact_id)) {
            $this->load->helper('url');
            if (!$this->session_local['logged_in']) {
                redirect('#');
            } else {
                $this->load->helper('url');
                $this->load->model('Contact');
                $this->load->library('form_validation');
                $this->load->library('table');
                $this->load->view('bootstrap/header');
                if (isset($_POST["phone_number"])) {
                    $this->contact_update_form($contact_id);
                } else if ($this->input->post('name_to_edit')) {
                    $this->update_list();
                } else if ($this->input->post('name_list_add')) {
                    $this->add_list_contact();
                }
                $contact = new Contact();
                $contact->load($contact_id);
                $this->phone_to_update = $contact->phone_number;
                $table_list = $this->get_lists($this->session_local['user_id']);
                $this->load->view('contact_views/contact_edit_view', array("name_list" => 'Mettre à jour', "names_view" => $table_list, "contact" => $contact));
                $ajax_url = array(
                    'ajax_url' => TRUE,
                    'object_services_urls' => json_encode(array('ajax_lists' => base_url() . "index.php/lists_contacts/ajax_lists")),
                    'ajax_sript' => array('ajax_lists.js', 'contacts.js')
                );
                $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
            }
        } else {
            $this->load->view("error_400.php");
        }
    }

    /* ========================================================================== Trash */

    /**
     * trash
     */
    public function trash() {
        $this->load->helper('url');
        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            $this->load->helper('url');
            $this->load->model(array('Contact', 'List_contact', 'User'));
            $this->load->library('table');
            $this->load->library('form_validation');
            $this->load->view('bootstrap/header');
            if (isset($_POST['name_to_edit'])) {
                $this->update_list();
            } else if (isset($_POST['name_list_add'])) {
                $this->add_list_contact();
            }
            $table_list = $this->get_lists($this->session_local['user_id']);
            $table = $this->get_table_trash_with_limit(10, 0);
            $this->load->view('contact_views/trash_view', array("name_list" => 'Corbeille', "contacts_table" => $table, "count" => 1, "names_view" => $table_list));
            $ajax_url = array(
                'ajax_url' => TRUE,
                'object_services_urls' => json_encode(array('ajax_lists' => base_url() . "index.php/lists_contacts/ajax_lists", 'ajax_trash' => base_url() . "index.php/lists_contacts/ajax_trash")),
                'ajax_sript' => array('ajax_lists.js', 'ajax_trash.js', 'contacts.js')
            );
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }

    /* ========================================================================== Functions
      /**
     * @param sting $model_name
     * @param string $key_id
     * @param int $list_id
     * @return int
     */

    private function get_number_rows($model_name, $key_id, $list_id) {

        $this->load->model($model_name);
        $where_array = array(array(
                'where_tag' => $key_id,
                'where_value' => $list_id
            ),
            array(
                'where_tag' => 'trash',
                'where_value' => FALSE
            )
        );
        $number_rows = $this->Contact->get_count($where_array);


        return $number_rows;
    }

    /**
     * check users contact
     * @return int
     */
    private function if_contact_of_user($contact_id) {
        $this->load->model(array('Contact', 'List_contact', 'User'));
        $join_array = array(
            array(
                'table' => $this->List_contact,
                'type' => ''
            ),
            array(
                'table' => $this->User,
                'type' => ''
            )
        );

        $where_array = array(
            array(
                'where_tag' => 'users.user_id',
                'where_value' => $this->session_local['user_id']
            ),
            array(
                'where_tag' => 'contacts.Trash',
                'where_value' => False
            ),
            array(
                'where_tag' => 'contacts.contact_id',
                'where_value' => $contact_id
            )
        );
        $int = $this->Contact->get_with_join('phone_number', $join_array, $where_array, 0, 0, TRUE);
        if ($int > 0)
            return TRUE;
        else
            return False;
    }

    /**
     * get count of trash
     * @return int
     */
    private function get_count_trash() {

        $join_array = array(
            array(
                'table' => $this->List_contact,
                'type' => ''
            ),
            array(
                'table' => $this->User,
                'type' => ''
            )
        );

        $where_array = array(
            array(
                'where_tag' => 'users.user_id',
                'where_value' => $this->session_local['user_id']
            ),
            array(
                'where_tag' => 'contacts.Trash',
                'where_value' => TRUE
            )
        );
        return $int = $this->Contact->get_with_join('phone_number', $join_array, $where_array, 0, 0, TRUE);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    private function get_table_trash_with_limit($limit, $offset) {
        $join_array = array(array(
                'table' => $this->List_contact,
                'type' => ''
            ),
            array(
                'table' => $this->User,
                'type' => ''
            )
        );

        $where_array = array(
            array(
                'where_tag' => 'users.user_id',
                'where_value' => $this->session_local['user_id']
            ),
            array(
                'where_tag' => 'contacts.Trash',
                'where_value' => TRUE
            )
        );

        $objects = $this->Contact->get_with_join('* , contacts_lists.name', $join_array, $where_array, $limit, $offset);
        $table[] = array('<input type="checkbox" id="all_edit" name="edit" value="">', 'Action', 'Téléphone', 'Liste', 'Prènom', 'Nom', 'Civilité', 'Pays', 'Adresse', 'date de modification');
        // print_r($objects);
        foreach ($objects as $object) {

            $table[] = array(
                '<input type="checkbox" class="item-to-edit" name="chk[]" value="' . $object->contact_id . '">',
                '<a class="delete-button" id="' . $object->contact_id . '"><i class="fa fa-trash-o"></i></a>| <a class="edit-button" id="' . $object->contact_id . '"><i class="fa fa-refresh"></i></i></a>',
                $object->phone_number,
                $object->name,
                $object->l_name,
                $object->f_name,
                $object->sex,
                $object->country,
                $object->address,
                $object->date_modified
            );
        }
        return $table;
    }

    /**
     * @param sting $model_name
     * @param string $key_id
     * @param int $list_id .
     * @param int $limit .
     * @param int $offset .
     * @param boolean $trash .
     * @return array .
     */
    private function get_table_with_limit($model_name, $key_id, $value_id, $limit, $offset, $trash = FALSE) {
        $tabel[] = array('<input type="checkbox" id="all_edit" name="edit" value="' . $value_id . '">', 'Action', 'Téléphone','Email', 'Prènom', 'Nom', 'Civilité', 'Pays', 'Adresse', 'date de modification');
        $this->load->helper('url');
        $this->load->model($model_name);
        $this->load->model('List_contact');
        $user_list = new List_contact();
        $user_list->load($value_id);
        if ($user_list->user_id == $this->session_local['user_id']) {
            $where_array = array(array(
                    'where_tag' => $key_id,
                    'where_value' => $value_id,
                ),
                array('where_tag' => 'trash',
                    'where_value' => $trash
                )
            );
            $objects = $this->Contact->get($limit, $offset, $where_array);

            // $objects = $this->Contact->get_with_join(new List_Contact(),0,$offset,$key_id, $list_id);       
            foreach ($objects as $object) {

                $tabel[] = array(
                    '<input type="checkbox" class="item-to-edit" name="chk[]" value="' . $object->contact_id . '">',
                    '<a class="delete-button" id="' . $object->contact_id . '"><i class="fa fa-trash-o"></i></a>| <a class="edit-button" href="' . base_url() . 'index.php/lists_contacts/update_contact/' . $object->contact_id . '" id="' . $object->contact_id . '"><i class="fa fa-pencil-square-o"></i></a>',
                    $object->phone_number,
                    $object->mail,
                    $object->l_name,
                    $object->f_name,
                    $object->sex,
                    $object->country,
                    $object->address,
                    $object->date_modified
                );
            }
        } else {
            $this->load->view("error_400.php");
        }


        return $tabel;
    }

    /* ========================================================================= Rules for forms */

    /**
     * @param String $key 
     * @return boolean
     */
    public function key_exists($key) {

        if ($this->List_contact->var_exists_for_user('name', $key, "user_id", $this->session_local['user_id'])) {

            $this->form_validation->set_message('key_exists', "Nom de la liste existe déjà.");
            return FALSE;
        }
        return TRUE;
    }

    /**
     * verifiction need join
     * @param type $key
     * @return boolean
     */
    public function key_exists_contact($key) {


        $result = $this->Contact->var_exists_for_user_join($this->List_contact, "user_id", $this->session_local['user_id'], "phone_number", $key, "name");
        if ($result) {

            $this->form_validation->set_message('key_exists_contact', "Numéro existe déjà dans la liste : " . $result->name);
            return FALSE;
        }
        return True;
    }

    /**
     * verifiction need join
     * @param type $key
     * @return boolean
     */
    public function key_exists_contact_update($key) {


        $result = $this->Contact->var_exists_for_user_join($this->List_contact, "user_id", $this->session_local['user_id'], "phone_number", $key, "name");
        if ($result && ($key != $this->phone_to_update)) {

            $this->form_validation->set_message('key_exists_contact_update', "Numéro existe déjà dans la liste : " . $result->name);
            return FALSE;
        }
        return True;
    }

    /* ========================================================================== Ajax 
      /**
     * view_ajax
     * 
     */

    public function view_ajax() {
        /*  $this->load->helper('url');
          $this->load->library('form_validation'); */
        $this->load->library('table');
        $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="table-striped   table">'));
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'tabel_action':
                    $list_id = $_POST['list_id'];
                    $unity = $_POST['unity'];
                    $count = $_POST['count'];
                    $limit = $unity;
                    $offset = $unity * $count;
                    $tabel_contacts = $this->get_table_with_limit('Contact', "list_id", $list_id, $limit, $offset);

                    echo $this->table->generate($tabel_contacts);

                    break;
                case 'length_tabel':
                    $list_id = $_POST['list_id'];
                    echo $this->get_number_rows('Contact', "list_id", $list_id);
                    break;
                case 'get_lists':
                    $this->load->model('List_contact');
                    $where_array = array(array(
                            'where_tag' => "user_id",
                            'where_value' => $this->session_local['user_id']
                    ));
                    echo json_encode($this->List_contact->get(0, 0, $where_array));
                    break;
                case 'delete':

                    $id = $_POST['id'];

                    $this->load->model('Contact');
                    $contact = new Contact();
                    $contact->load($id);

                    $contact->trash = True;
                    $contact->update();
                    $this->load->view("alert_view", array("message" => "Contact (Nom :" . $contact->f_name . " Prènom :" . $contact->l_name . " Téléphone :" . $contact->phone_number . ") a été mis à la corbeille ", 'alert' => 'alert-success'));
                    break;

                case 'move_list':
                    $array_to_update = $_POST['array'];
                    $id_list = $_POST['id'];

                    $this->load->model('Contact');
                    $contact = new Contact();
                    foreach ($array_to_update as $id) {
                        $contact->load($id);
                        $contact->list_id = $id_list;
                        $contact->update();
                    }
                    $this->load->view("alert_view", array("message" => "Des contacts ont été déplacer . ", 'alert' => 'alert-success'));
                    break;
                case 'delete_list':

                    $array_to_restor = $_POST['id'];

                    $this->load->model('Contact');
                    $contact = new Contact();
                    foreach ($array_to_restor as $id) {
                        $contact->load($id);
                        $contact->trash = True;
                        $contact->update();
                    }

                    $this->load->view("alert_view", array("message" => "Liste des Contact a été mis à la corbeille ", 'alert' => 'alert-success'));

                    break;
            }
        }
    }

    function ajax_lists() {
        $this->load->library('table');
        $this->load->model(array('Contact', 'List_contact', 'User'));
        $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="table-striped   table">'));
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'get_lists':
                    $this->load->model('List_contact');
                    $where_array = array(array(
                            'where_tag' => "user_id",
                            'where_value' => $this->session_local['user_id']
                    ));
                    echo json_encode($this->List_contact->get(0, 0, $where_array));
                    break;
                case 'length_trash':

                    echo $this->get_count_trash();
                    break;
                case 'move_list':
                    $list_id = $_POST['list_id'];
                    $id_to_move = $_POST['move_id'];

                    $this->load->model('Contact');
                    $where_array = array(array(
                            'where_tag' => "list_id",
                            'where_value' => $list_id
                    ));
                    $contacts = $this->Contact->get(0, 0, $where_array);

                    foreach ($contacts as $contact) {

                        $contact->list_id = $id_to_move;
                        $contact->update();
                    }
                    $this->load->view("alert_view", array("message" => "Des contacts ont été déplacer . ", 'alert' => 'alert-success'));
                    break;

                case 'export_all':
                    $this->export_contacts();
                    break;
                case 'export_list':
                    $list_id = $_POST['list_id'];
                    $this->export_contacts($list_id);

                    break;
            }
        }
    }

    function ajax_trash() {

        $this->load->library('table');
        $this->load->model(array('Contact', 'List_contact', 'User'));
        $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="table-striped   table">'));
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'tabel_action':

                    $unity = $_POST['unity'];
                    $count = $_POST['count'];
                    $limit = $unity;
                    $offset = $unity * $count;
                    $tabel_contacts = $this->get_table_trash_with_limit($limit, $offset);

                    echo $this->table->generate($tabel_contacts);

                    break;
                case 'length_tabel':

                    echo $this->get_count_trash();
                    break;
                case 'get_lists':
                    $this->load->model('List_contact');
                    $where_array = array(array(
                            'where_tag' => "user_id",
                            'where_value' => $this->session_local['user_id']
                    ));
                    echo json_encode($this->List_contact->get(0, 0, $where_array));
                    break;
                case 'restore':

                    $id = $_POST['id'];

                    $this->load->model('Contact');
                    $contact = new Contact();
                    $contact->load($id);

                    $contact->trash = False;
                    $contact->update();
                    $this->load->view("alert_view", array("message" => "Contact (Nom :" . $contact->f_name . " Prènom :" . $contact->l_name . " Téléphone :" . $contact->phone_number . ") a été déplacer ", 'alert' => 'alert-success'));
                    break;
                case 'delete':

                    $id = $_POST['id'];

                    $this->load->model('Contact');
                    $contact = new Contact();
                    $contact->load($id);
                    $l_name = $contact->l_name;
                    $f_name = $contact->f_name;
                    $phone_number = $contact->phone_number;
                    $contact->delete();
                    $this->load->view("alert_view", array("message" => "Contact (Nom :" . $f_name . " Prènom :" . $l_name . " Téléphone :" . $phone_number . ") a été Supprimer ", 'alert' => 'alert-warning'));

                    break;

                case 'delete_list':

                    $array_to_remove = $_POST['id'];

                    $this->load->model('Contact');
                    $contact = new Contact();
                    foreach ($array_to_remove as $id) {
                        $contact->load($id);
                        $contact->delete();
                    }

                    $this->load->view("alert_view", array("message" => "Liste des Contact a été Supprimer ", 'alert' => 'alert-warning'));

                    break;
                case 'restore_list':

                    $array_to_restor = $_POST['id'];

                    $this->load->model('Contact');
                    $contact = new Contact();
                    foreach ($array_to_restor as $id) {
                        $contact->load($id);
                        $contact->trash = False;
                        $contact->update();
                    }

                    $this->load->view("alert_view", array("message" => "Liste des Contact a été Rastaurer ", 'alert' => 'alert-success'));

                    break;
            }
        }
    }

    /*     * ***************************************************************************** export ajax excel */

    private function export_contacts($list_id = false) {
        $this->load->model('List_contact');
        $this->load->model('Contact');

        $this->load->library('PHPExcel');

// create new PHPExcel object
        $objPHPExcel = new PHPExcel;
// set default font
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
// set default font size
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
// create the writer
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");


// writer already created the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
// rename the sheet
        $objSheet->setTitle('Contacts');

// let's bold and size the header font and write the header
// as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);

// write header
        $objSheet->getCell('A1')->setValue('Téléphone');
        $objSheet->getCell('B1')->setValue('Mail');
        $objSheet->getCell('C1')->setValue('Prènom');
        $objSheet->getCell('D1')->setValue('Nom');
        $objSheet->getCell('E1')->setValue('Pays');
        $objSheet->getCell('F1')->setValue('Adresse');
        $objSheet->getCell('G1')->setValue('Civilité');
        if (!$list_id) {
            $where_array = array(array(
                    'where_tag' => "user_id",
                    'where_value' => $this->session_local['user_id']
            ));
            $lists = $this->List_contact->get(0, 0, $where_array);
            $index = 2;
            foreach ($lists as $list) {
                $where_array = array(array(
                        'where_tag' => 'list_id',
                        'where_value' => $list->list_id,
                    ),
                    array('where_tag' => 'trash',
                        'where_value' => false
                    )
                );
                $objects_contact = $this->Contact->get(0, 0, $where_array);

                // $objects = $this->Contact->get_with_join(new List_Contact(),0,$offset,$key_id, $list_id);       
                foreach ($objects_contact as $contact) {
                    $objSheet->getCell('A' . $index)->setValue($contact->phone_number);
                    $objSheet->getCell('B' . $index)->setValue($contact->mail);
                    $objSheet->getCell('C' . $index)->setValue($contact->l_name);
                    $objSheet->getCell('D' . $index)->setValue($contact->f_name);
                    $objSheet->getCell('E' . $index)->setValue($contact->country);
                    $objSheet->getCell('F' . $index)->setValue($contact->address);
                    $objSheet->getCell('G' . $index)->setValue($contact->sex);

                    $index ++;
                }
            }
        } else {
            $index = 2;
            $where_array = array(array(
                    'where_tag' => 'list_id',
                    'where_value' => $list_id,
                ),
                array('where_tag' => 'trash',
                    'where_value' => false
                )
            );
            $objects_contact = $this->Contact->get(0, 0, $where_array);

            // $objects = $this->Contact->get_with_join(new List_Contact(),0,$offset,$key_id, $list_id);       
            foreach ($objects_contact as $contact) {
                $objSheet->getCell('A' . $index)->setValue($contact->phone_number);
                $objSheet->getCell('B' . $index)->setValue($contact->mail);
                $objSheet->getCell('C' . $index)->setValue($contact->l_name);
                $objSheet->getCell('D' . $index)->setValue($contact->f_name);
                $objSheet->getCell('E' . $index)->setValue($contact->country);
                $objSheet->getCell('F' . $index)->setValue($contact->address);
                $objSheet->getCell('G' . $index)->setValue($contact->sex);

                $index ++;
            }
        }
        /*
          // create some borders
          // first, create the whole grid around the table
          $objSheet->getStyle('A1:D5')->getBorders()->
          getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          // create medium border around the table
          $objSheet->getStyle('A1:D5')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
          // create a double border above total line
          $objSheet->getStyle('A5:D5')->getBorders()->
          getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
          // create a medium border on the header line
          $objSheet->getStyle('A1:D1')->getBorders()->
          getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
         */
// autosize the columns
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setAutoSize(true);
        $objSheet->getColumnDimension('D')->setAutoSize(true);
        $objSheet->getColumnDimension('E')->setAutoSize(true);
        $objSheet->getColumnDimension('F')->setAutoSize(true);
        $objSheet->getColumnDimension('G')->setAutoSize(true);

// write the file
        // $this->load->helper('download');
        //$this->load->helper("file");

        $this->load->model('User');
        $user = new User();
        $user->load($this->session_local['user_id']);



        //$data = file_get_contents("new.xls"); // Read the file's contents
        //$name = 'new.xls';

        /*
          if (unlink('new.xls')) {
          //  echo' deleted';
          } else {
          //  echo'not deleted';
          } */

        $this->load->helper('url');
        if ($list_id) {
            $list = new List_contact();
            $list->load($list_id);
            $objWriter->save('download_files/' . $user->company . ' -liste-' . $list->name . '. contacts.xlsx');
            echo base_url() . 'download_files/' . $user->company . ' -liste-' . $list->name . '. contacts.xlsx';
        } else {
            $objWriter->save('download_files/' . $user->company . '. toutes_les_contacts.xlsx');
            echo base_url() . 'download_files/' . $user->company . '. toutes_les_contacts.xlsx';
        }


        //force_download($name, $data);
    }

}
