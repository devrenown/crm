<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\ShiftDataTable;
use App\Models\Shift;
use App\DataTables\ShiftEmployeesDataTable;
use App\Models\EmployeeShift;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ShiftManagementController extends Controller
{
    public $view = 'pages.shift.';

    public function index (ShiftDataTable $dataTable) 
    {
        $this->data['pageTitle'] = 'Shift';

        $this->data['shifts'] = Shift::withCount([
            'employeeShifts as employees_count' => function ($q) {
                $q->whereIn('id', function ($sub) {
                    $sub->selectRaw('MAX(id)')
                        ->from('employee_shifts')
                        ->groupBy('user_id');
                });
            }
        ])->get();

        return view($this->view . 'index', $this->data);
    }

    public function list (ShiftDataTable $dataTable)
    {
      $this->data['pageTitle'] = 'Shift';
      return $dataTable->render($this->view . 'list', $this->data);  
    }

    public function create (Request $request)
    {
        return view($this->view . 'create');
    }

    public function store (Request $request) 
    {
        $request->validate([
            'name'          => 'required|string|max:20',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'break_minutes' => 'nullable|integer|min:0',
            'grace_minutes' => 'nullable|integer|min:0',
            'status'        => 'required|in:1,2',
        ]);

        Shift::create([
            'name'          => $request->name,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'break_minutes' => $request->break_minutes,
            'grace_minutes' => $request->grace_minutes,
            'status'        => $request->status,
        ]);

        return back()->with(notify('Shift created successfully'));
    }

    public function edit (Shift $shift)
    {
        return view($this->view . 'edit', compact('shift'));
    }

    public function update (Request $request, Shift $shift)
    {
        $request->validate([
            'name'          => 'required|string|max:20',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'break_minutes' => 'nullable|integer|min:0',
            'grace_minutes' => 'nullable|integer|min:0',
            'status'        => 'required|in:1,2',
        ]);

        $shift->update([
            'name'          => $request->name,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'break_minutes' => $request->break_minutes,
            'grace_minutes' => $request->grace_minutes,
            'status'        => $request->status,
        ]);

        return back()->with(notify('Shift updated successfully'));
    }

    public function destroy (Shift $shift)
    {
        $shift->delete();
        return back()->with(notify('Shift deleted successfully'));
    }

    public function employees(Shift $shift, ShiftEmployeesDataTable $dataTable)
    {
        request()->merge(['shift_id' => $shift->id]);
        return $dataTable->render($this->view . 'shift-employees');
    }

    public function addRemoveEmployeeView (Shift $shift)
    {
        $employees = User::with('shift')->where('is_active', true)->get();

        return view($this->view . 'employee-add-remove', compact('shift', 'employees'));
    }

    public function addRemoveEmployee (Request $request)
    {
        $request->validate([
            'shift_id'   => 'required|exists:shifts,id',
            'employee'   => 'array',
            'employee.*' => 'exists:users,id',
        ]);
        
        $shiftId     = $request->shift_id;
        $employeeIds = $request->employee ?? [];

        $existingEmployeeIds = EmployeeShift::where('shift_id', $shiftId)
        ->pluck('user_id')
        ->toArray();

        $removeIds = array_diff($existingEmployeeIds, $employeeIds);

        if (!empty($removeIds)) {
            EmployeeShift::where('shift_id', $shiftId)
                ->whereIn('user_id', $removeIds)
                ->delete();
        }

        $addIds = array_diff($employeeIds, $existingEmployeeIds);

        foreach ($addIds as $userId) {
            EmployeeShift::create([
                'shift_id' => $shiftId,
                'user_id'  => $userId,
            ]);
        }

        return back()->with(notify('Shift updated successfully!'));
    }
}
