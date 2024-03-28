@extends('layouts.master')
@section('content')
<div style="margin-top:100px;"></div>
<div class="text-center alert alert-success" style="display:none;"></div>
<div class="alert alert-danger" style="display:none;"></div>
@if(session('dataN'))
    <div class="text-center alert alert-success">Wallet Top Uped Successfully</div>
@endif
@if(session('failed'))
    <div class="alert alert-danger"> {{ session('failed') }}</div>
@endif

<div class="modal fade payout-model" id="wallet_top_up_model" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Retailer Wallet</h5>
                                    <button type="button" id="payout_add_or_verify_account_model_close" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div style="margin-top:25px;">
                                    <input type="hidden" name="w_user_code" id="w_user_code" value="" />
                                        <div class="mb-3 form-inputs">
                                            <label for="exampleFormControlInput1" class="form-label bank_ifsc">Shop Name</label>
                                            <input type="text" disabled name="w_shop_name" id="w_shop_name" value=""
                                                class="form-control bank_ifsc"
                                                autocomplete="off">
                                                <p id="payout_ifsc_code_check"></p>
                                        </div>
                                        <div class="mb-3 form-inputs">
                                            <label for="exampleFormControlInput1" class="form-label">Mobile Number</label>
                                            <input type="text" name="w_mobile_number" id="w_mobile_number" disabled value="">
                                                <p id="account_number_check"></p>
                                        </div>
                                        <div class="mb-3 form-inputs">
                                            <label for="exampleFormControlInput1" class="form-label">Current Balance</label>
                                            <input type="text" name="w_current_balance" id="w_current_balance" disabled value="">
                                                <p id="account_number_check"></p>
                                        </div>
                                        <div class="mb-3 form-inputs">
                                            <label for="exampleFormControlInput1" class="form-label">Hold Balance</label>
                                            <input type="text" name="w_hold_balance" id="w_hold_balance" disabled value="">
                                                <p id="account_number_check"></p>
                                        </div>
                                        <label for="exampleFormControlInput1" class="form-label">Action Type</label>
                                        <select style="margin-bottom:15px" class="form-select"
                                            aria-label="Default select example" id="wallet_action_type">
                                            <option disabled>Select Action Type</option>
                                            <option selected value="credit">Credit</option>
                                            <option value="debit">Debit</option>
                                            <option value="hold">Hold</option>
                                            <option value="unhold">Un-Hold</option>
                                        </select>
                                        <p id="payout_bank_list_check"></p>
                                        <div class="mb-3 form-inputs">
                                            <label for="exampleFormControlInput1"
                                                class="form-label account_holder_name">Amount</label>
                                            <input type="text" name="account_holder_name" autofocus required
                                                minlength="3" maxlength="6" placeholder="Enter Amount" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                class="form-control account_holder_name" id="w_amount"
                                                autocomplete="off">
                                                <small>Available balance: {{Auth::user()->awards}}</small>
                                                <p id="name_check"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" style="width:100%;"
                                        class="btn btn-secondary" id="wallet_submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
<table id="wallet_topup" class="display nowrap" style="width:100%;">
        <thead>
            <tr>
                <th style="display:none;"></th>
                <th>S.No</th>
                <th>Shop Name</th>
                <th>Mobile Number</th>
                <th>Wallet Balance</th>
                <th>Hold Balance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @if(session('dataN'))
        @foreach (session('dataN') as $index => $v)
            <tr>
                <td style="display:none;">{{$v->door_code}}</td>
                <td>{{$index+1}}</td>
                <td>{{$v->shop_name}}</td>
                <td>{{$v->mobile_number}}</td>
                <td>{{$v->awards}}</td>
                <td>{{$v->awards_hold}}</td>
                <td><button id="top_up" type="button">Wallet</button></td>
            </tr>
        @endforeach
        @else
        @foreach ($data as $index => $v)
            <tr>
                <td style="display:none;">{{$v->door_code}}</td>
                <td>{{$index+1}}</td>
                <td>{{$v->shop_name}}</td>
                <td>{{$v->mobile_number}}</td>
                <td>{{$v->awards}}</td>
                <td>{{$v->awards_hold}}</td>
                <td><button id="top_up" type="button">Wallet</button></td>
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>

@endsection