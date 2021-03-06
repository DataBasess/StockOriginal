<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Openflow extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->model('Openflow_model');
        $this->load->model('Item_model');
        $this->load->model('Location_model');
        $this->load->model('Inventoryflow_model');
        $this->load->model('Inventory_model');
        $this->load->model('User_model');
    }

    public function index() {
        $this->load->view('template/backheader');
        $this->load->view('openflow/newdata');
        $this->load->view('template/backfooter');
    }

    public function newdata() {
        $this->load->view('template/backheader');
        $this->load->view('openflow/newdata');
        $this->load->view('template/backfooter');
    }

    public function postdata() {
        if ($this->input->server('REQUEST_METHOD') == TRUE) {
            $this->form_validation->set_rules('barcode', 'รหัสสินค้า', 'required', array('required' => 'ค่าห้ามว่าง!'));
            $this->form_validation->set_rules('open_qty', 'จำนวนเบิก', 'required|numeric', array('required' => 'ค่าห้ามว่าง!', 'numeric' => 'ตัวเลขเท่านั้น'));
            $this->form_validation->set_rules('unit', 'หน่วยนับ', 'required', array('required' => 'ค่าห้ามว่าง!'));
            $this->form_validation->set_rules('open_date', 'วันที่เบิก', 'required', array('required' => 'ค่าห้ามว่าง!'));
            $this->form_validation->set_rules('open_entry', 'ผู้เบิกสินค้า', 'required', array('required' => 'ค่าห้ามว่าง!'));
            $this->form_validation->set_rules('location_name', 'คลังสินค้า', 'required', array('required' => 'ค่าห้ามว่าง!'));
            $this->form_validation->set_rules('onqty', 'จำนวนคงเหลือ', 'required|numeric|greater_than[0]', array('required' => 'ค่าห้ามว่าง!', 'numeric'=>'ตัวเลขเท่านั้น','greater_than'=>'จำนวนคงเหลือต้องมากกว่า 0'));
            if ($this->form_validation->run() == TRUE) {

                $this->db->trans_start();
                $this->Openflow_model->entry_openflow();
                $this->Inventoryflow_model->entry_invenflow(NULL,'open');
                if ($this->Inventory_model->record_count($this->input->post('item_id'), $this->input->post('location_id')) == 1) {
                    $this->Inventory_model->update_inventory(0, $this->input->post('open_qty') * -1, 0, 0);
                } else {
                    $this->Inventory_model->entry_inventory(0, $this->input->post('open_qty') * -1, 0, 0);
                }
                $this->db->trans_complete();
                $this->session->set_flashdata(
                        array(
                            'msginfo' => '<div class="pad margin no-print"><div style="margin-bottom: 0!important;" class="callout callout-info"><h4><i class="fa fa-info"></i> ข้อความจากระบบ</h4>ทำรายการสำเร็จ</div></div>'
                        )
                );
                redirect('openflow', 'refresh');
            } else {
                $data = array(
                    'error_barcode' => form_error('barcode'),
                    'error_itemname' => form_error('itemname'),
                    'error_open_qty' => form_error('open_qty'),
                    'error_unit' => form_error('unit'),
                    'error_open_date' => form_error('open_date'),
                    'error_open_entry' => form_error('open_entry'),
                    'error_location_name' => form_error('location_name'),
                    'error_onqty' => form_error('onqty'),
                    'item_id' => set_value('item_id'),
                    'location_id' => set_value('location_id'),
                    'barcode' => set_value('barcode'),
                    'itemname' => set_value('itemname'),
                    'open_qty' => set_value('open_qty'),
                    'unit' => set_value('unit'),
                    'open_date' => set_value('open_date'),
                    'open_entry' => set_value('open_entry'),
                    'location_name' => set_value('location_name'),
                    'onqty'=>set_value('onqty')
                );
                $this->session->set_flashdata($data);
            }
            if ($this->input->post('id') == NULL) {
                redirect('openflow/newdata');
            } else {
                redirect('openflow/edit/' . $this->input->post('id'));
            }
        }
    }

    public function edit($id) {
        $data['result'] = $this->Itemtype_model->read_openflow($id);
        $this->load->view('template/backheader');
        $this->load->view('openflow/edit', $data);
        $this->load->view('template/backfooter');
    }

    public function remove($id) {
        $this->Itemtype_model->remove_openflow($id);
        redirect('openflow', 'refresh');
    }

    public function flowup() {
        $config = array();
        $config['base_url'] = base_url('openflow/flowup');
        $config['total_rows'] = $this->Inventory_model->record_count_like($this->input->get('keyword'),$this->input->get('myword'));
    		$config['per_page'] = $this->input->get('keyword') == NULL ? 14 : 999;
    		$config['uri_segment'] = 3;
    		$choice = $config['total_rows'] / $config['per_page'];
    		$config['num_links'] = round($choice);

    		$this->pagination->initialize($config);

    		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    		$data['results'] = $this->Inventory_model->fetch_inventory($config['per_page'], $page, $this->input->get('keyword'),$this->input->get('wordname'));
    		$data['link'] = $this->pagination->create_links();
    		$data['total_rows'] = $config['total_rows'];
        $this->load->view('popup/flowup', $data);
    }

    public function locationup() {
        $config = array();
        $config['base_url'] = base_url('openflow/locationup');
        $config['total_rows'] = $this->Location_model->record_count($this->input->get('keyword'));
        $config['per_page'] = $this->input->get('keyword') == NULL ? 14 : 999;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = round($choice);

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['results'] = $this->Location_model->fetch_location($config['per_page'], $page, $this->input->get('keyword'));
        $data['link'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $this->load->view('popup/locationup', $data);
    }

    public function userup() {
        $config = array();
        $config['base_url'] = base_url('openflow/userup');
        $config['total_rows'] = $this->User_model->record_count($this->input->get('keyword'));
        $config['per_page'] = $this->input->get('keyword') == NULL ? 14 : 999;
        $config['uri_segment'] = 3;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = round($choice);

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['results'] = $this->User_model->fetch_user($config['per_page'], $page, $this->input->get('keyword'));
        $data['link'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $this->load->view('popup/userup', $data);
        ;
    }

}
