<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobsLog;

class JobsLogController extends Controller
{
    public function index()
    {

        // $datas = JobsLog::take(5)->get();

        // $datas = JobsLog::where('id', '>', $lastId)->take(5)->get();

        // $lastId = $datas->max('id');

        // foreach($datas as $data){
        //     $data->update([
        //         'last_id' => $lastId
        //     ]);
        // }


        // return $datas;


        // $query = JobsLog::limit(5)->get();

        // $jobsLogs = JobsLog::where('id', '>', 'last_id')
        // ->orderBy('id', 'asc')
        // ->limit(5)
        // ->get();




        // Get the current last_id (fetch from persistent storage, e.g., DB or session)
        $lastId = JobsLog::max('last_id') ?? 0;

        $jobsLogs = JobsLog::where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->limit(5)
            ->get();


        if ($jobsLogs->isNotEmpty()) {

            $lastRecordId = $jobsLogs->last()->id;
            JobsLog::whereIn('id', $jobsLogs->pluck('id'))->update(['updated_at' => now()]);
            JobsLog::where('id', $lastRecordId)->update(['last_id' => $lastRecordId]);
        }

        return response()->json($jobsLogs);
    }
}
