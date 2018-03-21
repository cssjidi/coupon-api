<?php
class ControllerCatalogUnion extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('catalog/union');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/category');
        $data['breadcrumbs'] = array();
        $data['token']  = $this->session->data['user_token'];
        $this->load->model('catalog/product');
        //print_r($this->taobao->getContent('https://detail.tmall.com/item.htm?id=563625235712'));
        //$productDesc = $this->model_catalog_product->getProductDesc();
        //foreach ($productDesc as $pro){
        //    $this->taobao->corvertUrl($pro['item_url']);
        //}
//        echo '<pre>';
//        print_r($this->taobao->getTaobaoFavoritesCategory());
//        echo '</pre>';
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $products = array();
            $categories = $this->model_catalog_category->getCategories();
            $page = (int)$this->request->post['page'];
            $size = $this->request->post['limit'];
            foreach ($categories as $key=>$cate){
                $products[$cate['category_id']] = $this->taobao->getTaobaoCoupon($cate['name'], array(
                    'size' => $size,
                    'page' => $page,
                ));
            }

            $this->model_catalog_product->addProductFromTaobao($products);
//            sleep(30);
//            $productDesc = $this->model_catalog_product->getProductDesc();
//            foreach ($productDesc as $pro){
//                $desc = $this->taobao->getContent($pro['item_url']);
//                echo $desc;
//                echo '<br/>';
//                $this->model_catalog_product->updateProductDesc($desc,$pro['product_id']);
//            }
        }
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/taobao', 'user_token=' . $this->session->data['user_token'], true)
        );
        /*
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
        $data['products'] = $this->taobao->getTaobaoCoupon($search,$filter);
        */
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('catalog/union', $data));
    }
    public function favoriteCategory(){
        $this->load->model('catalog/category');
        $categories = $this->taobao->getTaobaoFavoritesCategory();
        $json = array();
        $this->model_catalog_category->deleteAllCate();
        foreach ($categories as $key=>$category){
            $new_array = array(
                'parent_id' => 0,
                'top'       => 1,
                'sort_order'=> $key,
                'column'    => 1,
                'status'    => 1,
                'favorites_id'=> $category->favorites_id,
                'category_description' => array(
                    '1' => array(
                        'name'          => $category->favorites_title,
                        'description'   => $category->favorites_title,
                        'meta_title'    => $category->favorites_title,
                        'meta_description'=> $category->favorites_title,
                        'meta_keyword'=> $category->favorites_title,
                    )
                ),
                'category_store'    => array(
                    '0' => 0
                )
            );
            $this->model_catalog_category->addCategory($new_array);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function favoriteProduct(){
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $json = array();
        $categories = $this->model_catalog_category->getCategories();
        $products = array();
        foreach ($categories as $category){
            $result = $this->taobao->getTaobaoFavoritesProduct($category);
            $products[$category['category_id']] = $result;
        }
        //var_dump($products);
        $this->model_catalog_product->addProductFromTaobao($products);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
?>