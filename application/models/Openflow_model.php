<?php

class Openflow_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function record_count($keyword) {
        $this->db->like('name', $keyword);
        $this->db->from('inventoryflows');
        return $this->db->count_all_results();
    }

    public function fetch_openflow($limit, $start, $keryword) {
        $this->db->like('name', $keryword);
        $this->db->limit($limit, $start);
        $query = $this->db->get('inventoryflows');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function entry_openflow($id=NULL) {
        $data = array(
            'item_id' => $this->input->post('item_id'),
            'itemname' => $this->input->post('itemname'),
            'open_qty' => $this->input->post('open_qty'),
            'unit' => $this->input->post('unit'),
            'open_date' => date('Y-m-d',strtotime(str_replace('/','-',$this->input->post('open_date')))),
            'open_entry' => $this->input->post('open_entry'),
            'open_location' => $this->input->post('location_id')
        );
        if ($id == NULL) {
            $this->db->insert('openflows', $data);
        } else {
            $this->db->update('openflows', $data, array('id' => $id));
        }
    }

    public function read_openflow($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('inventoryflows');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        }
        return FALSE;
    }

    public function remove_openflow($id) {
        $this->db->delete('inventoryflows', array('id' => $id));
    }

}
