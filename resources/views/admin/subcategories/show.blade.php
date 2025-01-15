<div class="row">
    <p><strong>Name:</strong> {{ $subcategory->name }}</p>
    <p><strong>Category:</strong> {{ $subcategory->category->name ?? 'Unknown' }}</p>
    <p><strong>Created By:</strong> {{ $subcategory->createdBy->name ?? 'Unknown' }}</p>
    <p><strong>Updated By:</strong> {{ $subcategory->updatedBy->name ?? 'Unknown' }}</p>
</div>