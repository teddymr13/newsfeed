<?php
require_once 'traits/categorytrait.php';
require_once 'traits/locationtrait.php';
require_once 'traits/intropagetrait.php';
require_once 'traits/mainslidertrait.php';
require_once 'traits/settingtrait.php';
require_once 'traits/sitemaptrait.php';

class Newsfeed extends Controller {
    use CategoryTrait, LocationTrait, IntroPageTrait, MainSliderTrait, SettingTrait, SitemapTrait;

    private function concatFilterParamNewsfeed($filter_title, $filter_type, $filter_category, $filter_country, $filter_date_added_from, $filter_date_added_to, $filter_date_modified_from, $filter_date_modified_to){
        $filter_param = '';
        if(!empty($filter_title)) $filter_param = 'filter_title=' . rawurlencode($filter_title);
        if ($filter_type===0 || $filter_type===1 || $filter_type===2 || $filter_type===3 || $filter_type===4 || $filter_type===5) {
            if(!empty($filter_param)) $filter_param .= '&';
            $filter_param .= 'filter_type=' . $filter_type;
        }
        if ($filter_category > 0 || $filter_category === 0) {
            if(!empty($filter_param)) $filter_param .= '&';
            $filter_param .= 'filter_category=' . $filter_category;
        }
        if (!empty($filter_country)) {
            if(!empty($filter_param)) $filter_param .= '&';
            $filter_param .= 'filter_country=' . rawurlencode($filter_country);
        }
        $trueDate = true;
        if (!empty($filter_date_added_from) && !empty($filter_date_added_to) && ($filter_date_added_from > $filter_date_added_to)) $trueDate = false;
        if($trueDate) {
            if (!empty($filter_date_added_from)) {
                if (!empty($filter_param)) $filter_param .= '&';
                $filter_param .= 'filter_date_added_from=' . rawurlencode($filter_date_added_from);
            }
            if (!empty($filter_date_added_to)) {
                if (!empty($filter_param)) $filter_param .= '&';
                $filter_param .= 'filter_date_added_to=' . rawurlencode($filter_date_added_to);
            }
        }
        $trueDate = true;
        if (!empty($filter_date_modified_from) && !empty($filter_date_modified_to) && ($filter_date_modified_from > $filter_date_modified_to)) $trueDate = false;
        if($trueDate) {
            if (!empty($filter_date_modified_from)) {
                if (!empty($filter_param)) $filter_param .= '&';
                $filter_param .= 'filter_date_modified_from=' . rawurlencode($filter_date_modified_from);
            }
            if (!empty($filter_date_modified_to)) {
                if (!empty($filter_param)) $filter_param .= '&';
                $filter_param .= 'filter_date_modified_to=' . rawurlencode($filter_date_modified_to);
            }
        }
        return $filter_param;
    }
    protected function index(){
        if($this->registry->template->login_check){
            $page_id = '9|1';
            if(UserHelper::checkUserAccess($page_id)) {
                $main_url = 'newsfeed';
                if(isset($_POST) && !empty($_POST)){
                    $args = array(
                        'filter_title' => array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK),
                        'filter_type' => array('filter' => FILTER_VALIDATE_INT, 'options' => array('min_range' => 0, 'max_range' => 5)),
                        'filter_category' => array('filter' => FILTER_VALIDATE_INT, 'options' => array('min_range' => 0))
                    );
                    $post = filter_input_array(INPUT_POST, $args);

                    $post['filter_country'] = '';
                    if(isset($_POST['filter_country'])) $post['filter_country'] = Validator::checkIsoCountry($_POST['filter_country']);
                    $post['filter_date_added_from'] = NULL;
                    if(isset($_POST['filter_date_added_from'])) $post['filter_date_added_from'] = Validator::checkDate($_POST['filter_date_added_from']);
                    $post['filter_date_added_to'] = NULL;
                    if(isset($_POST['filter_date_added_to'])) $post['filter_date_added_to'] = Validator::checkDate($_POST['filter_date_added_to']);
                    $post['filter_date_modified_from'] = NULL;
                    if(isset($_POST['filter_date_modified_from'])) $post['filter_date_modified_from'] = Validator::checkDate($_POST['filter_date_modified_from']);
                    $post['filter_date_modified_to'] = NULL;
                    if(isset($_POST['filter_date_modified_to'])) $post['filter_date_modified_to'] = Validator::checkDate($_POST['filter_date_modified_to']);

                    $filter_param = $this->concatFilterParamNewsfeed($post['filter_title'], $post['filter_type'], $post['filter_category'], $post['filter_country'], $post['filter_date_added_from'], $post['filter_date_added_to'], $post['filter_date_modified_from'], $post['filter_date_modified_to']);

                    if(empty($filter_param)) $filter_param = '/'; else $filter_param = '?' . $filter_param;
                    header('Location:' . ROOT_PATH . $main_url . $filter_param);
                }

                $args = array(
                    'url_id' => FILTER_VALIDATE_INT,
                    'orderby' =>array('filter' => FILTER_VALIDATE_INT, 'options' => array('min_range' => 1, 'max_range' => 5)),
                    'filter_title' => array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK),
                    'filter_type' => array('filter' => FILTER_VALIDATE_INT, 'options' => array('min_range' => 0, 'max_range' => 5)),
                    'filter_category' => array('filter' => FILTER_VALIDATE_INT, 'options' => array('min_range' => 0))
                );
                $get = filter_input_array(INPUT_GET, $args);

                if (empty($get['url_id'])) $get['url_id'] = 1;
                if (empty($get['orderby'])) $get['orderby'] = 1;
                $get['order'] = 'DESC';
                if (isset($_GET['order']) && $_GET['order'] === 'ASC') $get['order'] = 'ASC';

                $get['filter_country'] = '';
                if(isset($_GET['filter_country'])) $get['filter_country'] = Validator::checkIsoCountry($_GET['filter_country']);
                $get['filter_date_added_from'] = NULL;
                if(isset($_GET['filter_date_added_from'])) $get['filter_date_added_from'] = Validator::checkDate($_GET['filter_date_added_from']);
                $get['filter_date_added_to'] = NULL;
                if(isset($_GET['filter_date_added_to'])) $get['filter_date_added_to'] = Validator::checkDate($_GET['filter_date_added_to']);
                $get['filter_date_modified_from'] = NULL;
                if(isset($_GET['filter_date_modified_from'])) $get['filter_date_modified_from'] = Validator::checkDate($_GET['filter_date_modified_from']);
                $get['filter_date_modified_to'] = NULL;
                if(isset($_GET['filter_date_modified_to'])) $get['filter_date_modified_to'] = Validator::checkDate($_GET['filter_date_modified_to']);

                $viewmodel = new NewsfeedModel;
                if(empty($get['filter_title']) && !($get['filter_type'] === 0 || $get['filter_type'] === 1 || $get['filter_type'] === 2 || $get['filter_type'] === 3 || $get['filter_type'] === 4 || $get['filter_type'] === 5) && !($get['filter_category'] > 0 || $get['filter_category'] === 0) && empty($get['filter_country']) && empty($get['filter_date_added_from']) && empty($get['filter_date_added_to']) && empty($get['filter_date_modified_from']) && empty($get['filter_date_modified_to']))
                    $data_count = $viewmodel->getArticle();
                else
                    $data_count = $viewmodel->getArticleWithFilter($get['filter_title'], $get['filter_type'], $get['filter_category'], $get['filter_country'], $get['filter_date_added_from'], $get['filter_date_added_to'], $get['filter_date_modified_from'], $get['filter_date_modified_to']);
                $totalrow = count($data_count);
                if($totalrow > 0) $totalhlm = intval(ceil ($totalrow / LIST_ITEM_LIMIT));
                else $totalhlm = 1;
                if($get['url_id'] > $totalhlm) header('Location: ' . ROOT_PATH . $main_url . '/');

                $param = array();
                switch ($get['orderby']) {
                    case 1:
                        $param['order_by'] = array('tgl' => $get['order'], 'judul' => 'ASC');
                        break;
                    case 2:
                        $param['order_by'] = array('judul' => $get['order']);
                        break;
                    case 3:
                        $param['order_by'] = array('kategori_str' => $get['order'], 'judul' => 'ASC');
                        break;
                    case 4:
                        $param['order_by'] = array('stat' => $get['order'], 'kategori_str' => 'ASC', 'judul' => 'ASC');
                        break;
                    case 5:
                        $param['order_by'] = array('last_modified' => $get['order'], 'judul' => 'ASC');
                        break;
                    default:
                        $param['order_by'] = array('tgl' => $get['order'], 'judul' => 'ASC');
                        break;
                }
                $limitervalue = array();
                if ($totalrow > LIST_ITEM_LIMIT) {
                    $offset = ($get['url_id'] - 1) * LIST_ITEM_LIMIT;
                    $limitervalue["count"] = LIST_ITEM_LIMIT;
                    if (!empty($offset)) $limitervalue["offset"] = $offset;
                }
                if (!empty($limitervalue)) $param['limit'] = $limitervalue;

                if(empty($get['filter_title']) && !($get['filter_type'] === 0 || $get['filter_type'] === 1 || $get['filter_type'] === 2 || $get['filter_type'] === 3 || $get['filter_type'] === 4 || $get['filter_type'] === 5) && !($get['filter_category'] > 0 || $get['filter_category'] === 0) && empty($get['filter_country']) && empty($get['filter_date_added_from']) && empty($get['filter_date_added_to']) && empty($get['filter_date_modified_from']) && empty($get['filter_date_modified_to']))
                    $this->registry->template->data_list = $data_list = $viewmodel->getArticle($param, '', true);
                else
                    $this->registry->template->data_list = $data_list = $viewmodel->getArticleWithFilter($get['filter_title'], $get['filter_type'], $get['filter_category'], $get['filter_country'], $get['filter_date_added_from'], $get['filter_date_added_to'], $get['filter_date_modified_from'], $get['filter_date_modified_to'], $param, true);

                $countrymodel = new CountryModel;
                $this->registry->template->data_country = $countrymodel->getCountry(array('service_exist'=>1, 'order_by'=>array('nicename'=>'ASC')));
                $this->registry->template->data_category = $viewmodel->getArticleCategories(array('order_by'=>array('kategori'=>'ASC')), "", true);

                $this->registry->template->main_url = $main_url;
                $this->registry->template->hlm = $get['url_id'];
                $this->registry->template->totalrow = $totalrow;
                $this->registry->template->totalhlm = $totalhlm;

                if (!($get['orderby'] == 1 && $get['order'] == 'DESC')) {
                    $this->registry->template->orderby = $get['orderby'];
                    $this->registry->template->order = strtolower($get['order']);
                }

                $this->registry->template->filter_param = $this->concatFilterParamNewsfeed($get['filter_title'], $get['filter_type'], $get['filter_category'], $get['filter_country'], $get['filter_date_added_from'], $get['filter_date_added_to'], $get['filter_date_modified_from'], $get['filter_date_modified_to']);

                $this->registry->template->page_title = $this->registry->template->header_title = "Newsfeed";
                $this->registry->template->page_id = $page_id;

                $this->registry->template->ma_status_article = $viewmodel->getMaStatusArticle();

                $breadcrumb = array();
                $breadcrumb[0]['str'] = "Newsfeed";
                $breadcrumb[0]['active'] = true;
                $this->registry->template->breadcrumb = $breadcrumb;

                if ($totalrow > 0) {
                    $jsSrc = array();
                    $jsSrc[0]['type'] = "js";
                    $jsSrc[0]['src'] = "assets/js/bundles/list-data.js";
                    $jsSrc[1]['type'] = "js";
                    $jsSrc[1]['src'] = "assets/js/bundles/list-general-remove-button-function.js";
                    $jsSrc[2]['type'] = "js";
                    $jsSrc[2]['src'] = $this->registry->default_js_src;
                    $this->registry->template->jsSrc = $jsSrc;
                }

                $this->returnView();
            }
            else Redirect::home();
        }
        else Redirect::signin();
    }
    private function formarticle($type){
        if($this->registry->template->login_check){
            $page_id = '9|1';
            if(UserHelper::checkUserAccess($page_id)) {
                $viewmodel = new NewsfeedModel;
                $parent_url = 'newsfeed/';
                $checkUrlId = false;
                if($type==='Add New') $checkUrlId = true;
                elseif($type==='Edit'){
                    $url_id = NULL;
                    if(isset($_GET['url_id'])) $url_id = intval(filter_var($_GET['url_id'], FILTER_VALIDATE_INT));
                    if(!empty($url_id)) {
                        $data_edit = $viewmodel->getArticle(array('id' => $url_id));
                        if ($data_edit && is_array($data_edit) && count($data_edit) == 1) $checkUrlId = true;
                        else header('Location: ' . ROOT_PATH . $parent_url);
                    }
                    else header('Location: ' . ROOT_PATH . $parent_url);
                }

                if($checkUrlId){
                    if (isset($_POST) && !empty($_POST)) {
                        $args = array(
                            'short_description' => array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK),
                            'status' => FILTER_VALIDATE_INT,
                            'category' => FILTER_VALIDATE_INT,
                            'type' => array('filter' => FILTER_VALIDATE_INT, 'options' => array('min_range' => 1, 'max_range' => 5)),
                            'city' => FILTER_VALIDATE_INT,
                            'emails' => FILTER_VALIDATE_EMAIL,
                            'urlmaps' => array('filter' => FILTER_VALIDATE_URL, 'flags' => FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED),
                            'phone1' => FILTER_SANITIZE_NUMBER_INT,
                            'phone2' => FILTER_SANITIZE_NUMBER_INT,
                            'thumbnail' => array('filter' => FILTER_VALIDATE_URL, 'flags' => FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED)
                        );
                        if($type==='Edit') $args['hid_id'] = FILTER_VALIDATE_INT;
                        $post = filter_input_array(INPUT_POST, $args);
                        $post['title'] = NULL;
                        if(isset($_POST['title'])) $post['title'] = filter_var(MainHelper::removeQuote(MainHelper::quotTo39($_POST['title'])), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK);
                        $post['title_url'] = NULL;
                        if(isset($_POST['title'])) $post['title_url'] = MainHelper::getUrlString(filter_var(MainHelper::removeQuote($_POST['title']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK));
                        $post['keywords'] = NULL;
                        if(isset($_POST['keywords'])) $post['keywords'] = Validator::checkKeyword($_POST['keywords']);
                        $post['country'] = NULL;
                        if(isset($_POST['country'])) $post['country'] = Validator::checkIsoCountry($_POST['country']);  
                        $post['province'] = NULL;
                        if(isset($_POST['province'])) $post['province'] = Validator::checkAlphaNumeric($_POST['province']);
                        $post['content'] = NULL;
                        if(isset($_POST['content'])) $post['content'] = MainHelper::quotTo39(Validator::purifyHtml($_POST['content']));

                        if (!empty($post['title']) && !empty($post['short_description']) && !empty($post['keywords']) && ($post['status']==0||$post['status']==1) && !empty($post['category']) && !empty($post['type']) && !empty($post['country']) && !empty($post['province']) && !empty($post['city']) && !empty($post['thumbnail'])) {
                            if(empty($post['content'])) $post['content'] = '';
                            if (empty($post['emails'])) $post['emails'] = '';
                            if (empty($post['urlmaps'])) $post['urlmaps'] = '';
                            if($post['status'] > 0) $stat = $post['type'];
                            else $stat = $post['status'];

                            if($type==='Add New') {
                                $viewmodel = new NewsfeedModel(NULL, 'i');
                                $datetime_now = date('Y-m-d H:i:s');
                                $new_id = time();

                                if ($viewmodel->insertArticle(array('id'=>$new_id, 'judul'=>$post['title'], 'url_judul'=>$post['title_url'], 'kategori'=>$post['category'], 'kk'=>$post['keywords'], 'deskp'=>$post['short_description'], 'isi'=>$post['content'], 'url_gbr'=>$post['thumbnail'], 'iso_country'=>$post['country'], 'id_city'=>$post['city'], 'email'=>$post['emails'], 'googlemaps'=>$post['urlmaps'], 'tgl'=>$datetime_now, 'telp1'=>$post['phone1'], 'telp2'=>$post['phone2'], 'last_modified'=>$datetime_now, 'author'=>$_SESSION['user_id'], 'last_modified_by'=>$_SESSION['user_id'], 'stat'=>$stat))) {
                                    $this->insertMainSlider('article', $new_id, $_POST['picture'], $_POST['video_or_link'], $_POST['order']);
                                    Messages::setMsg('Successfully add new article.', 'success', 'Success!');
                                }
                                else Messages::setMsg('Failed to add new article.', 'danger', 'Error!');
                            }
                            elseif($type==='Edit') {
                                if (!empty($post['hid_id']) && $post['hid_id'] == $url_id) {
                                    $viewmodel = new NewsfeedModel(NULL, 'u');
                                    if ($viewmodel->updateArticle(array('judul'=>$post['title'], 'url_judul'=>$post['title_url'], 'kategori'=>$post['category'], 'kk'=>$post['keywords'], 'deskp'=>$post['short_description'], 'isi'=>$post['content'], 'url_gbr'=>$post['thumbnail'], 'iso_country'=>$post['country'], 'id_city'=>$post['city'], 'email'=>$post['emails'], 'googlemaps'=>$post['urlmaps'], 'last_modified'=>date('Y-m-d H:i:s'), 'telp1'=>$post['phone1'], 'telp2'=>$post['phone2'], 'last_modified_by'=>$_SESSION['user_id'], 'stat'=>$stat), array('id' => $post['hid_id']))) {
                                        $this->insertMainSlider('article', $post['hid_id'], $_POST['picture'], $_POST['video_or_link'], $_POST['order'], true);

                                        $viewmodel = new NewsfeedModel;
                                        $data_edit = $viewmodel->getArticle(array('id' => $url_id));
                                        Messages::setMsg('Successfully edit article.', 'success', 'Success!');
                                    }
                                    else Messages::setMsg('Failed to edit article.', 'danger', 'Error!');
                                }
                                else Messages::setMsg('Invalid ID.', 'danger', 'Error!');
                            }
                        }
                        else Messages::setMsg('Incomplete input data.', 'danger', 'Error!');
                    }

                    $this->registry->template->ma_status_article = $viewmodel->getMaStatusArticle();

                    $countrymodel = new CountryModel;
                    $this->registry->template->data_country = $countrymodel->getCountry(array('service_exist'=>1,'order_by'=>array('nicename'=>'ASC')));

                    $this->registry->template->data_category = $viewmodel->getArticleCategories(array('order_by'=>array('kategori'=>'ASC')), "", true);
                    if($type==='Edit') {
                        $iso_country = strtolower($data_edit[0]['iso_country']);
                        $this->registry->template->data_province = $countrymodel->getState($iso_country, array('order_by'=>array('state'=>'ASC')));
                        $data_city = $countrymodel->getCity(strtolower($iso_country), array('id'=>$data_edit[0]['id_city']));
                        $data_edit[0]['state_code'] = $data_city[0]['state_code'];
                        $this->registry->template->data_city = $countrymodel->getCity(strtolower($iso_country), array('state_code'=>$data_city[0]['state_code'], 'order_by'=>array('city'=>'ASC')));
                        $this->registry->template->data_edit_main_pic = $viewmodel->getMainPic('article', $url_id);
                        $this->registry->template->data_edit = $data_edit[0];
                    }

                    $this->registry->template->page_title = $type . " Article - Newsfeed";
                    $this->registry->template->header_title = $type . " Articlee";
                    $this->registry->template->page_id = $page_id;

                    $breadcrumb = array();
                    $breadcrumb[0]['str'] = "Newsfeed";
                    $breadcrumb[0]['href'] = "newsfeed/";
                    $breadcrumb[1]['str'] = $type;
                    $breadcrumb[1]['active'] = true;
                    $this->registry->template->breadcrumb = $breadcrumb;

                    $jsSrc = array();
                    $jsSrc[0]['type'] = "js";
                    $jsSrc[0]['src'] = "assets/js/bundles/TinyMCE-4.7.5/tinymce.min.js";
                    $jsSrc[1]['type'] = "js";
                    $jsSrc[1]['src'] = "assets/js/bundles/tinymce-init.js";
                    $jsSrc[2]['type'] = "js";
                    $jsSrc[2]['src'] = "assets/js/bundles/form-refresh-select-state-city.js";
                    $jsSrc[3]['type'] = "js";
                    $jsSrc[3]['src'] = "assets/js/bundles/slider-main.js";
                    $jsSrc[4]['type'] = "js";
                    $jsSrc[4]['src'] = "assets/js/newsfeed/formarticle.js";
                    $this->registry->template->jsSrc = $jsSrc;

                    $this->registry->imageCSP .= " https://i.ytimg.com blob:";
                    $this->registry->styleCSP .= " 'unsafe-inline'";

                    $this->returnView(true, 'newsfeed/formarticle.php');
                }
                else header('Location: ' . ROOT_PATH . $parent_url);
            }
            else Redirect::home();
        }
        else Redirect::signin();
    }
    protected function addarticle(){
        $this->formarticle('Add New');
    }
    protected function editarticle(){
        $this->formarticle('Edit');
    }
    protected function ajax_remove_article(){
        if($this->registry->template->login_check) {
            $page_id = '9|1';
            if (UserHelper::checkUserAccess($page_id)) {
                $data_id = NULL;
                if(isset($_POST['data_id'])) $data_id = intval(filter_var($_POST['data_id'], FILTER_VALIDATE_INT));
                if(!empty($data_id)){
                    $viewmodel = new NewsfeedModel(NULL, 'd');
                    if ($viewmodel->deleteArticle(array("id" => $data_id))) $flag = 1;
                    else $flag = 2;
                }
                else $flag = 3;
            }
            else $flag = 4;
        }
        else $flag = 5;

        if($flag == 1) Messages::setMsg('Remove Success.', 'success', 'Succes!');
        echo $flag;
    }

    protected function editintropage(){
        if($this->registry->template->login_check){
            $page_id = '9|2';
            if(UserHelper::checkUserAccess($page_id)) {
                $url_id = NULL;
                if(isset($_GET['url_id'])) $url_id = intval(filter_var($_GET['url_id'], FILTER_VALIDATE_INT));

                $parent_url = "newsfeed/intro-page/";

                if(!empty($url_id)) {
                    $countrymodel = new CountryModel;
                    $check_country = $countrymodel->getCountry(array('id'=>$url_id, 'service_exist'=>1));
                    if($check_country && is_array($check_country) && count($check_country) == 1 ) {
                        $data_edit = [];
                        $data_edit[0]['id'] = $url_id;
                        $viewmodel = new NewsfeedModel;
                        $data_edit_main_pic = $viewmodel->getMainPic('article', $url_id);
                        if (isset($_POST) && !empty($_POST)) {
                            if($this->insertMainSlider('article', $url_id, $_POST['picture'], $_POST['video_or_link'], $_POST['order'], true)) {
                                $data_edit_main_pic = $viewmodel->getMainPic('article', $url_id);
                                Messages::setMsg('Successfully update newsfeed intro page.', 'success', 'Success!');
                            }
                            else Messages::setMsg('Failed to update newsfeed intro page.', 'danger', 'Error!');
                        }

                        $this->registry->template->data_edit_main_pic = $data_edit_main_pic;
                        $this->registry->template->data_edit = $data_edit[0];

                        $this->registry->template->page_title = "Edit | Intro Page - Newsfeed";
                        $this->registry->template->header_title = "Edit Intro Page";
                        $this->registry->template->page_id = $page_id;
                        $this->registry->template->cancelUrl = $parent_url;

                        $breadcrumb = array();
                        $breadcrumb[0]['str'] = "Newsfeed";
                        $breadcrumb[1]['str'] = "Intro Page";
                        $breadcrumb[1]['href'] = $parent_url;
                        $breadcrumb[2]['str'] = "Edit";
                        $breadcrumb[2]['active'] = true;
                        $this->registry->template->breadcrumb = $breadcrumb;

                        $jsSrc = array();
                        $jsSrc[0]['type'] = "js";
                        $jsSrc[0]['src'] = 'assets/js/bundles/slider-main.js';
                        $jsSrc[1]['type'] = "js";
                        $jsSrc[1]['src'] = $this->registry->default_js_src;
                        $this->registry->template->jsSrc = $jsSrc;

                        $this->registry->imageCSP .= " https://i.ytimg.com";

                        $this->returnView();
                    }
                    else header('Location: ' . ROOT_PATH . $parent_url);
                }
                else header('Location: ' . ROOT_PATH . $parent_url);
            }
            else Redirect::home();
        }
        else Redirect::signin();
    }
}
?>