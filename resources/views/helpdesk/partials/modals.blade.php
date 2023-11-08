
<!-- Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="replyModal">Reply</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('helpdesk.reply') }}" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="" name="message_id" id="message_id">
            <input type="hidden" value="" name="user_id" id="user_id">
            <input type="hidden" value="" name="role_id" id="role_id">
            <input type="hidden" value="" name="subject" id="subject">
            <div class="pl-lg-4">
                
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="name">{{ __('Reply') }}</label>
                    <textarea name="message" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Reply') }}" value="{{old('name')}}"></textarea>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">{{ __('Send') }}</button>
                </div>
            </div>
        </form>
      </div>
   </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ViewModal" tabindex="-1" role="dialog" aria-labelledby="ViewModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ViewModal">View</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="pl-lg-4">
                
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="name">{{ __('Messge') }}</label>
                    <p id="message"></p>
                    <a id="attachment_id" target='_blank'>Attachment</a>
                    
                </div>
                
                
            </div>
        </form>
      </div>
   </div>
  </div>
</div>