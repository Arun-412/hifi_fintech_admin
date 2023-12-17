@extends('layouts.master')
@section('content')
<section style="margin-top: 110px;margin-bottom: 40px;padding: 0px 30px;">
    <div class="container-fluid">
        <div class="row">
           
        <!-- <h2>Payout page</h2> -->
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#payout_add_rules_model">
                           Add Rule
                        </button> -->
        <div class="modal fade payout-model" id="payout_add_rules_model" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Payout Rules</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div style="margin-top:25px;">
                                    <form action="test" method="post">
                                        <input type="text" name="" placeholder="From Amount" id="">
                                        <input type="text" name="" placeholder="To Amount" id="">
                                        <input type="text" name="" placeholder="API Charge" id="">
                                       
                                
                                        <select name="" id="">
                                            <option value="Charge Type" Selected disabled>User Charge Type</option>
                                            <option value="percentage">Percentage %</option>
                                            <option value="ruppes">Ruppess ₹</option>
                                            
                                        </select>
                                        <input type="text" name="" placeholder="User Charge" id="">
                                    </div>
                                </div>
                                <div class="modal-footer">
                               
                                    <button type="submit" style="width:100%;" class="btn btn-secondary"
                            >Add Rule</button>
                            </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                            aria-label="Close">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xs-12">
                <div class="payout-box">
                    <h4 style="float:left;">Payout Charges</h4>
                 
                    <table id="payout_rules_table" class="table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">S.NO</th>
                               
                                <th scope="col">From Amount</th>
                                <th scope="col">To Amount</th>
                                <th scope="col">API Charge</th>
                                
                               
                                <th scope="col">User Charge</th>
                                <th scope="col">Status</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        @if($data)
                @foreach( $data as $index => $v)
                <tr>
                    <td>{{$index+1}}</td>
                                <td>₹{{number_format((float)$v->from_amount, 2, '.', '')}}</td>
                                <td>₹{{number_format((float)$v->to_amount, 2, '.', '')}}</td>
                                <td>₹{{number_format((float)$v->room_charge, 2, '.', '')}}</td>
                               @if($v->charge_type == 'HFR')
                               <td>₹{{$v->charge}}</td>
                               @else
                               <td>%{{$v->charge}}</td>
                               @endif
                               
                                @if($v->charge_status == 'HFY')
                                <td>Activated</td>
                                @else
                                <td>Deactivated</td>
                                @endif               
                            </tr>
                @endforeach
            @endif
                            
                        </tbody>
                    </table>
                </div>
            </div>
</div>
</div>
</section>
@endsection