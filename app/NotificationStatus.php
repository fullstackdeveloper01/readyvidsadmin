<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationStatus extends Model
{
    protected $table = 'notification_status';
    protected $primaryKey = 'id';

    function noti()
    {
        return $this->hasone('App\Notification','id','notification_id');
    }
}
