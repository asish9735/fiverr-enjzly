<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends MX_Controller {

	function __construct()
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		ini_set('display_errors', 1);
			parent::__construct();
	}
	public function index(){
		$to='asish9735@gmail.com,swaminathan.pakhira@gmail.com';
		$data_parse=array();
		$template='forgot-password';
		$this->SendMailN('',$to,$template,$data_parse);
	}
	function phpmailer()
{
	$this->load->library("PhpMailerLib");
        $mail = $this->phpmailerlib->load();
	try {
		    //Server settings
		    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP

$mail->Host = "relay-hosting.secureserver.net"; 
$mail->SMTPAuth =false; 
$mail->SMTPSecure = false;
$mail->SMTPAutoTLS = false;                              // Enable SMTP authentication
		    $mail->Username = 'help@echodeveloper.com';                 // SMTP username
		    $mail->Password = 'Quw01250';                           // SMTP password
		    //$mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = '25';                                    // TCP port to connect to
		    //Recipients
		    $mail->setFrom('help@echodeveloper.com');
		    $mail->addAddress('asish9735@gmail.com');     // Add a recipient
		    $mail->addAddress('swaminathan.pakhira@gmail.com');     // Add a recipient
		   // $mail->addAddress('RECEIPIENTEMAIL02');               // Name is optional
		    $mail->addReplyTo('help@echodeveloper.com');
		    //$mail->addCC('cc@example.com');
		    //$mail->addBCC('bcc@example.com');

		    //Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = 'it working';
		    $mail->Body    = 'This is the HTML message body <b>in bold!</b>'.time();;
		    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		    $mail->send();
		    echo 'Message has been sent';
		} catch (Exception $e) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
	}
	public function SendMailN($from='', $to, $template, $data_parse,$type='html',$bcc=array(),$cc=array(),$data_subject=array()) {
 		$CI = get_instance();

		
		$config['protocol'] = 'mail';
		$config['smtp_host'] = 'localhost';//change this
		$config['smtp_user'] = 'help@echodeveloper.com';
		$config['smtp_pass'] = 'Quw01250';
		//$config['_smtp_auth '] = FALSE;
		$config['smtp_port'] = '25';
		//$config['smtp_crypto'] = 'tls';
		$config['smtp_timeout'] = '60';
		//$config['starttls'] = 'true';//not sure about this, if not work remove this and try once
		$config['newline'] = '\r\n';
		
		
		//dd($config,TRUE);
 		$mailemailID=get_option_value('admin_email');
		$name=get_option_value('website_name');
		$site_logo=theme_url().IMAGE.LOGO_NAME;
 		$contents = 'This is the HTML message body <b>in bold!</b>';
        $subject ='Here is the subject';
		//$to='asish9735@gmail.com';
       // $CI->load->library('email');
        //$CI->email->initialize($config);
        $CI->load->library('email', $config);
		$CI->email->from($config['smtp_user']);
		//$CI->email->reply_to($mailemailID, $name);

        $CI->email->to($to);
        //$CI->email->bcc('asish9735@gmail.com');
        $CI->email->subject($subject.$config['protocol']);
		$CI->email->set_mailtype($type);
		if($bcc){
			$CI->email->bcc($bcc);	
		}
		if($cc){
			$CI->email->bcc($cc);	
		}
        $CI->email->message($contents.'--'.time());
        $send=$CI->email->send();
        echo $CI->email->print_debugger();
    }
}
