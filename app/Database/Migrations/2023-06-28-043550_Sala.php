<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sala extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' =>[
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' =>   true,
                'auto_increment' => true,
                'null' => false,
            ],
            'operador_id' =>[
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'sala' =>[
                'type' => 'INT',
                'constraint' => 50,
                'null' => false,
            ] 
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('sala');   
    }

    public function down()
    {
        $this->forge->dropTable('sala');
    }
}
