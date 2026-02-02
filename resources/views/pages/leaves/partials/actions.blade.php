<div class="btn-group">

    {{-- VIEW --}}
    @can('view', $leave)
        <a href="{{ route('leaves.show', $leave) }}"
           class="btn btn-sm btn-info">
            <i class="fa-solid fa-eye"></i> View
        </a>
    @endcan

    {{-- UPDATE (Applicant) --}}
    @can('edit', $leave)
        <a href="{{ route('leaves.edit', $leave) }}"
           class="btn btn-sm btn-primary">
            <i class="fa-solid fa-pen"></i> Update
        </a>
    @endcan

    {{-- APPROVE (TL / L2) --}}
    @can('approve', $leave)
        <a href="{{ route('leaves.approve.edit', $leave) }}"
        class="btn btn-sm btn-success">
            <i class="fa-solid fa-check"></i> Approve
        </a>
    @endcan

    {{-- DELETE (Applicant only) --}}
    @can('delete', $leave)
        <form action="{{ route('leaves.destroy', $leave) }}"
              method="POST"
              style="display:inline-block"
              onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">
                <i class="fa-solid fa-trash"></i> Delete
            </button>
        </form>
    @endcan

</div>



<!--
@activeAnyCan(['view-leave', 'edit-leave', 'delete-leave'])
<div class="btn-group">

    @activeCan('view-leave')
        <a href="{{ route('leaves.show', ['leave' => $leave->id]) }}"
           class="btn btn-sm btn-info">
            <i class="fa-solid fa-eye"></i> View
        </a>
    @endactiveCan

    @activeCan('edit-leave')
        <a href="{{ route('leaves.edit', ['leave' => $leave->id]) }}"
           class="btn btn-sm btn-primary">
            <i class="fa-solid fa-pen"></i> Update
        </a>
    @endactiveCan

    @activeCan('delete-leave')
    <form action="{{ route('leaves.destroy', ['leave' => $leave->id]) }}"
        method="POST"
        style="display:inline-block;"
        onsubmit="return confirm('Are you sure you want to delete this leave request?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fa-solid fa-trash"></i> Delete
        </button>
    </form>
    @endactiveCan


</div>
@endactiveAnyCan
-->


