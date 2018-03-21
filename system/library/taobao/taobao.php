<?php
namespace Taobao;
include 'Snoopy.class.php';
class Taobao {
	public function __construct($registry) {
		date_default_timezone_set('Asia/Shanghai'); 
		include_once(DIR_SYSTEM . 'library/taobao/taobao/TopSdk.php');
		$this->config = $registry->get('config');
		$this->top = new \TopClient;
		$this->top->appkey = $this->config->get('module_taobao_appkey');
		$this->top->secretKey = $this->config->get('module_taobao_appsecret');
		$this->pageNo = $this->config->get('module_taobao_page');
		$this->pageSize = $this->config->get('module_taobao_size');
		$this->top->readTimeout = '120';
		// $this->pageNo = 1;
		// $this->pageSize = 20;
  //       $this->top->appkey = '24703000';
  //       $this->top->secretKey = 'f738952974cf8c7aa04835a8f19c5555';
        $this->top->format = 'json';
		$this->debug = true;
        $this->adzoneId = $this->config->get('module_taobao_zone');
	}
	public function getResult($msg){
		if($msg->results){
			return $msg->results;
		}elseif($this->debug){
			return $msg;
		}else{
			return [];
		}
	}
	public function getTaobaoFavoritesCategory($data=array()){
		$req = new \TbkUatmFavoritesGetRequest;
		$req->setPageSize(isset($data['size']) ? $data['size'] : $this->pageSize);
		$req->setPageNo(isset($data['page']) ? $data['size'] : $this->pageNo);
		$req->setFields(isset($data['fields']) ? $data['size'] : 'favorites_id,favorites_title,type');
		$rep = $this->top->execute($req);
		$result = $this->getResult($rep);
		return $result->tbk_favorites;
	}
	public function getTaobaoFavoritesProduct($data=array()){
		$req = new \TbkUatmFavoritesItemGetRequest;
		$req->setPlatform("1");
		$req->setPageSize(isset($data['size']) ? $data['size'] : $this->pageSize);
		$req->setPageNo(isset($data['page']) ? $data['size'] : $this->pageNo);
		$req->setAdzoneId($this->adzoneId);
		$req->setUnid('1082619894');
		$req->setFavoritesId($data['favorites_id']);
		//$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type,coupon_amount,coupon_start_fee,
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type,coupon_end_time,click_url,coupon_click_url,coupon_info,coupon_start_time,coupon_total_count,coupon_remain_count");
		//$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type");
		$rep = $this->top->execute($req);
		if (isset($rep->results)){
			$results =  json_decode(json_encode($rep->results->uatm_tbk_item),true);
			foreach ($results as $key => $value) {
				//print_r($value);
				$results[$key]['id'] = $data['category_id'];
				$results[$key]['favorites_id'] = $data['favorites_id'];
				if(isset($value['coupon_click_url'])){
					$results[$key]['tbpwd'] = $this->corverTbpwd($value['title'],$value['coupon_click_url'],$value['pict_url']);
				}
				if(isset($value['coupon_info'])) {
					preg_match_all('/[0-9]+/', $value['coupon_info'], $match);
					$price = $value['zk_final_price'];
					//print_r($match);
					$results[$key]['coupon_amount'] = (String)($price - $match[0][1]);
					$results[$key]['coupon_minus'] = (String)($match[0][1]);
					//$results[$key]['coupon_minus'] = (String)($price - $match[0][2]);
				}
			}
			return $results;
		}
	}
	public function getTaobaoCouponInfo($coupon_id){
		$req = new \TbkCouponGetRequest;
		$req->setMe($coupon_id);
		//$req->setItemId("123");
		//$req->setActivityId("sdfwe3eefsdf");
		$rep = $this->top->execute($req);
		//$result = $this->getResult($rep);
		return $rep;
	}
	public function getProductDetail($pid){
		$req = new \TbkItemInfoGetRequest;
		$req->setPlatform("1");
		$req->setNumIids((string)$pid);
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,coupon_click_url,click_url,event_end_time,tk_rate,status,type");
		$rep = $this->top->execute($req);
		$result = $this->getResult($rep);
		return $result->n_tbk_item;
	}
	public function getTaobaoSearch($search,$data=array()){
		$req = new \TbkDgItemCouponGetRequest;
        $req->setAdzoneId($this->adzoneId);
        $req->setPlatform(isset($data['platform']) ? $data['platform'] : '2');
        $req->setPageNo(isset($data['page']) ? $data['page'] : $this->pageNo);
        $req->setPageSize(isset($data['size']) ? $data['size'] : $this->pageSize);
        //$req->setQ(urldecode($search));
        $rep = $this->top->execute($req);
        $result = $this->getResult($rep);
		//$new_items = array();
		//foreach ($result->tbk_coupon as $key=>$item){
		//	$item->desc = $this->getContent($item->item_url);
		//}
		return $this->corverTpwd($result->tbk_coupon);
	}
	private function corverTpwd($coupons){
        $results =  json_decode(json_encode($coupons),true);
        foreach ($results as $key => $value) {
            $results[$key]['id'] = $value['num_iid'];
			if(isset($value['coupon_info'])) {
				preg_match_all('/[0-9]+/', $value['coupon_info'], $match);
				$price = isset($value['zk_final_price_wap']) ? $value['zk_final_price_wap'] : $value['zk_final_price'];
				$results[$key]['coupon_price'] = (String)($price - $match[0][1]);
				$results[$key]['coupon_minus'] = $match[0][1];
			}
            $results[$key]['tbpwd'] = $this->corverTbpwd($value['title'],$value['coupon_click_url'],$value['pict_url']);
        }
        return $results;
	}
	private function corverTbpwd($title,$url,$thumb){
        $req = new \TbkTpwdCreateRequest;
        $req->setText($title);
        $req->setUrl($url);
        $req->setUserId($this->config->get('module_taobao_userid'));
        $req->setLogo($thumb);
        $rep = $this->top->execute($req);
        $results = json_decode(json_encode($rep),true);
        if(isset($results['code'])){
            return array(
                'code'=>$results['code']
            );
        }
        return $results['data']['model'];
    }
	public function getContent($url){
		$snoopy = new \Snoopy();
		$sourceURL = $url;
		$snoopy->fetch($sourceURL);
		$result = $snoopy->results;
		preg_match_all('/location.protocol===\?(.*?)\'/sxi',$result,$match);
		echo '<pre>';
		print_r($snoopy);
		echo '</pre>';
//		if(count($match) > 0) {
//			if (isset($match[1][0])) {
//				$desc = $snoopy->fetch('http:' . $match[1][0]);
//				preg_match_all('/desc=\'(.*?)\'/si', $desc->results, $desc_match);
//				return $desc_match[1][0];
//			}
//		}
//		return '';
		//var_dump($desc->results);
	}
	public function corvertUrl($url){
		$req = new \TbkSpreadGetRequest;
		$requests = new \TbkSpreadRequest;
		$requests->url=$url;
		$req->setRequests(json_encode($requests));
		$resp = $this->top->execute($req);
		echo '<pre>';
		//print_r($resp);
		echo '</pre>';
	}
}