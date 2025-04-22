<?php
class Pegawai_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all records
    public function get_all() {
        return $this->db->get('pegawai')->result();
    }

    // Get single record by ID (new primary key)
    public function get_by_id($id) {
        return $this->db->get_where('pegawai', ['id' => $id])->row();
    }

    // Create new record
    public function create($data) {
        // Add timestamps
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('pegawai', $data);
        return $this->db->insert_id(); // Return newly created ID
    }

    // Update existing record
    public function update($id, $data) {
        // Update timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id); // Use the ID to find the record
        return $this->db->update('pegawai', $data);
    }
    // Delete record
    public function delete($nip) {
        $this->db->where('nip', $nip);
        return $this->db->delete('pegawai');
    }

    // Additional useful methods

    // Get by NIP
    public function get_by_nip($nip) {
        return $this->db->get_where('pegawai', ['nip' => $nip])->row();
    }

    // Check if NIP exists
    public function nip_exists($nip) {
        return $this->db->get_where('pegawai', ['nip' => $nip])->num_rows() > 0;
    }

    // Check if NIP exists excluding current ID (for updates)
    public function nip_exists_except($nip, $id) {
        $this->db->where('nip', $nip);
        $this->db->where('id !=', $id);
        return $this->db->get('pegawai')->num_rows() > 0;
    }

    // Get total records count
    public function count_all() {
        return $this->db->count_all('pegawai');
    }

    public function count_pegawai_stats() {
        // Create subquery first
        $subquery = $this->db
            ->select('nip, MAX(tglaktif_jabatan) as max_tgl')
            ->from('pegawai')
            ->group_by('nip')
            ->get_compiled_select();
        $this->db->reset_query();
    
        // Helper function to build fresh queries
        $build_base_query = function() use ($subquery) {
            $this->db->from('pegawai')
                     ->join("($subquery) as latest", 
                         'pegawai.nip = latest.nip AND pegawai.tglaktif_jabatan = latest.max_tgl')
                     ->where('pegawai.aktif', 'Aktif');
        };
    
        // Get counts with fresh queries
        return [
            'totalActive'   => $this->get_count($build_base_query),
            'totalMale'     => $this->get_count($build_base_query, ['pegawai.jenis_kelamin' => 'Laki-laki']),
            'totalFemale'   => $this->get_count($build_base_query, ['pegawai.jenis_kelamin' => 'Perempuan']),
            'totalPermanen' => $this->get_count($build_base_query, ['pegawai.status' => 'Permanen']),
            'totalKontrak'  => $this->get_count($build_base_query, ['pegawai.status' => 'Kontrak'])
        ];
    }
    
    private function get_count($build_query, $where = []) {
        $build_query(); // Build base query
        foreach($where as $key => $val) {
            $this->db->where($key, $val);
        }
        $count = $this->db->count_all_results();
        $this->db->reset_query(); // Critical reset
        return $count;
    }
}