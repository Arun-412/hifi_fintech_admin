@extends('layouts.master')
@section('content')
<section style="margin-top: 94px;margin-bottom: 40px;padding: 0px 30px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-xs-12">
                <div class="payout-box">
                    <h4 style="float:left;">Report</h4>
                    <form class="report-form">
                        @csrf
                        <input type="date" name="from date" id="" placeholder="From Date">
                        <input type="date" name="to date" id="" placeholder="To Date">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Select Service</option>
                            <option value="1">All</option>
                            <option value="2">Payout</option>
                        </select>
                        <button class="btn" type="button" value="search_report">Search Report</button>
                    </form>
                    <table id="report_data" class="table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Date - Time</th>
                                <th scope="col">Transaction ID</th>
                                <th scope="col">Mobile Number</th>
                                <th scope="col">Name</th>
                                <th scope="col">Bank Name</th>
                                <th scope="col">Account Number</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01-Dec-2023 - 12:24:32 PM</td>
                                <td>HFPTXaLie000012</td>
                                <td>6383224535</td>
                                <td>Hifi Money</td>
                                <td>HDFC Bank</td>
                                <td>2003020001020</td>
                                <td>₹12500</td>
                                <td>Success</td>
                                <td><button class="print-btn" type="button"><a href="{{route('print_transaction')}}"><i class="bi bi-printer-fill"></i></a></button>
                                    <button class="print-btn" type="button"><i
                                            class="bi bi-arrow-clockwise"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection