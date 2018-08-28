<?php

// v1
if ( ! file_exists( GeoLite2Helper_PATH . '/Reader.php' ) && file_exists( GeoLite2Helper_PATH . '/Db/Reader.php' ) ) {
	include GeoLite2Helper_PATH . '/Db/Reader.php';
	include GeoLite2Helper_PATH . '/Db/Reader/Decoder.php';
	include GeoLite2Helper_PATH . '/Db/Reader/InvalidDatabaseException.php';
	include GeoLite2Helper_PATH . '/Db/Reader/Metadata.php';
	include GeoLite2Helper_PATH . '/Db/Reader/Util.php';
}
// v2
else {
	include GeoLite2Helper_PATH . '/Reader.php';
	include GeoLite2Helper_PATH . '/Reader/Decoder.php';
	include GeoLite2Helper_PATH . '/Reader/InvalidDatabaseException.php';
	include GeoLite2Helper_PATH . '/Reader/Metadata.php';
	include GeoLite2Helper_PATH . '/Reader/Util.php';
}

use MaxMind\Db\Reader;

class WGR_GeoLite2Helper {
//	public $ipAddress;
	
	private function db_not_found () {
		return 'GeoLite2 DB not found! <a href="http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz" rel="nofollow" target="_blank">Click here</a> Download and up to folder: <strong>' . GeoLite2Helper_UploadPATH . '</strong>';
	}
	
	/*
	function __construct() {
		$this->ipAddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$this->ipAddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$this->ipAddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$this->ipAddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$this->ipAddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $this->ipAddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$this->ipAddress = getenv('REMOTE_ADDR');
		else
			$this->ipAddress = 'UNKNOWN';
	}
	*/
	
