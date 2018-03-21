<?php
class ControllerCatalogTaobao extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('catalog/taobao');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/category');
        $data['breadcrumbs'] = array();
        $data['token']  = $this->session->data['user_token'];
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/taobao', 'user_token=' . $this->session->data['user_token'], true)
        );
        $filter = array();
        $page = 1;
        $data['tabs'] = $this->model_catalog_category->getCategories(array(
            'sort'        => 'sort_order',
            'order'       => 'ASC',
        ));

        if(isset($this->request->get['page'])){
            $filter['page'] = $this->request->get['page'];
            $page = $this->request->get['page'];
        }
        if(isset($this->request->get['limit'])){
            $filter['size'] = $this->request->get['limit'];
        }

        if(isset($this->request->get['query'])){
            $search = $this->request->get['query'];
            $data['search'] = $this->request->get['query'];
        }else{
            $search = '女装';
            $data['search'] = '女装';
        }
        $data['url'] = $this->url->link('catalog/taobao', 'user_token=' . $this->session->data['user_token'] . '&query='. $search .'&page=1', true);

        $pagination = new Pagination();
        $pagination->total = 500;
        $pagination->page = $page;
        $pagination->limit = 20;
        $pagination->url = $this->url->link('catalog/taobao', 'user_token=' . $this->session->data['user_token'] .'&page={page}', true);

        $data['pagination'] = $pagination->render();
        //var_dump($this->taobao->getTaobaoCoupon($search,$filter));
        //$data['products'] = $this->taobao->getTaobaoCoupon($search,$filter);
        $data['products'] = $this->taobao->getTaobaoCoupon($search,$filter);

//        foreach ($products as $product){
//            $detail = $this->taobao->getProductDetail($product['num_iid']);
//            $new_product = array_merge($product,$detail);
//            echo '<pre>';
//            print_r($new_product);
//            echo '</pre>';
//        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('catalog/taobao', $data));
    }
    public function search(){
        $json = array();
        if(isset($this->request->get['query'])){
            $json = $this->taobao->getTaobaoCoupon($this->request->get['query']);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
?>