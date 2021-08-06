<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Coord_cache;

class MapController extends Controller {

    const MARKERS_NUM = 5; //number of markers to show
    const TERM = 2592000; // coordinates cache storage life (30 days)
    const APIKEY = 'AIzaSyCuD4ARi7M1DKuSxK7RedS7HLep8BbN90s';

    public function index() {
        $results = DB::table('country')->orderBy('name')->get();

        $countries = array();
        foreach ($results as $r) {
            $countries[] = array('id'=>$r->id, 'country'=>$r->name);
        }
        return view('frontend.map.map', ['countries' => $countries]);
    }

    public function getStateList() { //ajax load states list for selected country
        if (!isset($_GET['country_id'])) {exit();}
        $results = DB::table('state')->where('country', $_GET['country_id'])->orderBy('name')->get();

        $regions = array();
        foreach ($results as $r) {
            $regions[] = array('id'=>$r->id, 'state'=>$r->name);
        }

        echo json_encode($regions);
    }
        
    public function getCityList() { //ajax load cities list for selected state
        if (!isset($_GET['state_id'])) {exit();}
        $results = DB::table('city')->where('state', $_GET['state_id'])->orderBy('name')->get();

        $cities = array();
        foreach ($results as $r) {
            $cities[] = array('id'=>$r->id, 'city'=>$r->name);
        }

        echo json_encode($cities);
    }
    
    public function getAddressList() {
        if (!isset($_GET['city_id'])) {exit();}
        $city = $_GET['city_id'];
        
        // select all agents (role = 2) from the city
        $results = DB::select("SELECT * FROM users_details WHERE city = ?" . 
                " AND uid IN (SELECT user_id FROM assigned_roles WHERE role_id = 2)", [$city]);
        $address_num = count($results);
        
        if ($address_num == 0) {
            exit('NO_RECORDS');
        } elseif ($address_num == 1) {
            $agents_id[] = 0;
        } elseif ($address_num > self::MARKERS_NUM) {
            $agents_id = array_rand($results, self::MARKERS_NUM); //select 5 random records from $results
        } else {
            $agents_id = range(0, $address_num - 1);
        }
        
        // only city and country for the map center
        $address = ['address' => '', 'city' => $results[0]->city, 'state' => $results[0]->state, 'country' => $results[0]->country];
        $markers[] =  $this->getMarker($address);
        
        // addresses for agents
        foreach ($agents_id as $r) {
            $address = ['address' => $results[$r]->address, 'city' => $results[$r]->city, 'state' => $results[$r]->state, 'country' => $results[$r]->country];
            $markers[] =  $this->getMarker($address);
        }
        
        // $markers[0] - city(center) coordinates
        // $markers[n] - address coordinates
        echo json_encode($markers);
    
    } // END getAddressList()

    private function getMarker($addr) {
        $cache = new Coord_cache;
        $hashAddr = md5($addr['address'] . $addr['city'] . $addr['state'] . $addr['country']);
        $fromCache = Coord_cache::where('hash', $hashAddr)->first();
        
        $mark['address'] = $addr['address'];

        if (count($fromCache) == 0) {
            // name of the country
            $mark['country'] = DB::table('country')->where('id', $addr['country'])->value('name');
            
            // name of the city
            $mark['city'] = DB::table('city')->where('id', $addr['city'])->value('name');
            
            $address = $addr['address'] . '+' . $mark['city'] . '+' . $mark['country'];
            $mark['geocodeAddr'] = str_replace(' ', '+', $address); //if this is not empty, js will request geocode
            $mark['hash'] = $hashAddr;
        } else {
            $mark['geocodeAddr'] = '';
            $mark['lat'] = $fromCache->lat;
            $mark['lng'] = $fromCache->lng;

            if ((strtotime('now') - strtotime($fromCache->created_at)) > self::TERM) {
                Cache::destroy($fromCache->id);
            }
        }
        return $mark;
    } // END getMarker
    
    public function toCache() {
        if (!isset($_GET['hash'])) {exit();}
        $cache = new Coord_cache;

        $cache->hash = $_GET['hash'];
        $cache->lat = $_GET['lat'];
        $cache->lng = $_GET['lng'];
                
        $cache->save();
    }
    
    public function getGeo() { //ajax-load browser apiKey for geocode

//TO DO /  add superadmin possibility to change APIKEY
        
        $apiKey = 'https://maps.googleapis.com/maps/api/js?key=' . self::APIKEY . '&signed_in=true&callback=initMap';
        echo $apiKey;
    }
    
} // class end
