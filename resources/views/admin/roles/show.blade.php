<div>
    <h5>Name: {{ $role->name }}</h5>
    <p><strong>Permissions:</strong>
        @foreach($role->permissions as $permission)
        <label class="badge bg-info">{{ $permission->name }}</label>
        @endforeach
    </p>
</div>