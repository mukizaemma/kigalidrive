<div class="modal fade" id="addFaqModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Question</label>
                    <input type="text" name="question" class="form-control" required maxlength="500">
                </div>
                <div class="mb-3">
                    <label class="form-label">Answer</label>
                    <textarea name="answer" class="form-control" rows="5" required maxlength="5000"></textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="addFaqActive" checked>
                            <label class="form-check-label" for="addFaqActive">Visible on website</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save FAQ</button>
            </div>
        </form>
    </div>
</div>
