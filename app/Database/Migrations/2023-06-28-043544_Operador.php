<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class Operador extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'operador_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' =>   true,
                'auto_increment' => true
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'nit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'direccion' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'contacto' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'contrato' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'correo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'unique' => true
            ],
            'telefono' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'estado' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false
            ]
        ]);
        $this->forge->addPrimaryKey('operador_id');
        $this->forge->createTable('operador');        
    }

    public function down()
    {
        $this->forge->dropTable('operador');
    }
}
