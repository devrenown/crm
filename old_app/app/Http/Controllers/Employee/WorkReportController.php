<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkReport;
use Illuminate\Support\Facades\Auth;
use App\DataTables\WorkReportDataTable;
use App\DataTables\AdminWorkReportDataTable;
use App\DataTables\EmployeeTaskListDataTable;
use App\Enums\UserType;

class WorkReportController extends Controller
{
    public $view = 'pages.work-report.';

    public function index (WorkReportDataTable $WorkReportDataTable, AdminWorkReportDataTable $AdminWorkReportDataTable) 
    {
        $pageTitle = __("Work Reports");

        if (activeRole() === UserType::EMPLOYEE->value) {
            return $WorkReportDataTable->render($this->view . 'index', compact('pageTitle'));
        }

        return $AdminWorkReportDataTable->render($this->view . 'index', compact('pageTitle'));
    }

    public function edit ($id)
    {
        if (!isset($id)) {
            $notification = notify('Task Not Found !');
            return back()->with($notification);
        }

        $task = WorkReport::findOrFail($id);

       $pageTitle = __("Edit Work Reports"); 
       return view($this->view . 'edit', compact('pageTitle', 'task'));
    }

    public function update (Request $request) 
    {
        $taskId = $request->task_id;

        if (!isset($taskId)) {
            $notification = notify('Task Not Found !');
            return back()->with($notification);
        }

        $task = WorkReport::findOrFail($taskId);
        $task->update([
            'title'         => $request->title,
            'project_id'    => $request->project ?? null,
            'description'   => $request->description,
            'status'        => $request->status
        ]);

        $notification = notify('Task Update Successfully');
        return back()->with($notification);
    }

    public function delete (Request $request) 
    {
       $taskId = $request->task_id;
       $task = WorkReport::findOrFail($taskId);

        if (!$task) {
            $notification = notify('Task Not Found');
            return back()->with($notification);
        }

       $task->delete();

       $notification = notify('Task Delete Successfully');
       return back()->with($notification);
    }

    public function employeeTaskListById (Request $request, EmployeeTaskListDataTable $dataTable)
    {
        $empId = decrypt($request->emp_id);
        $pageTitle = __("Employee Work Report List"); 
        $taskList = WorkReport::where('user_id', $empId)->get();
        return $dataTable->with(['empId' => $empId])->render($this->view . 'employee-task-list', compact('pageTitle'));
    }
}
