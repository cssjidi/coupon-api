<?php
class ControllerCommonTaobao extends Controller {
    public function index($setting){
        $this->load->language('common/taobao');
        $this->load->model('catalog/product');
        $filter_data = array(
            'taobao'        => true,
        );
        if(isset($setting['limit'])){
            $filter_data['sort'] = isset($setting['sort']) ? $setting['sort'] : 'p.date_added';
            $filter_data['order'] = isset($setting['order']) ? $setting['order'] : 'DESC';
            $filter_data['start'] = isset($setting['start']) ? $setting['start'] : 0;
            $filter_data['limit'] = isset($setting['limit']) ? $setting['limit'] : 20;
            $filter_data['filter_category_id'] = isset($setting['filter_category_id']) ? $setting['filter_category_id'] : '';
            $filter_data['filter_filter']      = isset($setting['filter']) ? $setting['filter'] : '';
        }
        $results = $this->model_catalog_product->getProducts($filter_data);
        $data['products'] =  array();
//        echo '<pre>';
//        print_r($results);
//        echo '</pre>';
        if($results) {
            foreach ($results as $result) {
                if($result['coupon_end_time'])
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $zk_final_price = $this->currency->format($this->tax->calculate($result['zk_final_price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $zk_final_price = false;
                }
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $reserve_price = $this->currency->format($this->tax->calculate($result['reserve_price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $reserve_price = false;
                }
                if ($result['coupon_amount']) {
                    $coupon_amount = $this->currency->format($this->tax->calculate($result['coupon_amount'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $coupon_amount = false;
                }
                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $result['image'],
                    'name' => $result['name'],
                    'reserve_price'=> $reserve_price,
                    'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $zk_final_price,
                    'shop_title' => $result['shop_title'],
                    'coupon_amount' => $coupon_amount,
                    'coupon_info' => isset($result['coupon_info']) ? $result['coupon_info'] : false,
                    'coupon_remain_count' => isset($result['coupon_remain_count']) ? $result['coupon_remain_count'] : false,
                    'coupon_total_count' => isset($result['coupon_total_count']) ? $result['coupon_total_count'] : false,
                    'coupon_start_time' => isset($result['coupon_start_time']) ? $result['coupon_start_time'] : false,
                    'coupon_end_time' => isset($result['coupon_end_time']) ? $result['coupon_end_time'] : false,
                    'coupon_remain_date' => isset($result['coupon_end_time']) ? strtotime($result['coupon_end_time']) - strtotime(date('Y-m-d')) : false,
                    'click_url' => isset($result['coupon_click_url']) ? $result['coupon_click_url'] : $result['click_url'],
                    'volume' => $result['volume'],
                    'user_type' => $result['user_type'],
                    'coupon_minus' => $result['coupon_minus'],
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                );
            }
        }
        //print_r($data);
        return $this->load->view('common/taobao', $data);
    }
}