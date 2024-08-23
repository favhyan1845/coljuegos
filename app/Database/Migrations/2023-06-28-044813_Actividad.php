<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Actividad extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'estado' =>[
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
            ] 
        ]);
        $this->forge->addPrimaryKey('estado');
        $this->forge->createTable('actividad');   
    }

    public function down()
    {
        $this->forge->dropTable('actividad');
    }
}
