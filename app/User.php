<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Message;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 
        'lastname',
        'username',
        'password',
        'gender',
        'mobile_number',
        'email',
        'login_type',
        'profile_picture',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function fullname()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function properties()
    {
        return $this->hasMany('App\Property', 'created_by');
    }

    public function sentMessages()
    {
        return $this->hasMany('App\Message', 'sent_from');
    }

    public function unreadMessagesCount()
    {
        return $this->hasMany('App\Message', 'sent_to')->whereNull('seen_at')->count();
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('login_type', $type);
    }
    public function isAdmin() {
       // if(!(isset($this->login_type)))
        return $this->login_type === 'ADMIN' ? true : false;
    }
    public function isEnable() {
        return $this->status === 'ENABLE' ? true : false;
    }
    public function displayName() {
        if($this->isAdmin()) {
            return $this->login_type;
        }
        return $this->username;
    }
    public function conversationWith($partner, $lastMessageId = false)
    {
        Message::where([
            ['sent_to',  '=', $this->id],
            ['sent_from',  '=', $partner],
        ])
        ->whereNull('seen_at')
        ->update([
            'seen_at' => date_create(null)->format('Y-m-d H:i:s')
        ]);
        
        $data = Message::where(function($query) USE ($partner){
            $query->where(function($q) USE ($partner){
                $q->where('sent_from', $this->id)->where('sent_to', $partner);
            })->orWhere(function($q) USE ($partner){
                $q->where('sent_from', $partner)->where('sent_to', $this->id);
            });
        })
        ->orderBy('created_at');

        if($lastMessageId){
            $data->where('id', '>', $lastMessageId);
        }else{
            $data->limit(20);
        }

        return $data->get();
    }

    public function getConversationList()
    {
        return DB::table('messages')
            ->select(
                'messages.sent_from AS sender_id', 
                DB::raw('SUM(CASE WHEN messages.seen_at IS NULL THEN 1 ELSE 0 END) AS unseen_count'), 
                DB::raw('CONCAT(users.firstname, " ", users.lastname) AS sender')
            )
            ->leftJoin('users', 'users.id', '=', 'messages.sent_from')
            ->where('messages.sent_to', $this->id)
            ->groupBy('messages.sent_from')
            ->get();
    }
    public static function changeStatus($id, $enable) {
        $user = User::find($id);
        if(!is_null($user)) {
            $enable = strtolower($enable) == 'true' ? true : false;

            if($enable) {
                $enable = 'ENABLE';
            } else {
                $enable = 'DISABLE';
            }
            $user->status = strtoupper($enable);
            $user->save();
            return true;
        }
        return false;

    }

    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }

    public function getAppointmentsCount()
    {
        if($this->login_type === 'PROPERTY_OWNER'){

            $unitIds = DB::table('properties AS p')
                ->select('u.id')
                ->join('units AS u', 'u.property_id', '=', 'u.id')
                ->where('p.created_by', '=', $this->id)
                ->get()
                ->pluck('id');
            
            if(count($unitIds)){
                return DB::table('appointments')
                    ->whereStatus('PENDING')
                    ->whereIn('unit_id', $unitIds)
                    ->whereNull('owner_seen_at')
                    ->get()
                    ->count('id');
            }

            return 0;

            
        }else if($this->login_type === 'USER'){

            return $this->appointments()
                ->whereNull('user_seen_at')
                ->where('status', '!=', 'PENDING')
                ->count('id');

        }

        return 0;
    }

    public function clearAppointmentNotification()
    {
         if($this->login_type == 'USER'){
            $this->appointments()
                ->where('status', '!=', 'PENDING')
                ->whereNull('user_seen_at')
                ->update([
                    'user_seen_at' => \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d H:i:s') 
                ]);
         }
         
    }
}
