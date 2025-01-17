<!-- This will be populated inside the modal in the `viewUser` AJAX function -->
<div class="row">
    <h5>Name: {{ $user->name }}</h5>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Roles:</strong>
        @foreach($user->roles as $role)
        <label class="badge bg-success">{{ $role->name }}</label>
        @endforeach
    </p>
    <!-- Add more user details if needed -->
</div>