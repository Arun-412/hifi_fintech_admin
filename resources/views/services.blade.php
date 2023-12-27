@extends('layouts.master')
@section('content')
<section style="margin-top: 110px;margin-bottom: 40px;padding: 0px 30px;">
    <div class="container-fluid">
    <h2>Service Providers</h2>
    <div class="row mb-5">
            <div class="col-sm-12 col-md-12 col-xs-12">
                <div class="payout-box">
                    <h4 style="float:left;">Providers list</h4>
                    <table id="providers_list_table" class="table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Mobile</th>
                                <th scope="col">Status</th>
                                <!-- <th scope="col">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['providers'] as $index => $v)
                            <tr><input type="hidden" name="provider_cd" value="{{$v->room_code}}">
                                <td>{{$index+1}}</td>
                                <td>{{$v->room_name}}</td>
                                <td>{{$v->room_email}}</td>
                                <td>{{$v->room_mobile}}</td>
                                @if($v->room_status == 'HFY')
                                <td>Activated</td>
                                <!-- <td><button>Deactivate</button></td> -->
                                @else
                                <td>Deacivated</td>
                                <!-- <td><button>Activate</button></td> -->
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-xs-12">
                <div class="payout-box">
                    <h4 style="float:left;">Service list</h4>
                    <table id="services_list_table" class="table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Provider Name</th>
                                <th scope="col">Service Name</th>
                                <th scope="col">Status</th>
                                <!-- <th scope="col">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['services'] as $index => $v)
                            <tr> <input type="hidden" name="service_cd" value="{{$v->counter_code}}">
                                <td>{{$index+1}}</td>
                                @if($v->room_code == 'HFAPVR23EKO011023')
                                <td>Eko India Financial Services</td>
                                @endif
                                <td>{{$v->counter_name}}</td>
                                @if($v->counter_status == 'HFY')
                                <td>Activated</td>
                                <!-- <td><button>Deactivate</button></td> -->
                                @else
                                <td>Deacivated</td>
                                <!-- <td><button>Activate</button></td> -->
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection