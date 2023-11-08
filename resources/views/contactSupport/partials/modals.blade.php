<!-- Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="replyModalLongTitle">Support Reply</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form method="post" action="{{route('support.update')}}">
          <div class="modal-body">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="userid">
                <div class="form-group">
                    <label class="form-control-label ">Topic</label>
                    <p class="topic"></p>
                </div>
                <div class="form-group">
                    <label class="form-control-label ">Description</label>
                    <p class="query"></p>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Reply</label>
                    <textarea name="shyamtrusteditor"></textarea>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
          </div>
        </form>
    </div>
  </div>
</div>
