<?php
if(
	(isset($_GET['debug']) && $_GET['debug'] == 'sql')
){
	return;
}

if(class_exists('Memcached')){
	function wp_cache_add($key, $data, $group='', $expire=0){
		global $wp_object_cache;
		return $wp_object_cache->add($key, $data, $group, (int)$expire);
	}

	function wp_cache_cas($cas_token, $key, $data, $group='', $expire=0){
		global $wp_object_cache;
		return $wp_object_cache->cas($cas_token, $key, $data, $group, (int)$expire);
	}

	function wp_cache_close(){
		global $wp_object_cache;
		return $wp_object_cache->close();
	}

	function wp_cache_decr($key, $offset=1, $group='', $initial_value=0, $expire=0){
		global $wp_object_cache;
		return $wp_object_cache->decr($key, $offset, $group, $initial_value, $expire);
	}

	function wp_cache_delete($key, $group=''){
		global $wp_object_cache;
		return $wp_object_cache->delete($key, $group);
	}

	function wp_cache_flush(){
		global $wp_object_cache;
		return $wp_object_cache->flush();
	}

	function wp_cache_get($key, $group='', $force=false, &$found=null){
		global $wp_object_cache;
		return $wp_object_cache->get($key, $group, $force, $found);
	}

	function wp_cache_get_multiple($keys, $group='', $force=false){
		global $wp_object_cache;
		return $wp_object_cache->get_multiple($keys, $group, $force);
	}

	function wp_cache_set_multiple($data, $group='', $expire=0){
		global $wp_object_cache;
		return $wp_object_cache->set_multiple($data, $group, $expire);
	}

	function wp_cache_delete_multiple($keys, $group=''){
		global $wp_object_cache;
		return $wp_object_cache->delete_multiple($keys, $group);
	}

	function wp_cache_get_with_cas($key, $group='', &$token=null){
		global $wp_object_cache;

		$result	= $wp_object_cache->get_with_cas($key, $group);

		if(is_array($result)){
			$token	= $result['cas'];
			$result	= $result['value'];
		}

		return $result;
	}

	function wp_cache_incr($key, $offset=1, $group='', $initial_value=0, $expire=0){
		global $wp_object_cache;
		return $wp_object_cache->incr($key, $offset, $group, $initial_value, $expire);
	}

	if(!isset($_GET['debug']) || $_GET['debug'] != 'sql'){
		function wp_cache_init(){
			global $wp_object_cache;
			$wp_object_cache	= new WP_Object_Cache();
		}
	}

	function wp_cache_replace($key, $data, $group='', $expire=0){
		global $wp_object_cache;
		return $wp_object_cache->replace($key, $data, $group, (int)$expire);
	}

	function wp_cache_set($key, $data, $group='', $expire=0){
		global $wp_object_cache;
		return $wp_object_cache->set($key, $data, $group, (int)$expire);
	}

	function wp_cache_switch_to_blog($blog_id){
		global $wp_object_cache;
		return $wp_object_cache->switch_to_blog($blog_id);
	}

	function wp_cache_add_global_groups($groups){
		global $wp_object_cache;
		$wp_object_cache->add_global_groups($groups);
	}

	function wp_cache_add_non_persistent_groups($groups){
		global $wp_object_cache;
		$wp_object_cache->add_non_persistent_groups($groups);
	}

	function wp_cache_get_stats(){
		global $wp_object_cache;
		return $wp_object_cache->get_stats();
	}
	
	/**
	 * 获取增强的缓存统计信息
	 * 
	 * @return array 详细的缓存统计数据
	 */
	function wp_cache_get_enhanced_stats(){
		global $wp_object_cache;
		if (method_exists($wp_object_cache, 'stats')) {
			return $wp_object_cache->stats();
		}
		return wp_cache_get_stats();
	}
	
	/**
	 * 获取缓存性能摘要
	 * 
	 * @return array 缓存性能关键指标
	 */
	function wp_cache_get_performance_summary(){
		$stats = wp_cache_get_enhanced_stats();
		
		return array(
			'hit_ratio' => $stats['ratio'] ?? 0,
			'efficiency_grade' => $stats['cache_efficiency']['grade'] ?? 'N/A',
			'total_operations' => $stats['operations'] ?? 0,
			'connection_status' => $stats['connection_status'] ?? false,
			'local_cache_size' => $stats['local_cache_size'] ?? 0,
			'timestamp' => $stats['timestamp'] ?? time()
		);
	}
	
	/**
	 * 检查缓存系统健康状态
	 * 
	 * @return array 健康状态报告
	 */
	function wp_cache_health_check(){
		$stats = wp_cache_get_enhanced_stats();
		$health = array(
			'overall_status' => 'healthy',
			'issues' => array(),
			'recommendations' => array()
		);
		
		// 检查连接状态
		if (!$stats['connection_status']) {
			$health['overall_status'] = 'critical';
			$health['issues'][] = 'Memcached连接失败';
			$health['recommendations'][] = '检查Memcached服务是否运行';
		}
		
		// 检查命中率
		if (isset($stats['ratio']) && $stats['ratio'] < 50) {
			$health['overall_status'] = $health['overall_status'] === 'critical' ? 'critical' : 'warning';
			$health['issues'][] = '缓存命中率过低: ' . $stats['ratio'] . '%';
			$health['recommendations'][] = '检查缓存策略和过期时间设置';
		}
		
		// 检查内存使用
		if (isset($stats['memory_usage']['php_memory_usage'])) {
			$memory_mb = $stats['memory_usage']['php_memory_usage'] / 1024 / 1024;
			if ($memory_mb > 512) { // 超过512MB
				$health['overall_status'] = $health['overall_status'] === 'critical' ? 'critical' : 'warning';
				$health['issues'][] = 'PHP内存使用过高: ' . round($memory_mb, 2) . 'MB';
				$health['recommendations'][] = '考虑优化内存使用或增加服务器内存';
			}
		}
		
		// 检查缓存组配置
		if (isset($stats['global_groups']) && $stats['global_groups'] === 0) {
			$health['issues'][] = '未配置全局缓存组';
			$health['recommendations'][] = '配置适当的全局缓存组以提高多站点性能';
		}
		
		return $health;
	}

	class WP_Object_Cache{
		private $cache 	= [];
		private $mc		= null;

		private $blog_prefix;
		private $global_prefix;
		
		// 统计属性
		public $cache_hits = 0;
		public $cache_misses = 0;
		public $cache_operations = 0;
		public $connection_status = false;
		public $multisite = false;
		public $key_cache = array();
		
		// 配置选项
		public $options = array(
			'servers' => array(),
			'compression' => true,
			'serializer' => 'php',
			'connect_timeout' => 1000,
			'binary_protocol' => true,
		);

		protected $global_groups	= [];
		protected $non_persistent_groups	= [];

		protected function action($action, $id, $group, $data, $expire=0){
			if($this->is_non_persistent_group($group)){
				$internal	= $this->internal('get', $id, $group);

				if($action == 'add'){
					if($internal !== false){
						return false;
					}
				}elseif($action == 'replace'){
					if($internal === false){
						return false;
					}
				}elseif($action == 'increment' || $action == 'decrement'){
					$data	= $action == 'increment' ? $data : (0-$data);
					$data	= (int)$internal+$data;
					$data	= $data < 0 ? 0 : $data;
				}

				return $this->internal('add', $id, $group, $data);
			}else{
				$key	= $this->build_key($id, $group);
				$expire	= (!$expire && strlen($id) > 50) ? DAY_IN_SECONDS : $expire;

				if($action == 'set'){
					$result	= $this->mc->set($key, $data, $expire);
				}elseif($action == 'add'){
					$result	= $this->mc->add($key, $data, $expire);
				}elseif($action == 'replace'){
					$result	= $this->mc->replace($key, $data, $expire);
				}elseif($action == 'increment'){
					$result	= $data = $this->mc->increment($key, $data);
				}elseif($action == 'decrement'){
					$result	= $data = $this->mc->decrement($key, $data);
				}

				$code	= $this->mc->getResultCode();

				if($code === Memcached::RES_SUCCESS){
					$this->internal('add', $id, $group, $data);
				}else{
					$this->internal('del', $id, $group);

					if($code != Memcached::RES_NOTSTORED){
                        error_log($code.' '.var_export($result, true).' '.var_export($key, true));
					}
				}

				return $result;
			}
		}

		protected function internal($action, $id, $group, $data=null){
			$group	= $this->parse_group($group);

			if($action == 'get'){
				$data	= $this->cache[$group][$id] ?? false;

				return is_object($data) ? clone $data : $data;
			}elseif($action == 'add'){
				$this->cache[$group][$id]	= is_object($data) ? clone $data : $data;

				return true;
			}elseif($action == 'del'){
				unset($this->cache[$group][$id]);
			}
		}

		public function add($id, $data, $group='default', $expire=0){
			if(wp_suspend_cache_addition()){
				return false;
			}

			return $this->action('add', $id, $group, $data, $expire);
		}

		public function replace($id, $data, $group='default', $expire=0){
			return $this->action('replace', $id, $group, $data, $expire);
		}

		public function set($id, $data, $group='default', $expire=0){
			return $this->action('set', $id, $group, $data, $expire);
		}

		public function incr($id, $offset=1, $group='default', $initial_value=0, $expire=0){
			$this->action('add', $id, $group, $initial_value, $expire);
			return $this->action('increment', $id, $group, $offset);
		}

		public function decr($id, $offset=1, $group='default', $initial_value=0, $expire=0){
			$this->action('add', $id, $group, $initial_value, $expire);
			return $this->action('decrement', $id, $group, $offset);
		}

		public function cas($cas_token, $id, $data, $group='default', $expire=0){
			$this->internal('del', $id, $group);

			return $this->mc->cas($cas_token, $this->build_key($id, $group), $data, $expire);
		}

		public function delete($id, $group='default'){
			$this->internal('del', $id, $group);

			return $this->is_non_persistent_group($group) ? true : $this->mc->delete($this->build_key($id, $group));
		}

		public function flush(){
			$this->cache	= [];

			return $this->mc->flush();
		}

		public function get($id, $group='default', $force=false, &$found=null){
			$this->cache_operations++;
			
			$value	= $force ? false : $this->internal('get', $id, $group);
			$found	= $value !== false;

			if(!$found && !$this->is_non_persistent_group($group)){
				$value	= $this->mc->get($this->build_key($id, $group));
				$code	= $this->mc->getResultCode();
				$found	= $code !== Memcached::RES_NOTFOUND;

				if($found){
					$this->cache_hits++;
					if($code !== Memcached::RES_SUCCESS){
						trigger_error($code.' '.var_export([$id, $group, $value], true));
					}

					$this->internal('add', $id, $group, $value);
				} else {
					$this->cache_misses++;
				}
			} else {
				if($found) {
					$this->cache_hits++;
				} else {
					$this->cache_misses++;
				}
			}

			return $value;
		}

		public function get_with_cas($id, $group='default'){
			$key	= $this->build_key($id, $group);

			if(defined('Memcached::GET_EXTENDED')){
				$result	= $this->mc->get($key, null, Memcached::GET_EXTENDED);
			}else{
				$value	= $this->mc->get($key, null, $cas);
				$result	= ['value'=>$value, 'cas'=>$cas];
			}

			return $this->mc->getResultCode() === Memcached::RES_NOTFOUND ? false : $result;
		}

		public function get_multiple($ids, $group='default', $force=false){
			$caches	= [];
			$keys	= [];

			$non_persistent	= $this->is_non_persistent_group($group);

			if($non_persistent || !$force){
				foreach($ids as $id){
					$caches[$id]	= $this->internal('get', $id, $group);
					$keys[$id]		= $this->build_key($id, $group);

					if(!$non_persistent && $caches[$id] === false){
						$force	= true;
					}
				}

				if($non_persistent || !$force){
					return $caches;
				}
			}

			$results	= $this->mc->getMulti(array_values($keys)) ?: [];

			foreach($keys as $id => $key){
				$caches[$id]	= $results[$key] ?? false;

				$this->internal('add', $id, $group, $caches[$id]);
			}

			return $caches;
		}

		public function set_multiple($data, $group='default', $expire=0){
			$items	= [];

			foreach($data as $id => $value){
				$this->internal('add', $id, $group, $value);

				$key = $this->build_key($id, $group);

				$items[$key]	= $value;
			}

			if($this->is_non_persistent_group($group)){
				$result	= true;
			}else{
				$result	= $this->mc->setMulti($items, $expire);
				$code	= $this->mc->getResultCode();

				if($code !== Memcached::RES_SUCCESS){
					if($code != Memcached::RES_NOTSTORED){
						// trigger_error($code.' '.var_export($result,true));
					}

					foreach($data as $id => $value){
						$this->internal('del', $id, $group);
					}

					return $result;
				}
			}

			return $result;
		}

		public function delete_multiple($ids, $group='default'){
			foreach($ids as $id){
				$this->internal('del', $id, $group);

				$keys[]	= $this->build_key($id, $group);
			}

			return (empty($keys) || $this->is_non_persistent_group($group)) ? true : $this->mc->deleteMulti($keys);
		}

		public function add_global_groups($groups){
			$this->global_groups	= array_merge($this->global_groups, array_fill_keys((array)$groups, true));
		}

		public function add_non_persistent_groups($groups){
			$this->non_persistent_groups	= array_merge($this->non_persistent_groups, array_fill_keys((array)$groups, true));
		}

		public function switch_to_blog($blog_id){
			if(is_multisite()){
				$this->blog_prefix	= ((int)$blog_id).':';
			}
		}

		private function is_non_persistent_group($group){
			return $group ? isset($this->non_persistent_groups[$group]) : false;
		}

		private function parse_group($group){
			$group	= $group ?: 'default';
			$prefix	= isset($this->global_groups[$group]) ? $this->global_prefix : $this->blog_prefix;

			return WP_CACHE_KEY_SALT.$prefix.$group;
		}

		public function build_key($id, $group='default'){
			return preg_replace('/\s+/', '', $this->parse_group($group).':'.$id);
		}

			/**
	 * 获取增强的缓存统计信息
	 */
	/**
	 * 获取增强的缓存统计信息
	 */
	public function stats() {
		$total = $this->cache_hits + $this->cache_misses;
		$ratio = $total > 0 ? ($this->cache_hits / $total) * 100 : 0;
		
		$stats = array(
			// 基础统计
			'hits' => $this->cache_hits,
			'misses' => $this->cache_misses,
			'ratio' => round($ratio, 2),
			'operations' => $this->cache_operations,
			'connection_status' => $this->is_connected(),
			'local_cache_size' => count($this->cache),
			'key_cache_size' => count($this->key_cache),
			
			// 配置信息
			'cache_key_salt' => defined('WP_CACHE_KEY_SALT') ? WP_CACHE_KEY_SALT : 'default',
			'user_key_salt' => defined('WP_CACHE_USER_KEY_SALT') ? WP_CACHE_USER_KEY_SALT : 'none',
			'blog_prefix' => $this->blog_prefix,
			'multisite' => $this->multisite,
			
			// 缓存组统计
			'global_groups' => count($this->global_groups),
			'non_persistent_groups' => count($this->non_persistent_groups),
			'global_groups_list' => array_values($this->global_groups),
			'non_persistent_groups_list' => array_values($this->non_persistent_groups),
			
			// 时间戳
			'timestamp' => time(),
			'date' => date('Y-m-d H:i:s'),
			
			// 性能指标
			'avg_operations_per_second' => $this->get_operations_per_second(),
			'cache_efficiency' => $this->get_cache_efficiency(),
			
			// 内存使用
			'memory_usage' => $this->get_memory_usage(),
			
			// 服务器配置
			'servers' => $this->options['servers'],
			'options' => array(
				'compression' => $this->options['compression'],
				'serializer' => $this->options['serializer'],
				'connect_timeout' => $this->options['connect_timeout'],
				'binary_protocol' => $this->options['binary_protocol'],
			)
		);
		
		// 添加 Memcached 服务器统计信息
		if ($this->connection_status && $this->mc) {
			try {
				$memcached_stats = $this->mc->getStats();
				$stats['memcached'] = $this->format_memcached_stats($memcached_stats);
			} catch (Exception $e) {
				$stats['memcached_error'] = $e->getMessage();
			}
		}
		
		// 按组统计缓存使用情况
		$stats['group_usage'] = $this->get_group_usage_stats();
		
		return $stats;
	}
	
	/**
	 * 检查是否连接到 Memcached
	 */
	public function is_connected() {
		if (!$this->mc) return false;
		
		try {
			// 尝试获取版本信息来测试连接
			$version = $this->mc->getVersion();
			return !empty($version);
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * 获取操作每秒统计
	 */
	private function get_operations_per_second() {
		static $start_time;
		if (!$start_time) {
			$start_time = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
		}
		
		$elapsed = microtime(true) - $start_time;
		return $elapsed > 0 ? round($this->cache_operations / $elapsed, 2) : 0;
	}
	
	/**
	 * 获取缓存效率指标
	 */
	private function get_cache_efficiency() {
		$total = $this->cache_hits + $this->cache_misses;
		if ($total == 0) return array('score' => 'N/A', 'grade' => 'N/A');
		
		$ratio = ($this->cache_hits / $total) * 100;
		
		if ($ratio >= 90) $grade = 'A+';
		elseif ($ratio >= 80) $grade = 'A';
		elseif ($ratio >= 70) $grade = 'B';
		elseif ($ratio >= 60) $grade = 'C';
		elseif ($ratio >= 50) $grade = 'D';
		else $grade = 'F';
		
		return array(
			'score' => round($ratio, 2),
			'grade' => $grade,
			'total_requests' => $total
		);
	}
	
	/**
	 * 获取内存使用统计
	 */
	private function get_memory_usage() {
		return array(
			'php_memory_usage' => memory_get_usage(true),
			'php_memory_peak' => memory_get_peak_usage(true),
			'php_memory_limit' => ini_get('memory_limit'),
			'local_cache_entries' => count($this->cache),
			'key_cache_entries' => count($this->key_cache)
		);
	}
	
	/**
	 * 格式化 Memcached 统计信息
	 */
	private function format_memcached_stats($raw_stats) {
		if (empty($raw_stats)) return array();
		
		$formatted = array();
		foreach ($raw_stats as $server => $stats) {
			if (!is_array($stats)) continue;
			
			$formatted[$server] = array(
				'uptime' => isset($stats['uptime']) ? $this->format_uptime($stats['uptime']) : 'N/A',
				'version' => $stats['version'] ?? 'N/A',
				'curr_items' => $stats['curr_items'] ?? 0,
				'total_items' => $stats['total_items'] ?? 0,
				'bytes' => isset($stats['bytes']) ? $this->format_bytes($stats['bytes']) : 'N/A',
				'curr_connections' => $stats['curr_connections'] ?? 0,
				'total_connections' => $stats['total_connections'] ?? 0,
				'cmd_get' => $stats['cmd_get'] ?? 0,
				'cmd_set' => $stats['cmd_set'] ?? 0,
				'get_hits' => $stats['get_hits'] ?? 0,
				'get_misses' => $stats['get_misses'] ?? 0,
				'hit_ratio' => $this->calculate_server_hit_ratio($stats),
				'evictions' => $stats['evictions'] ?? 0,
				'reclaimed' => $stats['reclaimed'] ?? 0,
				'bytes_read' => isset($stats['bytes_read']) ? $this->format_bytes($stats['bytes_read']) : 'N/A',
				'bytes_written' => isset($stats['bytes_written']) ? $this->format_bytes($stats['bytes_written']) : 'N/A'
			);
		}
		
		return $formatted;
	}
	
	/**
	 * 计算服务器命中率
	 */
	private function calculate_server_hit_ratio($stats) {
		$hits = $stats['get_hits'] ?? 0;
		$misses = $stats['get_misses'] ?? 0;
		$total = $hits + $misses;
		
		return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
	}
	
	/**
	 * 格式化运行时间
	 */
	private function format_uptime($seconds) {
		$days = floor($seconds / 86400);
		$hours = floor(($seconds % 86400) / 3600);
		$minutes = floor(($seconds % 3600) / 60);
		
		return sprintf('%d天 %d小时 %d分钟', $days, $hours, $minutes);
	}
	
	/**
	 * 格式化字节数
	 */
	private function format_bytes($bytes) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		
		$bytes /= pow(1024, $pow);
		
		return round($bytes, 2) . ' ' . $units[$pow];
	}
	
	/**
	 * 获取按组的使用统计
	 */
	private function get_group_usage_stats() {
		$group_stats = array();
		
		foreach ($this->cache as $key => $value) {
			$parts = explode(':', $key);
			if (count($parts) >= 2) {
				$group = $parts[count($parts) - 2]; // 倒数第二部分是组名
				
				if (!isset($group_stats[$group])) {
					$group_stats[$group] = array(
						'count' => 0,
						'is_global' => in_array($group, $this->global_groups),
						'is_non_persistent' => in_array($group, $this->non_persistent_groups)
					);
				}
				
				$group_stats[$group]['count']++;
			}
		}
		
		// 按使用量排序
		uasort($group_stats, function($a, $b) {
			return $b['count'] - $a['count'];
		});
		
		return $group_stats;
	}
	
	/**
	 * 获取原始 Memcached 统计（保持向后兼容）
	 */
	public function get_stats() {
		return $this->memcached ? $this->memcached->getStats() : array();
	}

		public function get_mc(){
			return $this->mc;
		}

		public function failure_callback($host, $port){}

		public function close(){
			$this->mc->quit();
		}

		public function __construct(){
			$this->mc	= new Memcached();
			
			// 初始化统计属性
			$this->cache_hits = 0;
			$this->cache_misses = 0;
			$this->cache_operations = 0;
			$this->multisite = function_exists('is_multisite') ? is_multisite() : false;

			if(!$this->mc->getServerList()){
				global $memcached_servers;

				if(isset($memcached_servers)){
					$this->options['servers'] = $memcached_servers;
					foreach($memcached_servers as $memcached){
						$this->mc->addServer(...$memcached);
					}
				}else{
					$this->options['servers'] = array(array('127.0.0.1', 11211));
					$this->mc->addServer('127.0.0.1', 11211);
				}
			}
			
			// 测试连接状态
			try {
				$version = $this->mc->getVersion();
				$this->connection_status = !empty($version);
			} catch (Exception $e) {
				$this->connection_status = false;
			}

			if($this->multisite){
				$this->blog_prefix		= get_current_blog_id().':';
				$this->global_prefix	= '';
			}else{
				$this->blog_prefix		= $GLOBALS['table_prefix'].':';
				$this->global_prefix	= defined('CUSTOM_USER_TABLE') ? '' : $this->blog_prefix;
			}
		}
	}
}