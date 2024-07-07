<?php

namespace App\Domains\Migration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MigrationRecord extends Model
{
    use HasFactory, HasUuids;
}
