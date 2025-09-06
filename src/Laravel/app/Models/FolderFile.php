<?php

// app/Models/FolderFile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderFile extends Model
{
    protected $fillable = ['folder_id', 'file_name', 'file_path'];


    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}

