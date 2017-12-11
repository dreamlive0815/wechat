<?php

namespace Util;

class ViewUtil
{
    function head( $title = '' ) { ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>

    <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="modal fade" tabindex="-1" role="dialog" id="my-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="my-modal-title"></h4>
                </div>
                <div class="modal-body" id="my-modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#my-modal">确定</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
    function showModal(title, body)
    {
        $('#my-modal-title').html(title);
        $('#my-modal-body').html(body);
        $('#my-modal').modal();
    }

    function refresh()
    {
        window.location.reload();
    }

    function callAPI(args)
    {
        if(typeof args != 'object') return;
        var url = args.url || 'api.php';
        var data = args.data || { controller : '' };
        var success = args.success || function(data) {
            console.log('调用API成功');
            console.log(data);
        };
        var fail = args.fail || function(code, msg) {
            console.log('调用API失败');
            showModal('调用API失败', code + ' ' + msg);
        }
        var processData = args.hasOwnProperty('processData') ? args.processData : true;
        var true_args = {
            url : url,
            type : 'POST',
            processData : processData,
            data : data,
            success : function(response) {
                data = JSON.parse(response);
                if(data.errorCode == 0)
                    success(data.data);
                else
                    fail(data.errorCode, data.errorMsg);
            },
            error : function(request, err) {
                console.log(request.responseText);
                fail(err, 2);
            }
        };
        console.log(true_args);
        $.ajax(true_args);
    }
    </script>
<?php    
    }

    function foot() { ?>
</div>
</body>
</html><?php
    }
}