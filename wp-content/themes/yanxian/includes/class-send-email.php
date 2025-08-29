<?php
// 发送邮件类
use PHPMailer\PHPMailer\PHPMailer;

class SendEmail {
    // smtp主机
    private $smtp_host;
    // smtp端口
    private $smtp_port;
    // smtp用户
    private $smtp_username;
    // smtp密码
    private $smtp_password;
    // 接收邮箱
    private $smtp_receivers;
    // 加密方式
    private $smtp_encryption;

    public function __construct() {
        $this->smtp_host = get_option('smtp_host');
        $this->smtp_port = get_option('smtp_port');
        $this->smtp_username = get_option('smtp_username');
        $this->smtp_password = get_option('smtp_password');
        $this->smtp_receivers = get_option('smtp_receivers');
        $this->smtp_encryption = get_option('smtp_encryption');
    }
    
    /**
     * 发送询盘邮件
     * @param string $subject 邮件主题
     * @param string $content 邮件内容
     * @return bool 发送成功返回true，失败返回false
     */
    public function send_inquiry_email(string $subject, string $content) {
        // 检查必要的SMTP配置
        if (empty($this->smtp_host)
         || empty($this->smtp_port)
         || empty($this->smtp_username)
         || empty($this->smtp_password)
         || empty($this->smtp_receivers)) {
            return false;
        }

        // 检查PHPMailer类是否存在
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            require_once ABSPATH . 'wp-includes/PHPMailer/SMTP.php';
            require_once ABSPATH . 'wp-includes/PHPMailer/PHPMailer.php';
            require_once ABSPATH . 'wp-includes/PHPMailer/Exception.php';
        }

        // 配置PHPMailer
        $mail = new PHPMailer(true);

        try {
            // 服务器设置
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $this->smtp_host;
            $mail->Port = $this->smtp_port;
            $mail->Username = $this->smtp_username;
            $mail->Password = $this->smtp_password;
            
            // 设置加密方式
            if ($this->smtp_encryption !== 'none') {
                $mail->SMTPSecure = $this->smtp_encryption;
            }
    
            // 收发件人设置
            $mail->setFrom($this->smtp_username, get_bloginfo('name'));
            foreach (explode(',', $this->smtp_receivers) as $receiver) {
                $mail->addAddress(trim($receiver));
            }
    
            // 邮件内容
            $mail->isHTML(true);
            $mail->Body = $content;
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
    
            // 发送邮件
            return $mail->send();
        } catch (Exception $e) {
            error_log("邮件发送失败: {$mail->ErrorInfo}");
            return false;
        }
    }
}
