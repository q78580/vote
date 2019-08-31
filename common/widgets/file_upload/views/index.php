<?php
/**
 * @see Yii中文网  http://www.yii-china.com
 * @author Xianan Huang <Xianan_huang@163.com>
 * 图片上传组件
 * 如何配置请到官网（Yii中文网）查看相关文章
 */
 $this->registerJs('
    $(".deletebtn a").click(function (e){
        var con = confirm("确认删除图片?")
        if (!con) return
        var par = $(this).parents(\'.per_upload_con\')
        par.find(\'input\')[0].value = \'\'
        par.find(\'.per_real_img img\').remove()
    })
 ');
use yii\helpers\Html;
?>
<div class="per_upload_con" data-url="<?=$config['serverUrl']?>">
    <div class="per_real_img <?=$attribute?>" domain-url = "<?=$config['domain_url']?>"><?=isset($inputValue)?'<img src="'.$config['domain_url'].$inputValue.'">':''?></div>
    <div class="per_upload_img">图片上传</div>
    <div class="per_upload_text">
        <div class="btn-wrap">
            <div class="upbtn" ><a id="<?=$attribute?>" href="javascript:;" class="btn btn-success green btn-xs choose_btn" style="font-size: 12px;margin-top: -6px">选择图片</a></div>
            <div class="deletebtn" ><a id="<?=$attribute?>" href="javascript:;" class="btn btn-danger btn-xs delete_btn"
                                 style="font-size: 12px;margin-left:20px; margin-top: -6px">删除图片</a></div>
        </div>
        <div class="rule" style="font-size: 7px;margin-bottom: -6px">仅支持文件格式为jpg、jpeg、png以及gif大小在1MB以下的文件</div>
    </div>
    <input up-id="<?=$attribute?>" type="hidden" name="<?=$inputName?>" upname='<?=$config['fileName']?>' value="<?=isset($inputValue)?$inputValue:''?>" filetype="img" />
</div>
