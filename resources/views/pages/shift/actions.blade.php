@activeAnyCan(['edit-shift', 'delete-shift'])
<x-table-action>
    @activeCan('edit-shift')
    <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('shift.edit', ['shift' => $id]) }}" data-ajax-modal="true"
        data-title="{{ __('Edit Shift') }}" data-size="lg"><i class="fa-solid fa-pencil m-r-5"></i>
        {{ __('Edit') }}
    </a>
    @endactiveCan
    @activeCan('delete-shift')
    <a class="dropdown-item deleteBtn" data-route="{{ route('shift.destroy', ['shift' => $id]) }}" data-title="{{ __('Delete Shift') }}"
        data-question="{{ __('Are you sure you want to delete?') }}" href="javascript:void(0)">
        <i class="fa-regular fa-trash-can m-r-5"></i>
        {{ __('Delete') }}
    </a>
    @endactiveCan
</x-table-action>
@endactiveAnyCan
