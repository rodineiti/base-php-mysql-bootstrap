<?php

namespace Src\Support;

use Source\Core\Database\Connect;
use stdClass;
use Source\Support\Message;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Email
 * @package Source\Core\Email
 */
class Email
{
    /**
     * @var PHPMailer
     */
    private $mail;

    /**
     * @var stdClass
     */
    private $data;

    /**
     * @var Message
     */
    private $message;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->message = new Message();
        $this->mail = new PHPMailer(true);
        $this->data = new stdClass();

        //$this->mail->SMTPDebug = SENDBLUE_CONFIG["debug];
        $this->mail->isSMTP();
        $this->mail->isHTML(SENDBLUE_CONFIG["html"]);
        $this->mail->setLanguage(SENDBLUE_CONFIG["lang"]);

        $this->mail->SMTPAuth   = SENDBLUE_CONFIG["auth"];
        $this->mail->SMTPSecure = SENDBLUE_CONFIG["secure"];
        $this->mail->CharSet = SENDBLUE_CONFIG["charset"];

        $this->mail->Host       = SENDBLUE_CONFIG["host"];
        $this->mail->Username   = SENDBLUE_CONFIG["user"];
        $this->mail->Password   = SENDBLUE_CONFIG["password"];
        $this->mail->Port       = SENDBLUE_CONFIG["port"];
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $recipent_name
     * @param string $recipent_email
     * @return Email
     */
    public function add(string $subject, string $body, string $recipent_name, string $recipent_email): Email
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipent_name = $recipent_name;
        $this->data->recipent_email = $recipent_email;

        return $this;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     * @return Email
     */
    public function attach(string $filePath, string $fileName): Email
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    /**
     * @param string $from_name
     * @param string $from_email
     * @return bool
     */
    public function send(string $from_name = SENDBLUE_CONFIG["from_name"], string $from_email = SENDBLUE_CONFIG["from_email"]): bool
    {
        if (empty($this->data)) {
            $this->message->error("Erro ao enviar, favor verificar os dados informados.");
            return false;
        }

        if (!is_email($this->data->recipent_email)) {
            $this->message->warning("O e-mail do destinatário é inválido.");
            return false;
        }

        if (!is_email($from_email)) {
            $this->message->warning("O e-mail do remetente é inválido.");
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipent_email, $this->data->recipent_name);
            $this->mail->setFrom($from_email, $from_name);

            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            $this->message->error("Erro ao enviar o e-mail. Erro: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * @param string $from_name
     * @param string $from_email
     * @return bool
     */
    public function queue(string $from_name = SENDBLUE_CONFIG["from_name"], string $from_email = SENDBLUE_CONFIG["from_email"]): bool
    {
        try {
            $stmt = Connect::getInstance()->prepare(
                "INSERT INTO mail_queue (subject, body, from_email, from_name, recipient_email, recipient_name) 
                VALUES (:subject, :body, :from_email, :from_name, :recipient_email, :recipient_name);"
            );
            $stmt->bindValue(":subject", $this->data->subject, \PDO::PARAM_STR);
            $stmt->bindValue(":body", $this->data->body, \PDO::PARAM_STR);
            $stmt->bindValue(":from_email", $from_email, \PDO::PARAM_STR);
            $stmt->bindValue(":from_name", $from_name, \PDO::PARAM_STR);
            $stmt->bindValue(":recipient_email", $this->data->recipent_email, \PDO::PARAM_STR);
            $stmt->bindValue(":recipient_name", $this->data->recipent_name, \PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (\PDOException $exception) {
            $this->message->error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param int $seconds
     */
    public function sendQueue(int $seconds = 5): void
    {
        $stmt = Connect::getInstance()->query("SELECT * FROM mail_queue WHERE sent_at IS NULL;");

        if ($stmt->rowCount()) {
            foreach ($stmt->fetchAll() as $send) {
                $email = $this->add(
                    $send->subject,
                    $send->body,
                    $send->recipient_name,
                    $send->recipient_email
                );

                if ($email->send($send->from_name, $send->from_email)) {
                    usleep(1000000 / $seconds); // padrão 5 segundos
                    Connect::getInstance()->exec("UPDATE mail_queue SET sent_at = NOW() WHERE id = {$send->id};");
                }
            }
        }
    }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }
}