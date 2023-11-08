@extends('layouts.app', ['title' => __('Image')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Images') }}</h3>
                            </div>
                            <div class="col-12 text-right">   
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#folderModal" class="btn btn-sm btn-primary">{{ __('Make Folder') }}</a>
                                <a href="{{ route('image.create') }}" class="btn btn-sm btn-primary">{{ __('Add Image') }}</a>
                                <a href="{{ route('image.files_upload') }}" class="btn btn-sm btn-primary">{{ __('Upload Multiple Image') }}</a>
                                <a href="{{ route('image.download') }}" class="btn btn-sm btn-primary">{{ __('Sample Download') }}</a>
                                <a href="{{ route('image.bulk_upload') }}" class="btn btn-sm btn-primary">{{ __('Bulk Upload') }}</a>
                            </div>
                            <div class="col-4">
                                <form method="get">
                                <div class="form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="parent_id">{{ __('Folder Name') }}</label>
                                    <select name="folder_id" id="folder_id" class="form-control form-control-alternative{{ $errors->has('folder_id') ? ' is-invalid' : '' }}" required>
                                        <option value=""> -- </option>
                                    @foreach($folders as $res)
                                         <option value="{{$res->id}}" @if(isset($_GET['folder_id']) && $_GET['folder_id']==$res->id) selected @endif>{{$res->folder_name}}</option>
                                    @endforeach
                                    
                                    @if ($errors->has('folder_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('folder_id') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                </div>
                               
                            </div>
                            <div class="col-8 text-right">   
                               @php
                                if(isset($_GET['folder_id'])){
                                    $folder_id=$_GET['folder_id'];
                                }else{
                                    $folder_id='';
                                }
                                @endphp
                                <a class="btn btn-sm btn-primary" href="{{ route('image.export') }}?folder_id=<?=$folder_id?>">Export</a>
                                <button type="submit" class="btn btn-sm btn-primary">{{ __('Filter') }}</button>
                                </form>
                                <a href="{{ route('image.index') }}" class="btn btn-sm btn-primary">{{ __('Clear Filter') }}</a>
                            </div>
                            <div class="col-4 text-right">   
                              
                            </div>
                            <div class="col-8 text-right">    
                                
                                <div class="row">
                                     <div class="col-9">   
                                      
                                    </div>
                                    <div class="col-1">   
                                      <a href="javascript:void(0)" data-toggle="modal" data-target="#folderUpdateModal" class="btn btn-sm btn-primary" onclick="renameFolder()" id="rename_folder">{{ __('Rename Folder') }}</a>
                                    </div>
                                  
                                    <div class="col-2">   
                                        
                                
                                        <!--<a href="javascript:void(0)" data-toggle="modal" data-target="#folderUpdateModal" class="btn btn-sm btn-primary" onclick="deleteFolder()" id="delete_folder">{{ __('Delete Folder') }}</a>-->
                                        <form action="{{ route('image.delete_folder') }}" id="deleteForm" method="post">
                                            @csrf
                                            <!--@method('delete')-->
                                            <input type="hidden" value="" name="folder_id" id="delete_folder">
                                            <button type="button" class="btn btn-sm btn-primary" id="folder_delete" onclick="deleteFolder()">
                                                {{ __('Delete Folder') }}
                                            </button>
                                        </form>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('ID') }}</th>
                                    <th scope="col">{{ __('Name') }}</th> 
                                    <th scope="col">{{ __('Relative Path') }}</th>
                                    <th scope="col">{{ __('Url') }}</th>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <!--<th scope="col">{{ __('Status') }}</th>-->
                                    <th scope="col" class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($images as $key=> $image)
                            
                                    <tr>
                                        <td><a href="{{ route('image.edit', $image) }}">{{ $key+1 }}</a></td>
                                        <td>{{ $image->name }}</td>
                                        <td id="relative_path{{$image->id}}">{{ $image->relative_image_path }}</td>
                                        <td>{{ $image->path }}</td>
                                        <td>
                                            <img width="50px" height="50px" src="{{ asset($image->relative_image_path) }}">
                                        </td>
                                        <!--@if($image->status==1)-->
                                        <!-- <td>-->
                                        <!--    <label class="switch">-->
                                        <!--        <input type="checkbox" id="togBtn" onclick="changeStatus('imageStatus','{{$image->id}}','0')" checked >-->
                                        <!--        <div class="slider round"></div>-->
                                        <!--    </label>-->
                                        <!--</td>-->
                                        <!--@else-->
                                        <!--<td>-->
                                        <!--    <label class="switch">-->
                                        <!--        <input type="checkbox" id="togBtn" onclick="changeStatus('imageStatus','{{$image->id}}','1')" >-->
                                        <!--        <div class="slider round"></div>-->
                                        <!--    </label>-->
                                        <!--</td>-->
                                        <!--@endif-->
                                        <td class="text-right">
                                            <div class="dropdown">
                                               
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" onclick="copyToClipboard('relative_path{{$image->id}}')">Copy Text</a>
                                                    <a class="dropdown-item" href="{{ route('image.edit', $image) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('image.destroy', $image) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this image?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                       @if(count($images))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $images->appends(Request::all())->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any image') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div> 
        @include('image.partials.modals')
    
        @include('layouts.footers.auth')
       
    </div>
@endsection
<script>
    function copyToClipboard(id) {
        // alert(id);
        var copyText= $("#"+id).html();
       // alert(copyText);
      // Get the text field
       var copyText1 = document.getElementById(id);
        console.log(copyText1);
      // Select the text field
       //copyText.select();
    //   copyText.setSelectionRange(0, 99999); // For mobile devices
    
      // Copy the text inside the text field
      navigator.clipboard.writeText(copyText);
    
      // Alert the copied text
      //alert("Copied the text: " + copyText);
    }
   function renameFolder(){
        var folder_id = document.getElementById('folder_id');
        var value = folder_id.value;
        var text = folder_id.options[folder_id.selectedIndex].text;
        if(value==''){
            alert('Please select folder name');
            
        }
        console.log(value);
        console.log(text);
        var edit_folder_id= document.getElementById('edit_folder_id');
        edit_folder_id.value=value;
        var fname= document.getElementById('fname');
        fname.value=text;
   }
   
   function deleteFolder(){
        var folder_id = document.getElementById('folder_id');
        var value = folder_id.value;
        var text = folder_id.options[folder_id.selectedIndex].text;
        if(value==''){
            alert('Please select folder name');
            
        }else{
             if(confirm("Do you really want to delete this folder?")){
                var delete_folder= document.getElementById('delete_folder');
                delete_folder.value=value;
               
                 document.getElementById('deleteForm').submit();
             }
        }
   }
   
    </script>