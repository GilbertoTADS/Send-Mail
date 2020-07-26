<?php
	require "./PHPMailer/src/Exception.php";
	require "./PHPMailer/src/OAuth.php";
	require "./PHPMailer/src/POP3.php";
	require "./PHPMailer/src/PHPMailer.php";
	require "./PHPMailer/src/SMTP.php";
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;


class Mensagem{
	private $para = null;
	private $assunto = null;
	private $mensagem = null;
	public $status = array('codigo_status'=>null,'descricao_status'=>'');

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo,$valor){
		$this->$atributo=$valor;
	}
	public function mensagemValida(){
		if(empty($this->para)||empty($this->assunto)||empty($this->mensagem)){
			return false;
		}
		return true;
		
	}
}

$mensagem = new Mensagem();

$mensagem->__set('para',$_POST['para']);
$mensagem->__set('assunto',$_POST['assunto']);
$mensagem->__set('mensagem',$_POST['mensagem']);

//Tudo preenchido?
if(!$mensagem->mensagemValida()){
	echo 'Mensagem invalida';
	header('Location:index.php');
}


$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'COLOQUE SEU E-MAIL(ESTÁ CONFIGURADO COM GMAIL)';                 // SMTP username
    $mail->Password = 'COLOQUE SUA SENHA(GMAIL)';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('gilbertotads@gmail.com', 'Gilberto');
    $mail->addAddress($mensagem->__get('para'));// Add a recipient // Name is optional
    //$mail->addReplyTo('solrak733@gmail.com', 'destinatario de gilbertoTDAS');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessario utilizar um client com acesso a HTML para visualizar essa mensagem';

    $mail->send();
    $mensagem->status['codigo_status']=1;
    $mensagem->status['descricao_status']='E-mail enviado com sucesso';
} catch (Exception $e) {
	 $mensagem->status['codigo_status']=2;
    $mensagem->status['descricao_status']='Não foi possivel enviar este e-mail,por favor tente mais tarde. Detalhes do erro: '.$mail->ErrorInfo;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>App Mail Send</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="imagens/logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?php
					if($mensagem->status['codigo_status']==1){
					?>
						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p> 
								<?=$mensagem->status['descricao_status'] ?>
							</p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>
					<?php } ?>
					<?php
					if($mensagem->status['codigo_status']==2){
					?>
						<div class="container">
							<h1 class="display-4 text-dander">Ops!</h1>
							<p> <?=$mensagem->status['descricao_status'] ?> </p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>
					<?php }?>
				</div>
			</div>
	</div>
</body>
</html>