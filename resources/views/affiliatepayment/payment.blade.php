@extends('layouts.app', ['title' => __('Affiliate Payment Management')])

@section('content')
    @include('affiliatepayment.partials.header', ['title' => __('Affiliate Payment')])   

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Payment Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('affiliatepayment.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('affiliatepayment.update', $affiliatepayment) }}" autocomplete="off">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id" value="{{$affiliatepayment->id}}">
                            <h6 class="heading-small text-muted mb-4">{{ __('Affiliate Payment information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $affiliatepayment->name) }}" readonly>

                                    
                                </div>
                               
                                <div class="form-group{{ $errors->has('commission') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Commission') }}</label>
                                    <input type="text" name="commission" id="input-name" class="form-control form-control-alternative{{ $errors->has('commission') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('commission', $affiliatepayment->commission) }}" readyonly>

                                </div>
                                
                                <div class="form-group{{ $errors->has('payment_method') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Payment Methods') }}</label>
                                   <select name="payment_method" id="payment_method" class="form-control form-control-alternative{{ $errors->has('payment_method') ? ' is-invalid' : '' }}" required>
                                        <option value=""> -- </option>
                                        <option value="Paypal">Paypal </option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cheque">Cheque </option>
                                    @if ($errors->has('payment_method'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('payment_method') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                    
                                </div>
                                
                                <div class="form-group{{ $errors->has('transaction') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Transaction') }}</label>
                                    <input type="text" name="transaction" id="input-name" class="form-control form-control-alternative{{ $errors->has('transaction') ? ' is-invalid' : '' }}" placeholder="{{ __('Transaction') }}" value="{{ old('commission', $affiliatepayment->transaction) }}">
                                     @if ($errors->has('transaction'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('transaction') }}</strong>
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
        
        @include('layouts.footers.auth')
    </div>
@endsection