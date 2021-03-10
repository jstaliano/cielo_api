/*exemplo da comunidade (Foruns)

fonte:https://pt.stackoverflow.com/questions/286570/imagem-em-e-mail-html-no-phpmailer
Utilizando o método $mail->AddEmbeddedImage:
$mail->AddEmbeddedImage('img/logo.jpg', 'logo_ref');
Na tag <img> insere: src='cid:logo_ref'.
Dessa forma, a imagem ficará embutida no e-mail.

***********************
fonte: https://qastack.com.br/programming/3708153/send-email-with-phpmailer-embed-image-in-body
$mail->AddEmbeddedImage('img/2u_cs_mini.jpg', 'logo_2u');
e na <img>tag colocarsrc='cid:logo_2u'

De acordo com o Manual do PHPMailer , a resposta completa seria:

$mail->AddEmbeddedImage(filename, cid, name);
//Example
$mail->AddEmbeddedImage('my-photo.jpg', 'my-photo', 'my-photo.jpg ');

Caso de uso:

$mail->AddEmbeddedImage("rocks.png", "my-attach", "rocks.png");
$mail->Body = 'Embedded Image: <img alt="PHPMailer" src="cid:my-attach"> Here is an image!';

Se você deseja exibir uma imagem com um URL remoto:

$mail->addStringAttachment(file_get_contents("url"), "filename");


*/