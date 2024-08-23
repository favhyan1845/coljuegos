<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Reporte extends Migration
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
        'id_operador' =>[
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => false,
        ],
        'fecha' =>[
            'type' => 'datetime',
            'null' => true,
        ],
        'respuesta_api' =>[
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => false,
        ],
        'csv' =>[
            'type' => 'BLOB',
            'null' => false,
        ],
        'estado' =>[
            'type' => 'INT',
            'constraint' => 5,
            'null' => false,
        ]
    ]);
    $this->forge->addPrimaryKey('id');
    $this->forge->createTable('reporte');   
    }

    public function down()
    {
        $this->forge->dropTable('reporte');
    }
}
