<?php
require_once 'traits/mainslidermodeltrait.php';
class NewsfeedModel extends Model{
    use MainSliderModelTrait;

    public function getArticle($param = NULL, $sql_str = "", $join = false){
        if(empty($sql_str))
            if($join) $sql_str = "SELECT article.id as id, article.judul as judul, article.url_judul as url_judul, article.iso_country as iso_country, article.email as email, article.googlemaps as googlemaps, article.telp1 as telp1, article.telp2 as telp2, article.tgl as tgl, article.last_modified as last_modified, article.stat as stat, article_categories.kategori as kategori_str, article_categories.url_kat as url_kat FROM article LEFT JOIN article_categories ON article.kategori = article_categories.id";
            else $sql_str = "SELECT id, judul, kategori, kk, deskp, isi, url_gbr, iso_country, id_city, email, googlemaps, telp1, telp2, stat FROM article";
        return $this->basicQuerySelect($param, $sql_str);
    }
    public function getArticleWithFilter($filter_title, $filter_type, $filter_category, $filter_country, $filter_date_added_from, $filter_date_added_to, $filter_date_modified_from, $filter_date_modified_to, $param = NULL, $join = false){
        $condition = "";
        if(!empty($filter_title)) $condition = " WHERE article.judul LIKE :judul";
        if($filter_type === 0 || $filter_type === 1 || $filter_type === 2 || $filter_type === 3 || $filter_type === 4 || $filter_type === 5){
            if(empty($condition)) $condition = " WHERE";
            else $condition .= " AND";
            $condition .= " article.stat = :stat";
        }
        if($filter_category > 0 || $filter_category === 0){
            if(empty($condition)) $condition = " WHERE";
            else $condition .= " AND";
            $condition .= " article.kategori = :kategori";
        }
        if(!empty($filter_country)){
            if(empty($condition)) $condition = " WHERE";
            else $condition .= " AND";
            $condition .= " article.iso_country = :isocountry";
        }
        if(!empty($filter_date_added_from)){
            if(empty($condition)) $condition = " WHERE";
            else $condition .= " AND";
            $condition .= " article.tgl >= :dateaddedfrom";
        }
        if(!empty($filter_date_added_to)){
            if(empty($condition)) $condition = " WHERE";
            else $condition .= " AND";
            $condition .= " article.tgl <= :dateaddedto";
        }
        if(!empty($filter_date_modified_from)){
            if(empty($condition)) $condition = " WHERE";
            else $condition .= " AND";
            $condition .= " article.last_modified >= :datemodifiedfrom";
        }
        if(!empty($filter_date_modified_to)){
            if(empty($condition)) $condition = " WHERE";
            else $condition .= " AND";
            $condition .= " article.last_modified >= :datemodifiedto";
        }

        if($join) {
            $sql_str = "SELECT article.id as id, article.judul as judul, article.tgl as tgl, article.last_modified as last_modified, article.stat as stat, article_categories.kategori as kategori_str FROM article LEFT JOIN article_categories ON article.kategori = article_categories.id" . $condition;
        }
        else $sql_str = "SELECT article.id FROM article" . $condition;

        if(isset($param['order_by'])) $sql_str .= $this->concatOrder($param['order_by']);
        if(isset($param['limit'])) $sql_str .= $this->concatLimiter($param['limit']);

        $this->query($sql_str);
        if(!empty($filter_title)) $this->bind(':judul', "%" . $filter_title . "%");
        if($filter_type === 0 || $filter_type === 1 || $filter_type === 2 || $filter_type === 3 || $filter_type === 4 || $filter_type === 5) $this->bind(':stat', $filter_type);
        if($filter_category > 0 || $filter_category === 0) $this->bind(':kategori', $filter_category);
        if(!empty($filter_country)) $this->bind(':isocountry', $filter_country);
        if(!empty($filter_date_added_from)) $this->bind(':dateaddedfrom', $filter_date_added_from . " 00:00:00");
        if(!empty($filter_date_added_to)) $this->bind(':dateaddedto', $filter_date_added_to . " 23:59:59");
        if(!empty($filter_date_modified_from)) $this->bind(':datemodifiedfrom', $filter_date_modified_from . " 00:00:00");
        if(!empty($filter_date_modified_to)) $this->bind(':datemodifiedto', $filter_date_modified_to . " 23:59:59");
        return $this->resultSet();
    }
    public function insertArticle($param, $sql_str = "INSERT INTO article"){
        return $this->basicQueryInsert($param, $sql_str);
    }
    public function updateArticle($set, $param = NULL, $sql_str = "UPDATE article"){
        return $this->basicQueryUpdate($set, $param, $sql_str);
    }
    public function deleteArticle($param = NULL, $sql_str = "DELETE FROM article"){
        return $this->basicQueryDelete($param, $sql_str);
    }

    public function getArticleCategories($param = NULL, $sql_str = "", $exception = false){
        if($exception){
            if(empty($sql_str)) $sql_str = "SELECT id, kategori FROM article_categories WHERE id > 0";
            if(!empty($param)){
                if(isset($param['order_by'])) $sql_str .= $this->concatOrder($param['order_by']);
                if(isset($param['limit'])) $sql_str .= $this->concatLimiter($param['limit']);
            }
            $this->query($sql_str);
            return $this->resultSet();
        }
        else {
            $sql_str = "SELECT id, kategori FROM article_categories";
            return $this->basicQuerySelect($param, $sql_str);
        }
    }
    public function insertArticleCategories($param, $sql_str = "INSERT INTO article_categories"){
        return $this->basicQueryInsert($param, $sql_str);
    }
    public function updateArticleCategories($set, $param = NULL, $sql_str = "UPDATE article_categories"){
        return $this->basicQueryUpdate($set, $param, $sql_str);
    }
    public function deleteArticleCategories($param = NULL, $sql_str = "DELETE FROM article_categories"){
        return $this->basicQueryDelete($param, $sql_str);
    }

    public function deleteArticleMainPic($param = NULL, $sql_str = "DELETE FROM article_main_pic"){
        return $this->basicQueryDelete($param, $sql_str);
    }
    public function getMaStatusArticle($param = NULL, $sql_str = "SELECT id, keterangan FROM ma_status_article"){
        return $this->basicQuerySelect($param, $sql_str);
    }
}
?>