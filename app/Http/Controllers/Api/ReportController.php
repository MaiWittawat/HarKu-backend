<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\User;

class ReportController extends Controller
{
    public function addReport(Request $request)
    {

        // return $request->all();

        $report_by = User::where('email', $request->report_by)->first();
        $report_to = User::where('email', $request->report_to)->first();


        // return $report_by;

        $report = new Report();
        $report->report_by = $report_by->id;
        $report->report_to = $report_to->id;
        $report->body = $request->body;

        $report_to->status = false;
        $report_to->save();
        $report->save();

        return "report success";
    }


    public function getAllReport()
    {
        $data = User::select('users.*')
            ->join('reports', 'users.id', '=', 'reports.report_to')
            ->selectRaw('COUNT(reports.id) as report_count')
            ->where('users.status', '!=', 1)
            ->groupBy('users.id')
            ->get();

        return $data;
    }




    public function ban($id)
    {
        // return $id;
        $user = User::find($id);
        $user->status = 1;
        $user->save();

        return "Ban user success";
    }

    public function unban($id)
    {
        $user = User::find($id);
        $user->status = null;
        $user->save();

        $reports = Report::where('report_to', $user->id)->delete();

        return "Unban user success";
    }


    public function getReportDetail($id)
    {

        $reports = Report::where('report_to', $id)->get();

        $reportDetails = [];

        foreach ($reports as $report) {
            $reporter = User::find($report->report_by);

            $reportDetails[] = [
                'reporter' => $reporter,
                'body' => $report->body,
            ];
        }

        return $reportDetails;
    }



}
