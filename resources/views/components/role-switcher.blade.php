<form action="{{ route('switch.role') }}" method="POST" class="d-inline">
    @csrf
    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
        @if (auth()->user()->roles)
            @foreach(auth()->user()->roles as $role)
                <option value="{{ $role->name }}" {{ session('active_role') === $role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        @endif
    </select>
</form>

