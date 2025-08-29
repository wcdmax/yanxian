<?php

class URL_Push {
    /**
     * 文章ID
     */
    private $post_id;
    /**
     * 文章链接
     */
    private $post_url;
    /**
     * 网站域名
     */
    private $base_url;
    /**
     * 必应推送key
     */
    private $bing_key;
    /**
     * 百度推送Token
     */
    private $baidu_token;

    public function __construct(int $post_id, string $post_url) {
        $this->post_id      = $post_id;
        $this->post_url     = $post_url;
        $this->base_url     = get_site_url();
        $this->bing_key     = get_option('url_push_bing_key');
        $this->baidu_token  = get_option('url_push_baidu_token');
    }

    /**
     * 保存日志
     * @param array $log_data 日志数据
     */
    private function log(array $log_data): void {
        // 写入数据库
        global $wpdb;
        $table_name = $wpdb->prefix . 'url_push';
        $wpdb->insert($table_name, $log_data + array(
                'url' => $this->post_url,
                'post_id' => $this->post_id,
                'create_time' => current_time('mysql'),
            )
        );
    }

    /**
     * 推送文章链接至百度
     */
    public function push_url_to_baidu(): void {
        // 检查是否需要推送
        if ($this->url_push_check($this->post_id, 'baidu')) {
            return;
        }

        $response = wp_remote_post($this->baidu_token, array(
            'body'      => $this->post_url,
            'headers'   => array('Content-Type' => 'text/plain'),
        ));
        $response_body = json_decode(wp_remote_retrieve_body($response), true);

        $log_data = array(
            'platform' => 'baidu',
            'remain' => $response_body['remain'],
            'success' => $response_body['success'] ? 1 : 0,
        );

        // 保存日志
        $this->log($log_data);
    }

    /**
     * 推送文章链接至必应
     */
    public function push_url_to_bing(): void {
        // 检查是否需要推送
        if ($this->url_push_check($this->post_id, 'bing')) {
            return;
        }

        $bing_data = array(
            'key'           => $this->bing_key,
            'urlList'       => array($this->post_url),
            'host'          => parse_url(get_site_url(), PHP_URL_HOST),
            'keyLocation'   => sprintf('%s/%s.txt', $this->base_url, $this->bing_key),
        );

        $response = wp_remote_post('https://www.bing.com/indexnow', array(
            'body' => json_encode($bing_data),
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        ));

        $log_data = array(
            'platform' => 'bing',
            'success' => wp_remote_retrieve_response_code($response) === 200 ? 1 : 0,
        );

        // 保存日志
        $this->log($log_data);
    }

    // 检查是否需要推送
    public function url_push_check(int $post_id, string $platform): bool {
        global $wpdb;
        $table_name = $wpdb->prefix . 'url_push';
        return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE post_id = %d AND success = 1 AND platform = %s", $post_id, $platform)) > 0;
    }
}
