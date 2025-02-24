<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FlightDestinations extends Migration
{
    public function up()
    {
    //    imageURI, slug, title, status[In-Air,Cancelled,Delayed], Price, cities, code,
    $this->forge->addField([
        'id' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'imageURI' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'null'       => true,
        ],
        'slug' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'unique'     => true,
        ],
        'title' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
           'null'       => true, // Allow null values
        ],
        'status' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'default'    => 'In-Air',
        ],
        'price' => [
            'type'       => 'DECIMAL',
            'constraint' => '10,2',
            'null'       => false,
        ],
        'cities' => [
            'type' => 'TEXT',
            'null' => true,
        ],
        'code' => [
            'type'       => 'VARCHAR',
            'constraint' => '50',
            'null'       => true,
        ],
        'created_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
        'updated_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('flights', true);
    }

    public function down()
    {
        //
    }
}
