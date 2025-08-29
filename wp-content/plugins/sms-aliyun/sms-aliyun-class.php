<?php
require_once "vendor/autoload.php";

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsSignListRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateListRequest;

class SMS_Aliyun {
    // 获取配置项
    private $config;
    // 运行时选项
    private $runtime;
    // 创建客户端实例
    private $createClient;
    // 发送短信的请求
    private $sendSmsRequest;

    /**
     * 构造函数
     * @param array $templateParams 短信内容
     */
    public function __construct(array $templateParams = [])
    {
        // 1. 获取必置项
        $this->config = new Config(get_option('sms_aliyun_options'));
        // 2. 初始化运行时
        $this->runtime = new RuntimeOptions([]);
        // 3. 初始化客户端
        $this->createClient();

        // 4. 构造短信发送请求
        $this->sendSmsRequest = new SendSmsRequest(array(
            "templateParam" => json_encode($templateParams),
            "signName"      => get_option('sms_aliyun_signName'),
            "phoneNumbers"  => get_option('sms_aliyun_phoneNumber'),
            "templateCode"  => get_option('sms_aliyun_templateCode'),
        ));
    }

    public function createClient(): void
    {
        $this->createClient = new Dysmsapi($this->config);
    }
    
    public function sendSmsRequest(): void
    {

        $log_file = ABSPATH . 'sms_aliyun_errors.log';

        try {
            $this->createClient->sendSmsWithOptions($this->sendSmsRequest, $this->runtime);
        } catch (Exception $e) {
            if (!($e instanceof TeaError)) {
                $e = new TeaError([], $e->getMessage(), $e->getCode(), $e);
            }
            // 写入错误日志
            file_put_contents( $log_file, sprintf(
                    "[%s]短信发送失败，发送号码：%d，%s，详情见：%s\n",
                    date('Y-m-d H:i:s'),
                    $this->sendSmsRequest->phoneNumbers,
                    $e->message,
                    $e->data["Recommend"],
                    $log_file
            ), FILE_APPEND );
        }
    }

    // 获取短信签名列表
    public function getSmsSignList(): array
    {
        $querySmsSignListRequest = new QuerySmsSignListRequest([]);
        try {
            $result = $this->createClient->querySmsSignListWithOptions($querySmsSignListRequest, $this->runtime);
            return $result->body->smsSignList;
        } catch (Exception $e) {
            return [];
        }
    }

    // 获取短信模板列表
    public function getSmsTemplateList(): array
    {
        $querySmsTemplateListRequest = new QuerySmsTemplateListRequest([]);

        try {
            $result = $this->createClient->querySmsTemplateListWithOptions($querySmsTemplateListRequest, $this->runtime);
            return $result->body->smsTemplateList;
        } catch (Exception $e) {
            return [];
        }
    }
}