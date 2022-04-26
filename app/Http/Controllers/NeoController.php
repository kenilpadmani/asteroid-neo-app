<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNeoRequest;
use Exception;
use Illuminate\Http\Request;

class NeoController extends Controller
{
    public function index(Request $request)
    {
        try {
            return view('neo');
        } catch (\Throwable $e) {
            return redirect(route("neo"))->with("error", $e->getMessage());
        }
    }

    public function submit(CreateNeoRequest $request)
    {
        try {
            $params = $request->all();
            if (empty($params['fromDate']) || empty($params['toDate'])) {
                throw new Exception("Resource data invalid.");
            }
            $url = "https://api.nasa.gov/neo/rest/v1/feed?start_date=" . $params['fromDate'] . "&end_date=" . $params['toDate'] . "&api_key=" . config('services.neo_api');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $neo_api_data = json_decode($output, true);
            $neo_by_array = [];
            $neo_velocity_kmph = [];
            $neo_distance_km = [];
            $neo_count_by_date = [];
            $neo_avg_size = [];
            foreach ($neo_api_data['near_earth_objects'] as $key => $value) {
                $neo_count_by_date[$key] = count($value);
                foreach ($value as $data_by_date) {
                    $neo_by_array[] = $data_by_date;
                }
            }

            foreach ($neo_by_array as $neo) {
                if (isset($neo['estimated_diameter']) && !empty($neo['estimated_diameter']) && isset($neo['estimated_diameter']['kilometers']) && !empty($neo['estimated_diameter']['kilometers']) && isset($neo['estimated_diameter']['kilometers']['estimated_diameter_min']) && !empty($neo['estimated_diameter']['kilometers']['estimated_diameter_min']) && isset($neo['estimated_diameter']['kilometers']['estimated_diameter_max']) && !empty($neo['estimated_diameter']['kilometers']['estimated_diameter_max'])) {
                    $neo_avg_size[] = ($neo['estimated_diameter']['kilometers']['estimated_diameter_max'] + $neo['estimated_diameter']['kilometers']['estimated_diameter_min']) / 2;
                }
                $colseApproachData = array_shift($neo['close_approach_data']);
                if (isset($colseApproachData) && !empty($colseApproachData) && isset($colseApproachData['relative_velocity']) && !empty($colseApproachData['relative_velocity']) && isset($colseApproachData['relative_velocity']['kilometers_per_hour']) && !empty($colseApproachData['relative_velocity']['kilometers_per_hour'])) {
                    $neo_velocity_kmph[] = $colseApproachData['relative_velocity']['kilometers_per_hour'];
                }
                if (isset($colseApproachData) && !empty($colseApproachData) && isset($colseApproachData['miss_distance']) && !empty($colseApproachData['miss_distance']) && isset($colseApproachData['miss_distance']['kilometers']) && !empty($colseApproachData['miss_distance']['kilometers'])) {
                    $neo_distance_km[] = $colseApproachData['miss_distance']['kilometers'];
                }
            }
            // Fastest Asteroid Id & Speed(in KM/Hour)
            arsort($neo_velocity_kmph);
            $fastestAseroid = \Arr::first($neo_velocity_kmph);
            $fastestAseroidkey = array_key_first($neo_velocity_kmph);
            $fastestAseroidId = $neo_by_array[$fastestAseroidkey]['id'];
            // Closest Asteroid Id & Distance(in KM)
            asort($neo_distance_km);
            $closestAseroid = \Arr::first($neo_distance_km);
            $closestAseroidkey = array_key_first($neo_velocity_kmph);
            $closestAseroidId = $neo_by_array[$closestAseroidkey]['id'];
            // Average Size of the Asteroids in kilometers
            asort($neo_avg_size);
            $avgAseroidSize = \Arr::first($neo_avg_size);
            ksort($neo_count_by_date);
            $neo_count_by_date_arry_keys = array_keys($neo_count_by_date);
            $neo_count_by_date_arry_values = array_values($neo_count_by_date);
            return view('barchart', compact('avgAseroidSize', 'fastestAseroidId', 'fastestAseroid', 'closestAseroidId', 'closestAseroid', 'neo_count_by_date_arry_keys', 'neo_count_by_date_arry_values'));
        } catch (\Throwable $e) {
            return redirect(route("neo"))->with("error", $e->getMessage());
        }
    }
}
