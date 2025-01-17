<div class="row">
    <p><strong>Name:</strong> {{ $branch->name }}</p>
    <p><strong>Location:</strong> {{ $branch->location }}</p>
    <p><strong>Created By:</strong> {{ $branch->creator->name ?? 'Unknown' }}</p>
</div>