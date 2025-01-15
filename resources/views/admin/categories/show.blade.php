<div class="row">
    <p><strong>Name:</strong> {{ $category->name }}</p>
    <p><strong>Created By:</strong> {{ $category->creator->name ?? 'Unknown' }}</p>
</div>