@activeAnyCan(['edit-PayrollAllowance', 'delete-PayrollAllowance'])
    <x-table-action>
        @activeCan('edit-PayrollAllowance')
            <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('allowances.edit', $id) }}"
                data-ajax-modal="true" data-title="{{ __('Edit Allowance') }}" data-bs-toggle="tooltip"
                data-bs-title="{{ __('Edit Allowance') }}" data-size="md"><i class="fa-solid fa-pencil m-r-5"></i>
                {{ __('Edit') }}
            </a>
        @endactiveCan
        @activeCan('delete-PayrollAllowance')
            <a class="dropdown-item deleteBtn" data-route="{{ route('allowances.destroy', $id) }}"
                data-title="{{ __('Delete Allowance') }}" data-question="{{ __('Are you sure you want to delete?') }}"
                href="javascript:void(0)">
                <i class="fa-regular fa-trash-can m-r-5"></i>
                {{ __('Delete') }}
            </a>
        @endactiveCan
    </x-table-action>

@endactiveAnyCan