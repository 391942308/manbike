<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>车位数量提交界面--车辆实时调度系统v0.3</title>

    <!-- Bootstrap -->
    <link href="/manbike0.3/Public/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/manbike0.3/Public/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/manbike0.3/Public/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/manbike0.3/Public/gentelella/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="/manbike0.3/Public/gentelella/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="/manbike0.3/Public/gentelella/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="/manbike0.3/Public/gentelella/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="/manbike0.3/Public/gentelella/vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="/manbike0.3/Public/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/manbike0.3/Public/gentelella/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
      

        

        <!-- page content -->
        <div class="right_col" role="main" style="margin-left: 0px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>监测模拟数据提交</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                 
                  <div class="x_content">
                    <br />
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" action="http://116.62.171.54:8080/manbike0.3/index.php/Home/Index/gateway">

                      <input type="hidden" name="flag" value="1" />
					  
					  
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="inout">数据<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          	<textarea name="inout" value="" style="width:700px;height:600px;" >[{"timestamp":"2017-09-12T08:11:12Z","type":"iBeacon","mac":"1918FC051F60","bleName":"","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10170,"ibeaconMinor":5375,"rssi":-83,"ibeaconTxPower":-59,"battery":0},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"1918FC03BAB6","bleName":"","rssi":-70,"rawData":"1E1686435C5A5B3F5B5B4243A758E1ED60DD74537C494F039E5B69A4A4A45B"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"1918FC051F70","bleName":"","rssi":-68,"rawData":"1E168643F7F1F094F0F1E9E80CF5EF80CB76DFF8D74AE40535F0FA0F0F0FF0"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"1F5E650EE666","bleName":"","rssi":-60,"rawData":"1EFF060001092000E294B7C0BA35D7D8ADE575E32D811B1BF427FC3007A8E4"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"1918FC051F5A","bleName":"","rssi":-74,"rawData":"1E1686436C6A6B0F6B6A7273976E743150ED44634CD17E6AAE6B619494946B"},{"timestamp":"2017-09-12T08:11:08Z","type":"Unknown","mac":"1918FC03BAC5","bleName":"","rssi":-70,"rawData":"1E1686438D8B8AEE8A8A93927689304FB10CA582AD32762B4F8AB87575758A"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"1918FC051F6C","bleName":"","rssi":-72,"rawData":"1E168643A4A2A3C7A3A2BABB5FA6BCCF98258CAB8419B75966A3A95C5C5CA3"},{"timestamp":"2017-09-12T08:11:10Z","type":"Unknown","mac":"50F14ABD1B62","bleName":"","rssi":-74,"rawData":"0201060CFF010350F14ABD1B620664010302E7FE"},{"timestamp":"2017-09-12T08:11:07Z","type":"Unknown","mac":"1918FC051F5D","bleName":"","rssi":-81,"rawData":"1E1686437375741074756D6C88716B294FF25B7C53CE6174B174468B8B8B74"},{"timestamp":"2017-09-12T08:11:15Z","type":"Unknown","mac":"1918FC051F6E","bleName":"","rssi":-83,"rawData":"1E1686433D3B3A5E3A3B2322C63F255401BC15321D802ECAFF3A30C5C5C53A"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"1918FC051F71","bleName":"","rssi":-81,"rawData":"1E168643EFE9E88CE8E9F1F014EDF799D36EC7E0CF52FC1C2DE8E2171717E8"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"1918FC051F63","bleName":"","rssi":-74,"rawData":"1E168643E5E3E286E2E3FBFA1EE7FD81D964CDEAC558F61F27E2E81D1D1DE2"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"E94EC4436BFB","bleName":"NokeLock","rssi":-63,"rawData":"09094E6F6B654C6F636B0303E7FE09FF0102E94EC4436BFB"},{"timestamp":"2017-09-12T08:11:16Z","type":"Unknown","mac":"C9F028FD8948","bleName":"mb_SIn9KPDJ","rssi":-72,"rawData":"0201060C096D625F53496E394B50444A"},{"timestamp":"2017-09-12T08:11:16Z","type":"iBeacon","mac":"1918FC051F65","bleName":"","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10170,"ibeaconMinor":5372,"rssi":-75,"ibeaconTxPower":-59,"battery":0},{"timestamp":"2017-09-12T08:11:14Z","type":"iBeacon","mac":"F5F82ED015DA","bleName":"MiniBeacon_00206","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10169,"ibeaconMinor":28834,"rssi":-27,"ibeaconTxPower":-59,"battery":100},{"timestamp":"2017-09-12T08:11:12Z","type":"iBeacon","mac":"1918FC04559C","bleName":"","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10170,"ibeaconMinor":7605,"rssi":-68,"ibeaconTxPower":-59,"battery":0},{"timestamp":"2017-09-12T08:11:16Z","type":"iBeacon","mac":"1918FC051F64","bleName":"","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10170,"ibeaconMinor":5369,"rssi":-76,"ibeaconTxPower":-59,"battery":0},{"timestamp":"2017-09-12T08:11:15Z","type":"Unknown","mac":"1918FC051F61","bleName":"","rssi":-67,"rawData":"1E168643E6E0E185E1E0F8F91DE4FE80DA67CEE9C65BF51024E1EB1E1E1EE1"},{"timestamp":"2017-09-12T08:11:14","type":"Gateway","mac":"0CEFAFCFEEC1","gatewayFree":92,"gatewayLoad":0.34}]</textarea>
                        </div>
                      </div>
					  

                      
                     
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          
						  <button class="btn btn-primary" type="reset">重置</button>
                          <button type="submit" class="btn btn-success">提交</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>

            
        <!-- /page content -->

        
      </div>
    </div>

    <!-- jQuery -->
    <script src="/manbike0.3/Public/gentelella/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/manbike0.3/Public/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/manbike0.3/Public/gentelella/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/manbike0.3/Public/gentelella/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="/manbike0.3/Public/gentelella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="/manbike0.3/Public/gentelella/vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/manbike0.3/Public/gentelella/vendors/moment/min/moment.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="/manbike0.3/Public/gentelella/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="/manbike0.3/Public/gentelella/vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="/manbike0.3/Public/gentelella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="/manbike0.3/Public/gentelella/vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="/manbike0.3/Public/gentelella/vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="/manbike0.3/Public/gentelella/vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="/manbike0.3/Public/gentelella/vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="/manbike0.3/Public/gentelella/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="/manbike0.3/Public/gentelella/vendors/starrr/dist/starrr.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="/manbike0.3/Public/gentelella/build/js/custom.min.js"></script>
	
  </body>
</html>