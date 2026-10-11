<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'phone', 'subject', 'message', 'language_id', 'status', 'ip_address', 'handled_by'])]
class ContactMessage extends Model {}
