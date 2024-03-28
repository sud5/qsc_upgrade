<?php

    $sqlRole = "SELECT id FROM {role} WHERE shortname='grader'";
    $rsRole = $DB->get_record_sql($sqlRole);

    $sqlRoleAssignment = "SELECT contextid FROM {role_assignments} where userid=".$USER->id." AND roleid=".$rsRole->id;
    $rsRoleAssignment = $DB->get_record_sql($sqlRoleAssignment);

    $USER->usertype = "";
    if(count($rsRoleAssignment->contextid) > 0){   // grader
        $USER->usertype = 'grader';
    }
    if(is_siteadmin() && (count($rsRoleAssignment->contextid) <= 0)){ // admin
        $USER->usertype = 'mainadmin';
    }
    if(is_siteadmin() && (count($rsRoleAssignment->contextid) > 0)) { // grader and admin both
        $USER->usertype = 'graderasadmin';
    }

    if(($USER->usertype == 'grader' || $USER->usertype == 'graderasadmin' || $USER->usertype == 'mainadmin') &&  ($user->id != $USER->id)){

        $select_header = "SELECT * FROM `mdl_admin_grader_notes` where `user_id` = $user->id ORDER BY id DESC LIMIT 1";
        $adminGraderNoteData = $DB->get_record_sql($select_header); ?>
        
        <section class="node_category">
                <h3>Admin Notes</h3>
            <input type="hidden" id ="lastNote" value='<?php echo htmlspecialchars($adminGraderNoteData->note_message);?>'>
            <textarea class ="no_spl_char" id="id_admin_notes" name="admin_notes" rows="10" style ="width: 95%; resize: unset;"> <?php if($adminGraderNoteData != ""){ echo htmlspecialchars($adminGraderNoteData->note_message); } ?></textarea>
            <div class ="alert alert-block alert-success flashMsgDiv"></div>
        </section>
    <?php
    }
    ?>
<script type='text/javascript'>

    $(document).ready(function(){
    // Admin or grader add note for user
        $(".flashMsgDiv").hide();
        $('#id_admin_notes').on('blur',function(){
            var lastNote = "<?php echo mysqli_real_escape_string(strip_tags(htmlspecialchars($adminGraderNoteData->note_message))); ?>";
            lastNote = lastNote.trim();
            var admin_grader_note = (this.value).trim();
            
            var admin_grader_id = '<?php echo $USER->id; ?>';
            var user_id = '<?php echo $user->id; ?>';
            if((admin_grader_note != '') && (lastNote != admin_grader_note)){
                $.ajax({
                    url: '/user/admin_grader_note_ajax.php',
                    type: 'post',
                    data: {admin_grader_note: admin_grader_note,admin_grader_id: admin_grader_id,user_id: user_id},
                    success: function(response) {
                        console.log(response);
                        $(".flashMsgDiv").html(response);
                       $(".flashMsgDiv").fadeIn( 300 ).delay(2000).fadeOut( 400 );
                    }
                });
            }
        });

       
    });

</script>
<style>
    .alert {
    padding: 12px 12px 12px 12px;
}
</style>  
