<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="card-header" data-toggle="collapse" data-target="#content_filter" aria-expanded="true" aria-controls="content_filter">
                Filters
            </div>
            <div id="content_filter" class="collapse show">
                <div class="card-body">
                    <form method="post">
                        <div class="row align-items-end">
                            <div class="col-lg-6">
                                <label>Date Added</label>
                                <div class="form-row">
                                    <div class="col mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">from</div>
                                            </div>
                                            <input id="filter_date_added_from" name="filter_date_added_from" type="date" class="form-control" value="<?php if(isset($_GET['filter_date_added_from'])) echo Validator::checkDate(rawurldecode($_GET['filter_date_added_from'])); ?>" />
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">to</div>
                                            </div>
                                            <input id="filter_date_added_to" name="filter_date_added_to" type="date" class="form-control" value="<?php if(isset($_GET['filter_date_added_to'])) echo Validator::checkDate(rawurldecode($_GET['filter_date_added_to'])); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label>Date Modified</label>
                                <div class="form-row">
                                    <div class="col mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">from</div>
                                            </div>
                                            <input id="filter_date_modified_from" name="filter_date_modified_from" type="date" class="form-control" value="<?php if(isset($_GET['filter_date_modified_from'])) echo Validator::checkDate(rawurldecode($_GET['filter_date_modified_from'])); ?>" />
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">to</div>
                                            </div>
                                            <input id="filter_date_modified_to" name="filter_date_modified_to" type="date" class="form-control" value="<?php if(isset($_GET['filter_date_modified_to'])) echo Validator::checkDate(rawurldecode($_GET['filter_date_modified_to'])); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="filter_title">Title</label>
                                    <input id="filter_title" name="filter_title" type="text" class="form-control" maxlength="65" value="<?php if(isset($_GET['filter_title'])) echo filter_var(rawurldecode($_GET['filter_title']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_BACKTICK); ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="filter_category">Category</label>
                                    <select id="filter_category" name="filter_category" class="custom-select">
                                        <option value=""></option>
                                        <option value="0"<?php if(isset($_GET['filter_category']) && ($_GET['filter_category'] == 0 || $_GET['filter_category'] == '0')) echo ' selected="selected"'; ?>>Uncategorized</option>
                                        <?php
                                        if(isset($data_category) && is_array($data_category) && count($data_category) > 0){
                                            foreach ($data_category as $item){
                                                echo '<option value="'.$item['id'].'"';
                                                if(isset($_GET['filter_category']) && $item['id']==$_GET['filter_category']) echo ' selected="selected"';
                                                echo '>'.$item['kategori'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="filter_type">Type</label>
                                    <select id="filter_type" name="filter_type" class="custom-select">
                                        <option value=""></option>
                                        <?php
                                        foreach ($ma_status_article as $msaItem){
                                            ?>
                                            <option <?php echo 'value="'.$msaItem['id'].'"'; if(isset($_GET['filter_type']) && (intval($_GET['filter_type']) === intval($msaItem['id']))) echo ' selected="selected"'; ?>>
                                                <?php echo $msaItem["keterangan"]; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="filter_country">Country</label>
                                    <select id="filter_country" name="filter_country" class="custom-select">
                                        <option value=""></option>
                                        <?php
                                        if(isset($data_country) && is_array($data_country) && count($data_country) > 0){
                                            foreach ($data_country as $item){
                                                echo '<option value="'.$item['iso'].'"';
                                                if(isset($_GET['filter_country']) && $item['iso']==$_GET['filter_country']) echo ' selected="selected"';
                                                echo '>'.$item['nicename'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-light">
                                        <i class="fas fa-fw fa-filter"></i>&nbsp;Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$isCanAdd = true;
$isCanRemove = true;
$cardTitle = 'Article List';
$urlAddPage = 'newsfeed/add-article/';

require_once 'views/includes/list-top-part.php';

if($is_valid_data) {
    if ($count_row > 0) {
        ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <?php require_once 'views/includes/list-table-caption.php'; ?>
                <thead>
                <tr>
                    <th scope="col">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check_all"
                                   id="check_all">
                            <label class="custom-control-label" for="check_all"></label>
                        </div>
                    </th>
                    <th scope="col"><?php ViewHelper::thLink($main_url, $hlm, $orderby, $order, 2, 'ID', $filter_param); ?></th>
                    <th scope="col"><?php ViewHelper::thLink($main_url, $hlm, $orderby, $order, 2, 'Title', $filter_param); ?></th>
                    <th scope="col"><?php ViewHelper::thLink($main_url, $hlm, $orderby, $order, 3, 'Category', $filter_param); ?></th>
                    <th scope="col"><?php ViewHelper::thLink($main_url, $hlm, $orderby, $order, 4, 'Type', $filter_param); ?></th>
                    <th scope="col"><?php ViewHelper::thLink($main_url, $hlm, $orderby, $order, 1, 'Date Added', $filter_param, true); ?></th>
                    <th scope="col"><?php ViewHelper::thLink($main_url, $hlm, $orderby, $order, 5, 'Date Modified', $filter_param); ?></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($data_list as $item) {
                    ?>
                    <tr>
                        <td scope="row">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       name="check_row" value="<?php echo $item['id']; ?>"
                                       id="check_row_<?php echo $item['id']; ?>">
                                <label class="custom-control-label"
                                       for="check_row_<?php echo $item['id']; ?>"></label>
                            </div>
                        </td>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo $item['judul']; ?></td>
                        <td><?php echo $item['kategori_str']; ?></td>
                        <td>
                            <?php
                            foreach ($ma_status_article as $msaItem){
                                if(intval($item['stat']) === intval($msaItem['id'])) {
                                    echo $msaItem['keterangan'];
                                    break;
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $explode_date = explode(' ', $item['tgl']);
                            echo $explode_date[0];
                            ?>
                        </td>
                        <td>
                            <?php
                            $explode_date = explode(' ', $item['last_modified']);
                            echo $explode_date[0];
                            ?>
                        </td>
                        <td class="td_button_action">
                            <a role="button" class="btn btn-outline-warning btn-sm"
                               href="newsfeed/edit-article-<?php echo $item['id'] ?>/"
                               data-toggle="tooltip" title="Edit">
                                <i class="far fa-fw fa-edit"></i>
                            </a>
                            <button type="button" name="button_remove"
                                    data-id-row="<?php echo $item['id']; ?>"
                                    class="btn btn-outline-danger btn-sm" data-toggle="tooltip"
                                    title="Remove">
                                <i class="far fa-fw fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <?php
        require_once 'views/includes/list-pager.php';
    }
    else echo "No Data Found.";
}
else echo "Invalid array data.";

require_once 'views/includes/list-bottom-part.php';
?>