<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Requests
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $message
 * @property string|null $comment
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 */
class Requests extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'requests';

    protected $fillable = [
        'name',
        'email',
        'message',
        'comment'
    ];

    protected function attributes()
    {
        return [
            'name' => 'Name',
            ''
        ];
    }
}
