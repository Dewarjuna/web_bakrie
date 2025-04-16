<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_pegawai_table extends CI_Migration {
    public function __construct() {
        parent::__construct();
        $this->load->dbforge(); // Load the dbforge library
    }
    // Create the pegawai table
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nip' => array(
                'type' => 'VARCHAR',
                'constraint' => 100, 
            ),
            'nama' => array(
                'type' => 'VARCHAR',
                'constraint' => 100, 
            ),
            'kelamin' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'jabatan' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'tglaktif_jabatan' => array(
                'type' => 'DATE',
                
            ),
            'tglmasuk_jabatan' => array(
                'type' => 'DATE',
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'aktif' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE); // Set 'id' as primary key
        $this->dbforge->create_table('pegawai');
    }

    public function down() {
        $this->dbforge->drop_table('pegawai');
    }
}