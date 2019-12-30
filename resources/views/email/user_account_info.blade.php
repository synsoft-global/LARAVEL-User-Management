<!DOCTYPE html>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap" rel="stylesheet">

<style>
    *{font-family: 'Open Sans', sans-serif;}
    body{font-family: 'Open Sans', sans-serif;color:#74787e;padding:0;margin:0}
    table{font-family: 'Open Sans', sans-serif;}
    * {
        word-break: break-word;
    }
</style>
<html>

<body style="background: #fff;">

    <div style="width:100%;padding: 50px 0;background: #f5f8fa;text-align: center"><a href="{{url('/')}}" style="text-decoration: none;"><img src="{{url('public/theme/img/logo.png')}}" width="150px" alt="" style="width: 150px;"></a></div>

    <table style="text-align: center;margin: auto;padding: 10px;max-width: 600px;width: 100%;word-break: break-all;background: #fff;border-collapse: collapse;">
        <tbody>
                 <tr>
                <td>
                    <table style="text-align: left;border: 0;padding: 0;width: 100%;border-bottom-width: 0;background: #fff;">
                        <tbody style="">
                            <tr>
                                <td style="font-size: 18px;text-align: left;word-break: break-word;font-weight: 700;padding:20px 0"><strong>{{__('messages.mail.hello')}} {{$name}}</strong></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;text-align: left;word-break: break-word; font-weight: 400;">
                                    <p style="font-size: 15px;
                                      margin: 0;
                                      padding: 10px 0;
                                      line-height: 28px;">
                                      
                                      {!!__('messages.mail.account_has_been_created')!!}
                                    </p>
                                </td>

                            </tr>


                        
                            <tr>
                                <td style="font-size: 15px;text-align: left; font-weight: 400;">
                                    <p style="font-size: 15px;
                                 margin: 0;
                                 padding: 10px 0;
                                 line-height: 28px;"> <strong>Email</strong>: {{$email}}</p>
                                </td>
                            </tr>   
                             <tr>
                                <td style="font-size: 15px;text-align: left; font-weight: 400;">
                                    <p style="font-size: 15px;
                                 margin: 0;
                                 padding: 10px 0;
                                 line-height: 28px;"> <strong>Password</strong>: {{$password}}</p>
                                </td>
                            </tr> 
                            <tr>
                                <td style="font-size: 15px;text-align: left; font-weight: 400;">
                                    <p style="font-size: 15px;
                                 margin: 0;
                                 padding: 10px 0;
                                 line-height: 28px;"> <strong>Click here for login:</strong>: {{url('login')}}</p>
                                </td>
                            </tr> 
                </tbody>
                </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table style="text-align: left;margin: auto;width: 100%;">
                        <thead>
                            <tr>
                                <td>
                                    <div style="word-break: break-word;
                              color: #74787e;
                              font-weight: 400;
                              font-size: 16px;
                              line-height: 36px;">{!!__('messages.mail.regards_ci_reporting')!!}</div>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
