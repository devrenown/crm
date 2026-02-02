
@activeAnyCan(['edit-work-task', 'delete-work-task', 'view-work-tasks'])
<x-table-action>
    @activeCan('edit-work-task')
    <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('work-report.edit', $id) }}" data-ajax-modal="true"
        data-title="{{ __('Edit Task') }}" data-size="lg"><i class="fa-solid fa-pencil m-r-5"></i>
        {{ __('Edit') }}
    </a>
    @endactiveCan

    @activeCan('delete-work-task')
    <a class="dropdown-item deleteBtn" data-route="{{ route('work-report.delete', ['task_id' => $id]) }}" data-title="{{ __('Delete Task') }}"
        data-question="{{ __('Are you sure you want to delete?') }}" href="javascript:void(0)">
        <i class="fa-regular fa-trash-can m-r-5"></i>
        {{ __('Delete') }}
    </a>
    @endactiveCan

    @if(activeRole() !== \App\Enums\UserType::EMPLOYEE->value)
        @activeCan('view-work-tasks')
        <a href="{{ route('employee.work-report', ['emp_id' => encrypt($emp_id)]) }}" class="dropdown-item">
            <i class="fa-solid fa-clock-rotate-left m-r-5"></i>
            {{ __('History') }}
        </a>
        @endactiveCan
    @endif
</x-table-action>
@endactiveAnyCan
