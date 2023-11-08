
<!-- Modal -->
<div class="modal fade" id="folderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Folder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('question_answer_voice.make_folder') }}" autocomplete="off" enctype="multipart/form-data">
            @csrf
            
            <div class="pl-lg-4">
                
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="name">{{ __('Folder Name') }}</label>
                    <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Folder name') }}" value="{{old('name')}}" required>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
      </div>
   </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="folderUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rename Folder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('question_answer_voice.update_folder') }}" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="" name="edit_folder_id" id="edit_folder_id">
            <div class="pl-lg-4">
                
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="name">{{ __('Folder Name') }}</label>
                    <input type="text" name="name" id="fname" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Folder name') }}" value="{{old('name')}}" required>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
      </div>
   </div>
  </div>
</div>