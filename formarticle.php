<?php $isEdit = (isset($data_edit)); ?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <form id="form_article" method="post">
                    <ul class="nav nav-tabs" id="form_tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general_tab" data-toggle="tab" href="#general_content" role="tab" aria-controls="general_content" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="data_tab" data-toggle="tab" href="#data_content" role="tab" aria-controls="data_content" aria-selected="false">Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="link_tab" data-toggle="tab" href="#link_content" role="tab" aria-controls="link_content" aria-selected="false">Links</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="image_tab" data-toggle="tab" href="#image_content" role="tab" aria-controls="image_content" aria-selected="false">Image</a>
                        </li>
                    </ul>
                    <div class="tab-content pt-3" id="article_tab_content">
                        <div class="tab-pane fade show active" id="general_content" role="tabpanel" aria-labelledby="general_tab">
                            <div class="form-group">
                                <label for="title" class="label_required_field">Title</label>
                                <input id="title" name="title" type="text" maxlength="100" class="form-control" value="<?php if($isEdit) echo $data_edit['judul']; ?>" required />
                            </div>
                            <div class="form-group">
                                <label for="short_description" class="label_required_field">Meta Tag Description / Short Description</label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="3" required><?php if($isEdit) echo $data_edit['deskp']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="keywords" class="label_required_field">Meta Tag Keywords</label>
                                <input id="keywords" name="keywords" type="text" maxlength="255" class="form-control" value="<?php if($isEdit) echo $data_edit['kk']; ?>" required />
                            </div>
                            <div class="form-group">
                                <label class="label_required_field">Status</label>
                                <br/>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="status_enabled" name="status" class="custom-control-input" value="1"<?php if(($isEdit && $data_edit['stat'] > 0) || !$isEdit) echo " checked"; ?>>
                                    <label class="custom-control-label" for="status_enabled">Enabled</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="status_disabled" name="status" class="custom-control-input" value="0"<?php if($isEdit && $data_edit['stat'] == 0) echo " checked"; ?>>
                                    <label class="custom-control-label" for="status_disabled">Disabled</label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="data_content" role="tabpanel" aria-labelledby="data_tab">
                            <div class="form-row">
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="country" class="label_required_field">Country</label>
                                    <select id="country" name="country" class="custom-select" required>
                                        <option value=""></option>
                                        <?php
                                        if(isset($data_country) && is_array($data_country) && count($data_country) > 0){
                                            foreach ($data_country as $item){
                                                echo '<option value="'.$item['iso'].'"';
                                                if($isEdit && $item['iso']==$data_edit['iso_country']) echo ' selected="selected"';
                                                echo '>'.$item['nicename'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label for="province" class="label_required_field">State / Province</label>
                                    <select id="province" name="province" class="custom-select" required<?php if(!$isEdit || empty($data_edit['iso_country'])) echo " disabled"; ?>>
                                        <option value=""></option>
                                        <?php
                                        if($isEdit && isset($data_province) && is_array($data_province) && count($data_province) > 0){
                                            foreach($data_province as $item){
                                                echo '<option value="'.$item['state_code'].'"';
                                                if($item['state_code']==$data_edit['state_code']) echo ' selected="selected"';
                                                echo '>'.$item['state'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="city" class="label_required_field">City</label>
                                    <select id="city" name="city" class="custom-select" required <?php if(!$isEdit || empty($data_edit['state_code'])) echo " disabled"; ?>>
                                        <option value=""></option>
                                        <?php
                                        if($isEdit && isset($data_city) && is_array($data_city) && count($data_city) > 0){
                                            foreach($data_city as $item){
                                                echo '<option value="'.$item['id'].'"';
                                                if($item['id']==$data_edit['id_city']) echo ' selected="selected"';
                                                echo '>'.$item['city'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="emails">Email</label>
                                    <input id="emails" name="emails" type="email" maxlength="150" class="form-control" value="<?php if($isEdit) echo $data_edit['email']; ?>"/>
                                </div>
                                 <div class="col-12 col-md-6 mb-3">
                                    <label for="urlmaps">Google Maps</label>
                                    <input id="urlmaps" name="urlmaps" type="url" maxlength="65535" class="form-control" value="<?php if($isEdit) echo $data_edit['googlemaps']; ?>"/>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="phone1">Phone</label>
                                    <input id="phone1" name="phone1" type="tel" class="form-control" value="<?php if($isEdit) echo $data_edit['telp1']; ?>"/>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="phone2">Whatsapp</label>
                                    <input id="phone2" name="phone2" type="tel" class="form-control" value="<?php if($isEdit) echo $data_edit['telp2']; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea id="content" name="content" class="tinymce"><?php if($isEdit) echo $data_edit['isi']; ?></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="link_content" role="tabpanel" aria-labelledby="link_tab">
                            <div class="form-group">
                                <label for="type" class="label_required_field">Type</label>
                                <select id="type" name="type" class="custom-select" required>
                                    <option value=""></option>
                                    <?php
                                    foreach ($ma_status_article as $msaItem){
                                        if ($msaItem['id'] == 0) continue;
                                        ?>
                                        <option <?php echo 'value="'.$msaItem['id'].'"'; if($isEdit && intval($data_edit['stat']) === intval($msaItem['id'])) echo ' selected="selected"'; ?>>
                                            <?php echo $msaItem["keterangan"]; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category" class="label_required_field">Category</label>
                                <select id="category" name="category" class="custom-select" required>
                                    <option value=""></option>
                                    <?php
                                    if(isset($data_category) && is_array($data_category) && count($data_category) > 0){
                                        foreach ($data_category as $item){
                                            echo '<option value="'.$item['id'].'"';
                                            if($isEdit && $item['id']==$data_edit['kategori']) echo ' selected="selected"';
                                            echo '>'.$item['kategori'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="image_content" role="tabpanel" aria-labelledby="image_tab">
                            <div class="form-group">
                                <div class="media">
                                    <img id="preview_thumbnail" class="img-thumbnail align-self-center" src="<?php if($isEdit && !empty($data_edit['url_gbr'])) echo $data_edit['url_gbr']; else echo DEFAULT_NO_IMAGE; ?>" />
                                    <div class="media-body align-self-center pl-3">
                                        <label for="thumbnail" class="label_required_field">Picture Thumbnail (URL)</label>
                                        <input id="thumbnail" name="thumbnail" type="url" class="form-control" maxlength="255" value="<?php if($isEdit) echo $data_edit['url_gbr']; ?>" required/>
                                    </div>
                                </div>
                            </div>
                            <?php require_once 'views/includes/slider-main-table.php'; ?>
                        </div>
                    </div>
                    <?php require_once 'views/includes/form-submit-buttons.php'; ?>
                </form>
            </div>
        </div>
    </div>
</div>