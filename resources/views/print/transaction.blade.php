@include('layouts.header')
<style>
   table, th, td {
    border:1px solid black;
   }
</style>
                                       <center>
    
    <table>
        <tr><h1>HIFI FINTECH</h1></tr>
        <tr><p> need to show success, failed, pending status with icons/image</p></tr>
        <tr>
            <th>Date | Time</th>
            <td>12-12-2023 | 9:17:36 AM</td>
        </tr>
        <tr>
            <th>Mobile Number</th>
            <td>6383224535</td>
        </tr>
        <tr>
            <th>Shop Name | Contact Number</th>
            <td>HIFI Mobiles, Karumathampatti | 6383224535</td>
        </tr>
        <tr>
            <th>Bank Name | IFSC CODE</th>
            <td>Canara Bank | CNRB0003437</td>
        </tr>
        <tr>
            <th>Account Number | Payment Mode</th>
            <td>3437101002021 | IMPS</td>
        </tr>
        <tr>
            <th>Transaction ID</th>
            <td>8818288189289</td>
        </tr>
        <tr>
            <th>Account Holder Name</th>
            <td><h5>Hifi fintech</h5></td>
        </tr>
        <tr>
            <th>Amount</th>
            <td><h5>5000</h5></td>
        </tr>
        <tr>
            <th>Transaction Status</th>
            <td><h5>SUCCESS</h5></td>
        </tr>
        <tfoot>
            <th colspan="2"><center>Computer Generated Receipt No Need of Signature/Seal</center></th>
        </tfoot>           
                   
                  </table>
        
</center>
@include('layouts.footer')
<script>
    // $(document).ready(function(){
    //     setTimeout(function () {
    //         window.print();
    //     }, 500);
    //     window.onafterprint = function(){
    //         window.location.replace("{{route('report')}}");
    //     }
    // });
</script>