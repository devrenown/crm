@activeAnyCan(['show-asset', 'edit-asset', 'delete-asset'])
<x-table-action>
    @activeCan('show-asset')
        <a class="dropdown-item" href="{{ route('assets-list.show', $id) }}"><i class="fa-solid fa-eye m-r-5"></i>
            {{ __('View') }}
        </a>
    @endactiveCan
    @activeCan('edit-asset')
        <a class="dropdown-item" href="javascript:void(0)" data-url="{{ route('assets-list.edit', $id) }}"
            data-ajax-modal="true" data-title="{{ __('Edit Asset') }}" data-size="lg"><i
                class="fa-solid fa-pencil m-r-5"></i>
            {{ __('Edit') }}
        </a>
    @endactiveCan
    @activeCan('delete-asset')
        <a class="dropdown-item deleteBtn" data-route="{{ route('assets-list.destroy', $id) }}"
            data-title="{{ __('Delete Asset') }}" data-question="{{ __('Are you sure you want to delete asset?') }}"
            href="javascript:void(0)">
            <i class="fa-regular fa-trash-can m-r-5"></i>
            {{ __('Delete') }}
        </a>
    @endactiveCan
</x-table-action>
@endactiveAnyCan