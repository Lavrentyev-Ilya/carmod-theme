<?php global $PKeyP, $CmtBKey; ?>
<div class="CmCmtSection">
    <div class="CmCmtList" id="CmCommentsList">
        <?php global $aComt;
        if(isset($aComt) AND count($aComt)<=0){ ?>
            <div class="CmNoCmts">
                <div class="CmNoCmtTxt"><span><?=Lng_x('There_are_no_reviews_yet');?>..</span></div>
                <?=$aPageSVG['CmNoComments']?>
            </div>
        <?php }else{
            $usHash = getUserHash();
            foreach($aComt as $a){
                if($a['CACT']<=0 AND $usHash != $a['CUSER']){
                    continue;
                }

                $BoxStateClass = 'CmCmtBox-approved';
                if($a['CACT']<=0){
                    $BoxStateClass = 'CmCmtBox-pending';
                }elseif($a['CACT']==1){
                    $BoxStateClass = 'CmCmtBox-rejected';
                } ?>

                <div class="CmCmtBox <?=$BoxStateClass?>">
                    <div class="CmCmtHeader">
                        <div class="CmCmtFooter">
                            <span><?=$a['CNAME']?></span>
                            <span class="CmCmtDate"><?=date("d.m.y H:i", $a['CSTMP']);?></span>
                        </div>
                        <span class="CmCmtRating">
                            <?php for($i = 1; $i <= 5; $i++){
                                echo $i <= $a['CRATE'] ? '&#9733;' : '&#9734;';
                            } ?>
                        </span>
                    </div>
                    <div class="CmCmtText"><?=$a['CTEXT']?></div>
                    <?php if($a['CACT']<=0){ ?>
                        <div class="CmModerLabel"><?=Lng_x('Your_comment_has_been_sent_for_moderation')?></div>
                    <?php } ?>
                    <?php if($a['CACT']==1){ ?>
                        <div class="CmModerLabel"><?=Lng_x('Your_comment_has_not_been_approved_by_the_moderator')?></div>
                    <?php } ?>
                </div>
            <?php }
        } ?>
    </div>

    <div class="CmCmtForm">
        <form id="CmAddCommentForm" novalidate>
            <div class="CmCmtFormGroup CmBoxTextCmt">
                <input type="text" id="CmCName" class="CmCmtFormControl CmCmtNameInput" placeholder="<?=Lng_x('Your_name');?>" required>
                <textarea id="CmCommentText" class="CmCmtFormControl" placeholder="<?=Lng_x('Your_feedback');?>" required></textarea>
            </div>

            <div class="CmCmtFormGroup CmCmtRatingField">
                <label><?=Lng_x('Product_rating');?></label>
                <div class="CmRatingStars">
                    <input type="radio" id="star5" name="CmCmtRatg" value="5" required>
                    <label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="CmCmtRatg" checked value="4">
                    <label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="CmCmtRatg" value="3">
                    <label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="CmCmtRatg" value="2">
                    <label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="CmCmtRatg" value="1">
                    <label for="star1">&#9733;</label>
                </div>
            </div>
            <button type="submit" class="CmCmtSubmitBtn"><?=Lng_x('Send');?></button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productId = '<?=$PKeyP?>';
    const CmtBKey = '<?=$CmtBKey?>';
    const CmAddCommentForm = document.getElementById('CmAddCommentForm');

    if(!CmAddCommentForm){
        return;
    }

    CmAddCommentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const CmCommentText = document.getElementById('CmCommentText').value;
        const CmCName = document.getElementById('CmCName').value;
        const CmCmtRatg = document.querySelector('input[name="CmCmtRatg"]:checked')?.value;

        if(!CmCName || !CmCommentText){
            CreatePopup('error', '<?=Lng_x('Error');?>', '<?=Lng_x('All_fields_are_required');?>');
            return;
        }

        if(!CmCmtRatg){
            document.querySelectorAll('.CmRatingStars label').forEach(function(label){
                label.style.color = '#ff6b6b';
            });
            setTimeout(function(){
                document.querySelectorAll('.CmRatingStars label').forEach(function(label){
                    label.style.color = '';
                });
            }, 1000);
            return;
        }

        const formData = new FormData();
        formData.append('action', 'add_comment');
        formData.append('product_id', productId);
        formData.append('CmtBKey', CmtBKey);
        formData.append('text', CmCommentText);
        formData.append('CName', CmCName);
        formData.append('rating', CmCmtRatg);
        formData.append('CarModAjax', 'Y');

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(function(response){return response.json();})
        .then(function(data){
            if(data.success){
                CreatePopup('success', 'OK', '<?=Lng_x('Your_comment_has_been_sent_for_moderation')?>');
                document.getElementById('CmCName').value = '';
                document.getElementById('CmCommentText').value = '';
            }else{
                CreatePopup('error', 'Error', data.message);
            }
        })
        .catch(function(){
            CreatePopup('error', '<?=Lng_x('Error');?>', 'Network or server error');
        });
    });

    <?php if(isset($_GET['cmt'])){ ?>
        const element = document.getElementById('CmAjaxBoxOENumbers');
        if(element){
            const yOffset = -150;
            const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({top: y, behavior: 'smooth'});
        }
    <?php } ?>
});
</script>