	private function ipAddress() {
		if ( isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) ) {
			return $_SERVER ['HTTP_X_FORWARDED_FOR'];
		}
		else if ( isset ( $_SERVER ['HTTP_X_REAL_IP'] ) ) {
			return $_SERVER ['HTTP_X_REAL_IP'];
		}
		else if ( isset ( $_SERVER ['HTTP_CLIENT_IP'] ) ) {
			return $_SERVER ['HTTP_CLIENT_IP'];
		}
		return $_SERVER ['REMOTE_ADDR'];
		
		
		//
		if (getenv('HTTP_CLIENT_IP'))
			$this->ipAddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$this->ipAddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$this->ipAddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$this->ipAddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $this->ipAddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$this->ipAddress = getenv('REMOTE_ADDR');
		else
			$this->ipAddress = 'UNKNOWN';
	}
	
	private function getDB($ip) {
		/*
		if (!empty($ip)) {
			$this->ipAddress = $ip;
		}
		*/
		if ( $ip == NULL ) {
			$ip = $this->ipAddress();
		}
		// test
//		$ip .= '1';
//		echo $ip . '<br>' . "\n";
		
		// nạp DB, ưu tiên nạp trong thư mục Upload trước
		// nếu có cấp độ City -> lấy theo cấp độ City
		if ( file_exists( GeoLite2Helper_UploadPATH . '/GeoLite2-City.mmdb' ) ) {
			$reader = new Reader( GeoLite2Helper_UploadPATH . '/GeoLite2-City.mmdb' );
		}
		else if ( file_exists( GeoLite2Helper_DBPATH . '/GeoLite2-City.mmdb' ) ) {
			$reader = new Reader( GeoLite2Helper_DBPATH . '/GeoLite2-City.mmdb' );
		}
		// v1
		else if ( file_exists( GeoLite2Helper_PATH . '/GeoLite2-City.mmdb' ) ) {
			$reader = new Reader( GeoLite2Helper_PATH . '/GeoLite2-City.mmdb' );
		}
		// mặc định chỉ lấy Country
		else if ( file_exists( GeoLite2Helper_UploadPATH . '/GeoLite2-Country.mmdb' ) ) {
			$reader = new Reader( GeoLite2Helper_UploadPATH . '/GeoLite2-Country.mmdb' );
		}
		else if ( file_exists( GeoLite2Helper_DBPATH . '/GeoLite2-Country.mmdb' ) ) {
			$reader = new Reader( GeoLite2Helper_DBPATH . '/GeoLite2-Country.mmdb' );
		}
		// v1
		else if ( file_exists( GeoLite2Helper_PATH . '/GeoLite2-Country.mmdb' ) ) {
			$reader = new Reader( GeoLite2Helper_PATH . '/GeoLite2-Country.mmdb' );
		}
		// ngoài ra thì bỏ qua
		else {
			return NULL;
		}
		
		//
//		$result = $reader->get($this->ipAddress);
		$result = $reader->get($ip);
//		print_r( $result );
		
		//
		$reader->close();
		
		return $result;
	}
	
	
	// lấy nhiều thông tin cùng lúc
	public function getUserOptionByIp($ip = NULL, $o = NULL) {
		$a = $this->getDB( $ip ); if ( $a == NULL ) return $this->db_not_found();
//		if ( mtv_id == 1 ) print_r( $a );
		
		//
		$r = array();
		
		// lấy các thông số theo thuộc tính
		// tất cả các giá trị
		if ( $o == NULL || $o == 'all' ) {
			if ( isset( $a['city'] ) ) {
				$r[] = $a['city']['names']['en'];
			}
			
			$r[] = $a['country']['names']['en'];
			$r[] = $a['continent']['names']['en'];
			$r[] = '<a href="https://www.google.com/maps/@' . $a['location']['latitude'] . ',' . $a['location']['longitude'] . ',17z" rel="nofollow" target="_blank" class="small">Xem bản đồ</a>';
		}
		// tất cả mã vùng
		else if ( $o == 'all_code' ) {
			if ( isset( $a['subdivisions'] ) ) {
				$r[] = $a['subdivisions'][0]['iso_code'];
			}
			else if ( isset( $a['city'] ) ) {
				$r[] = $a['city']['names']['en'];
			}
			
			$r[] = $a['country']['iso_code'];
			$r[] = $a['continent']['code'];
			$r[] = '<a href="https://www.google.com/maps/@' . $a['location']['latitude'] . ',' . $a['location']['longitude'] . ',17z" rel="nofollow" target="_blank" class="small">Xem bản đồ</a>';
		}
		// lấy từng cái
		else {
			if ( isset( $o['city'] ) && isset( $a['city'] ) ) {
				$r[] = $a['city']['names']['en'];
			}
			else if ( isset( $o['city_code'] ) ) {
				if ( isset( $a['subdivisions'] ) ) {
					$r[] = $a['subdivisions'][0]['iso_code'];
				}
				else if ( isset( $a['city'] ) ) {
					$r[] = $a['city']['names']['en'];
				}
			}
			
			if ( isset( $o['country'] ) ) {
				$r[] = $a['country']['names']['en'];
			}
			else if ( isset( $o['country_code'] ) ) {
				$r[] = $a['country']['iso_code'];
			}
			
			if ( isset( $o['continent'] ) ) {
				$r[] = $a['continent']['names']['en'];
			}
			else if ( isset( $o['continent_code'] ) ) {
				$r[] = $a['continent']['code'];
			}
			
			if ( isset( $o['location'] ) ) {
				$r[] = '<a href="https://www.google.com/maps/@' . $a['location']['latitude'] . ',' . $a['location']['longitude'] . ',17z" rel="nofollow" target="_blank" class="small">Xem bản đồ</a>';
			}
		}
		
		//
		return implode( ', ', $r );
	}
	
	
	// lấy vị trí theo tỉnh thành hoặc quốc gia
	public function getUserAddressByIp($ip = NULL) {
		$a = $this->getDB( $ip ); if ( $a == NULL ) return $this->db_not_found();
		
		//
		$r = array();
		
		if ( isset( $a['city'] ) ) {
			$r[] = $a['city']['names']['en'];
		}
		
		//
		$r[] = $a['country']['names']['en'];
		
		//
		return implode( ', ', $r );
	}
	
	
	// lấy vị trí theo quốc gia
	public function getUserCountryByIp($ip = NULL) {
		$a = $this->getDB( $ip ); if ( $a == NULL ) return $this->db_not_found();
		
		//
		return $a['country']['names']['en'];
	}
	
	// lấy vị trí theo quốc gia (mã code)
	public function getUserCountryCodeByIp($ip = NULL) {
		$a = $this->getDB( $ip ); if ( $a == NULL ) return $this->db_not_found();
		
		//
		return $a['country']['iso_code'];
	}
	
	
	// lấy vị trí theo tỉnh thành (mã code)
	public function getUserCityByIp($ip = NULL) {
		$a = $this->getDB( $ip ); if ( $a == NULL ) return $this->db_not_found();
		
		//
		if ( isset( $a['subdivisions'] ) ) {
			return $a['subdivisions'][0]['names']['en'];
		}
		else if ( isset( $a['city'] ) ) {
			return $a['city']['names']['en'];
		}
		
		return '';
	}
	
	// lấy vị trí theo tỉnh thành (mã code)
	public function getUserCityCodeByIp($ip = NULL) {
		$a = $this->getDB( $ip ); if ( $a == NULL ) return $this->db_not_found();
		
		//
		if ( isset( $a['subdivisions'] ) ) {
			return $a['subdivisions'][0]['iso_code'];
		}
		
		return '';
	}
	
	
	// lấy vị trí theo trên bản đồ google
	public function getUserLocByIp($ip = NULL) {
		$a = $this->getDB( $ip ); if ( $a == NULL ) return $this->db_not_found();
		
		//
		if ( isset( $a['location'] ) ) {
			return array(
				'lat' => $a['location']['latitude'],
				'latitude' => $a['location']['latitude'],
				'lon' => $a['location']['longitude'],
				'longitude' => $a['location']['longitude']
			);
		}
		
		return array();
	}
}


