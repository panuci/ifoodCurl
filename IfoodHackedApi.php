<?php

class IfoodHackedApi {
	private $cookie = 'cookie.txt';
	private $ch;
	private $complemento;
	private $resId;
	private $header = array();

	function __construct($restauranteInfo = null){
		$this->ch = curl_init();
		curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($this->ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt ($this->ch, CURLOPT_FRESH_CONNECT, TRUE);
		//curl_setopt ($this->ch, CURLOPT_USERAGENT, "insomnia/5.7.14");
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $this->cookie);
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $this->cookie);
		curl_setopt ($this->ch, CURLOPT_COOKIE, TRUE);

		if($restauranteInfo){
			$this->buildParams($restauranteInfo);
		}
	}

	public function addPrato($prato){
		$this->complementos[] = $prato;
	}

	public function setRestauranteId($id){
		$this->resId = $id;
	}

	public function addHeader($header){
		$this->header[] = $header;
	}

	public function buildParams($resInfo){
		$this->resId = $resInfo['RestauranteId'];
		$this->complemento = $resInfo['Food'];

		$this->setUrl('https://www.ifood.com.br/delivery/complementos-item?rid=' . $this->resId . '&code=' . $this->complemento . '&qty=1&index=0');
		$this->setHeader('Referer: https://www.ifood.com.br/delivery/londrina-pr/' . $resInfo['Referer']);
	}

	private function setUrl($url){
		curl_setopt ($this->ch, CURLOPT_URL, $url);
	}

	private function setHeader($header){
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
    		'Host: www.ifood.com.br',    
    		$header,
		));
	}

	public function apiExec(){
		$content = curl_exec($this->ch);
		curl_close ($this->ch);

		return $content;
	}
}