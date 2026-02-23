<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserType;
use App\Models\ClientDetail;
use Illuminate\Http\Request;
use App\DataTables\ClientDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class ReportingManagersController extends Controller
{
	public $view = 'pages.reporting-manager.';

	public function index ()
	{
		$data = [
			'pageTitle'	 		=> 'Reporting Managers',
			'reportingManagers' => User::reportingManagerList() ?? [],
		];

		return view($this->view . 'index', $data);
	}

	public function assignView () 
	{
		$data = [
			'pageTitle' => 'Assing Reporting Manager',
			'employees' => User::select('id', 'firstname', 'middlename', 'lastname')->get(),
		];

		return view($this->view .'assign', $data);
	}

	public function assign (Request $request)
	{
		$validate = $request->validate([
			'reporting_manager' => 'required',
			'teams'  			=> 'required',
		]);

		if (!$validate) {
			$notification = notify('Please Fill Required Fields !');
			return back()->with($notification);
		}

		$teams = $request->teams ?? [];

		foreach ($teams as $team) {
			$team = User::find($team);

			$team->update(['reporting_manager' => $request->reporting_manager]);
		}

		$notification = notify('Reporting Manager Assigned Successfully');
		return back()->with($notification);
	}

	public function editAssign (Request $request)
	{
		$data = [
			'pageTitle' => 'Edit Reporting Manager',
			'reportingManagerId' 	=> $request->reporting_manager,
			'employees'				=> User::select('id', 'firstname', 'middlename', 'lastname', 'reporting_manager')->get(),
		];

		return view($this->view . 'assign-edit', $data);
	}

	public function updateAssign (Request $request)
	{
		$validate = $request->validate([
			'reporting_manager' => 'required',
			'teams'  			=> 'required',
		]);

		if (!$validate) {
			$notification = notify('Please Fill Required Fields !');
			return back()->with($notification);
		}

		$teams = $request->teams ?? [];

		foreach ($teams as $team) {
			$team = User::find($team);
			$team->update(['reporting_manager' => $request->reporting_manager]);
		}

		$notification = notify('Reporting Manager Update Successfully');
		return back()->with($notification);
	}

	public function deleteReportingManager (Request $request) 
	{
		 $reportingManager = $request->reporting_manager;

	    // Bulk update all users who have this manager
	    User::where('reporting_manager', $reportingManager)
	        ->update(['reporting_manager' => null]);

	    $notification = notify('Reporting manager removed successfully for all members');

	    return back()->with($notification);
	}
}