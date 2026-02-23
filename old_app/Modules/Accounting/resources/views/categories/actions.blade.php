@activeAnyCan(['edit-budgetCategory', 'delete-budgetCategory'])
    <x-table-action>
        @activeCan('edit-budgetCategory')
            <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('budget.categories.edit', $id) }}" data-ajax-modal="true"
                data-title="{{ __('Edit Budget Category') }}" data-bs-toggle="tooltip" data-bs-title="{{ __('Edit Tax') }}"
                data-size="md"><i class="fa-solid fa-pencil m-r-5"></i>
                {{ __('Edit') }}
            </a>
        @endactiveCan
        @activeCan('delete-budgetCategory')
            <a class="dropdown-item deleteBtn" data-route="{{ route('budget.categories.destroy', $id) }}"
                data-title="{{ __('Delete Budget Category') }}" data-question="{{ __('Are you sure you want to delete?') }}"
                href="javascript:void(0)">
                <i class="fa-regular fa-trash-can m-r-5"></i>
                {{ __('Delete') }}
            </a>
        @endactiveCan
    </x-table-action>
@endactiveAnyCan