<?php

namespace api\modules\course\controllers;
use common\library\tool;
use common\models\CourseNotice;
use common\models\CourseReview;
use common\models\Lecturer;
use Yii;
use api\controllers\AbstractController;
use yii\web\Controller;

class CourseController extends AbstractController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCourseData()
    {
        $model = new CourseNotice();
        $course = $model->find()->where(['status'=>0])->asArray()->all();
        $loadArr=array($course);
        $this->_formatResponse(1,'正常',$loadArr);
    }

    public function actionIndexInfo(){
        $model = new Lecturer();
        $lecturer = $model->find()->where(['le_status'=>0])->orderBy(['le_weight'=>SORT_ASC])->limit(4)->all();

        //课程预告
        $course_model = new CourseNotice();
        $course = $course_model->find()->joinWith('lecturer')->joinWith('courseReview')
            ->where(['hql_course_notice.status'=>0])->limit(5)
            ->orderBy(['createtime'=>SORT_DESC])->all();
        //回顾
        $course_review = new CourseReview();
        $share_time = time();
        $review = $course_model->find()->joinWith('courseReview')->andOnCondition("hql_course_review.content!=''")->where(['hql_course_notice.status'=>0])->limit(3)
                    ->orderBy(['createtime'=>SORT_DESC])->asArray()->all();
        $loadArr=array('lec'=>$lecturer,'course'=>$course,'review'=>$review);
        $this->_formatResponse(1,'正常',$loadArr);
    }

    public function actionLecturer(){
        $id = Yii::$app->request->post('id');
        $model = new Lecturer();
        $lecturer = $model->find()->where(['le_status'=>0,'leid'=>$id])->asArray()->one();
        $lecturer['le_title'] = str_replace(PHP_EOL,'<br/>',$lecturer['le_title']);
        //$lecturer['le_title'] = nl2br($lecturer['le_title']);
        $loadArr = ['lec'=>$lecturer];
        $this->_formatResponse(1,'正常',$loadArr);
    }

    public function actionLecturerList(){
        $model = new Lecturer();
        $lecturer = $model->find()->where(['le_status'=>0])->orderBy(['le_weight'=>SORT_ASC])->all();
        $loadArr = ['lec'=>$lecturer];
        $this->_formatResponse(1,'正常',$loadArr);
    }

    public function actionCourseList(){
        //课程预告
        $params = Yii::$app->request->post();
        $page = isset($params['page']) ? $params['page'] : 1;
        $rows = isset($params['rows']) ? $params['rows'] : 8;
        $course_model = new CourseNotice();
        $total = $course_model->find()->joinWith('lecturer')->joinWith('courseReview')->count();
        $totalPage =  ceil($total/$rows);
        $course = $course_model->find()->joinWith('lecturer')->joinWith('courseReview')->offset($rows*($page-1))->limit($rows)
            ->where(['hql_course_notice.status'=>0])
            ->orderBy(['createtime'=>SORT_DESC])->all();
        $loadArr = ['course'=>$course];
        $this->_formatResponse(1,'正常',$loadArr,$totalPage);
    }
    public function actionReviewList(){
        //课程回顾
        $params = Yii::$app->request->post();
        $page = isset($params['page']) ? $params['page'] : 1;
        $rows = isset($params['rows']) ? $params['rows'] : 8;
        $course_model = new CourseNotice();
        $total = $course_model->find()->joinWith('courseReview')->andOnCondition("hql_course_review.content!=''")->count();
        $totalPage =  ceil($total/$rows);
        $review = $course_model->find()->joinWith('courseReview')->andOnCondition("hql_course_review.content!=''")->offset($rows*($page-1))->limit($rows)
            ->where(['hql_course_notice.status'=>0])->offset($rows*($page-1))->limit($rows)
            ->orderBy(['createtime'=>SORT_DESC])->asArray()->all();
        $loadArr = ['course'=>$review];
        $this->_formatResponse(1,'正常',$loadArr,$totalPage);
    }

    public function actionCourse(){
        $id = Yii::$app->request->post('id');
        //课程预告
        $course_model = new CourseNotice();
        $course = $course_model->find()->joinWith('lecturer')->joinWith('courseReview')
            ->where(['hql_course_notice.status'=>0,'hql_course_notice.cnid'=>$id])
            ->orderBy(['createtime'=>SORT_DESC])->asArray()->one();
        $course['share_time'] = date('Y-m-d',$course['share_time']);
        $loadArr = ['course'=>$course];

        $this->_formatResponse(1,'正常',$loadArr);
    }
}
