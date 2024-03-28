<?php
/*created by @shiva#qsc*/

namespace local_assign\task;

/**
 * An example of a scheduled task.
 */
class assign_45_days_remove_from_grader_pool extends \core\task\scheduled_task {

    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('remove_from_grader', 'local_assign');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB,$USER,$COURSE,$CFG,$SESSION;
        mtrace('cron started');
        //logic should be made here
        $data = $DB->get_records('course', array('visible'=>1), 'id', 'id, fullname');
        $this->cron_job($data);
        mtrace('cron end');
        // Apply fungus cream.
        // Apply chainsaw.
        // Apply olive oil.
    }
    public function cron_job($data){
        global $DB,$USER,$COURSE,$CFG;
        $timestamp45DaysAgo = strtotime("-45 day");
        $deletionCounter = 0;
        if(!empty($data)){
            foreach($data as $course){
                if($course->id==22){
                    continue;
                }
                
                /////getting course exam object.
                $assignList = $DB->get_records('assign', array('course' => $course->id));
                if(!empty($assignList)){

                    /////checking if course module for exam is enabled/visible or not.
                    foreach($assignList as $assignObj){
                        $courseAssignModuleObj = $DB->get_record('course_modules', array('course'=>$course->id, 'instance' => $assignObj->id, 'module'=>1, 'visible' => 1));

                        if(!empty($courseAssignModuleObj)){
                            ///////Checking if a grader is assigned for atleast one submission of  current exam or not, don't proceed if not assigned.
                            $graderAssigned = $DB->record_exists('assign_graders', array('exam_id'=>$courseAssignModuleObj->id));
                            
                            if($graderAssigned){
                                /////Getting passing grade for the assignment.
                                $assignPassGradeObj = $DB->get_record('grade_items', array('courseid'=>$course->id, 
                                'iteminstance'=>$courseAssignModuleObj->instance, 'itemmodule' => 'assign'));

                                if(!empty($assignPassGradeObj)){
                                    ///////Getting reopened submissions in current assignment.
                                    $reopenedSubmissionsSQL = "SELECT * from {assign_submission} where `assignment`=? AND `status` = ? AND `latest` = ? AND `timemodified`< ?";
                                    $reopenedSubmissions = $DB->get_records_sql($reopenedSubmissionsSQL, [$courseAssignModuleObj->instance, 'reopened', 1, $timestamp45DaysAgo]);

                                    if(!empty($reopenedSubmissions)){
                                        foreach($reopenedSubmissions as $reSubObj){
                                            /////Deleting submission from graders pool.
                                            if($DB->record_exists('assign_graders', array('exam_id'=>$courseAssignModuleObj->id,'student_id'=>$reSubObj->userid))){
                                                $DB->delete_records(
                                                    'assign_graders', 
                                                    array(
                                                        'exam_id' => $courseAssignModuleObj->id,
                                                        'student_id' => $reSubObj->userid
                                                    )
                                                );
                                                $deletionCounter++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return mtrace('Total '.$deletionCounter.' entries removed');
    }//@fn ends

}//class ends