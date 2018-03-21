<?php
class ControllerAppCategories extends RestController {
    public function index($args) {
        $this->load->model('catalog/category');
        $this->load->model('tool/image');
        $categories = $this->model_catalog_category->getCategories();
        //$this->showError($categories);
       // $categories = $this->model_catalog_category->getCategory($cid);

//        $categories_id = array();
        $data = array();
        if($categories) {
            foreach ($categories as $index=>$category) {
                $data[] = array(
                    'cid'           => $category['category_id'],
                    'name'          => $category['name'],
                    'favorites_id'        => $category['favorites_id'],
                    'status'        => $category['status'],
                    'icon'          => $this->model_tool_image->resize($category['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height')),
                );
            }
        }

        return $data;
    }
    public function products($args){
        $cid = isset($args['first_id']) ? $args['first_id'] : 0;
        $json = array();

        $this->load->language('product/category');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $this->load->model('catalog/product');
        $filter_data = array(
            'taobao'        => true,
            'sort'  => isset($this->request->get['sort']) ? $this->request->get['sort'] : 'p.date_added',
            'order'  => isset($this->request->get['order']) ? $this->request->get['order'] : 'DESC',
            'limit'  => isset($this->request->get['limit']) ? $this->request->get['limit'] : 20,
            'start'  => isset($this->request->get['page']) ? $this->request->get['page'] : 1,
            'filter_category_id'  => $cid,
        );
        $results = $this->model_catalog_product->getProducts($filter_data);
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
                $json[] = array(
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
        return $json;
    }
}
