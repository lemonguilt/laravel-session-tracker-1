<?php namespace Seismicsix\SessionTracker\Traits;

use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Application;
use Seismicsix\SessionTracker\Models\Session as SessionTrack;

trait SessionTrackerUserTrait{

    public function activeSessions($exceptSelf = false){
        $query =  $this->sessions()->where('end_date', null)->where('block', SessionTrack::STATUS_DEFAULT)->where('login_code', null);
        if($exceptSelf){
            if(Session::has('dbsession.id')){
                $query->where('id', '!=', Session::get('dbsession.id'));
            }
        }
        return $query;
    }

    public function sessions(){
        return $this->hasMany('Seismicsix\SessionTracker\Models\Session');
    }

    public function getFreshestSession(){
        return $this->sessions()->orderBy('last_activity', 'desc')->first();
    }

    public function devices(){
        return $this->hasMany('Seismicsix\SessionTracker\Models\Device');
    }

    public function devicesUids(){
      //for 5.2 version above
      $query = $this->devices()->pluck('uid');

      if(!str_contains(Application::VERSION, '5.0')){
          $query = $query->all();
      } else if(str_contains(Application::VERSION, '5.0') || str_contains(Application::VERSION, '5.1')){
          $query = $this->devices()->lists('uid');
      }
      return $query;
    }
}
