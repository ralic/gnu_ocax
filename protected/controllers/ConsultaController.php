<?php

class ConsultaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view','index','getConsulta'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('reply','assigned'),
				'expression'=>"Yii::app()->user->isTeamMember()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('adminView','admin','assign','delete'),
				'expression'=>"Yii::app()->user->isManager()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */

	public function actionView($id)
	{
		$this->layout='//layouts/column1';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->layout='//layouts/column1';
		$model=new Consulta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consulta']))
			$model->attributes=$_GET['Consulta'];

		$this->render('index',array(
			'model'=>$model,
		));
	}


	public function actionGetConsulta($id)
	{
		if(!Yii::app()->request->isAjaxRequest)
			Yii::app()->end();
		$model=$this->loadModel($id);

		if($model){
			$json = CJavaScript::jsonEncode(array(
					'title'=>$model->title,
					'body'=>$model->body,
			));
			echo $json;
		}else
			echo 0;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->layout='//layouts/column1';
		$model=new Consulta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Consulta']))
		{
			$model->attributes=$_POST['Consulta'];
			$model->user = Yii::app()->user->getUserID();
			$model->created = date('Y-m-d');
			$model->state = 0;
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionReply($id)
	{
		$this->layout='//layouts/column1';
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Consulta']))
		{
			$model->attributes=$_POST['Consulta'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('reply',array(
			'model'=>$model,
			'new_respuesta'=>new Respuesta,
		));
	}

	public function actionAssigned()
	{
		// grid of consultas by team_member
		$this->layout='//layouts/column1';

		$model=new Consulta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consulta']))
			$model->attributes=$_GET['Consulta'];
		$model->team_member = Yii::app()->user->getUserID();

/*
		$consultas=new CActiveDataProvider('Consulta', array(

			'criteria'=>array(
				'condition'=>"team_member=$id",
				'order'=>'created DESC',
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
*/
		$this->render('assigned',array(
			'model'=>$model,
		));
	}

	public function actionAssign($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Consulta']))
		{
			$team_member=$model->team_member;
			$model->attributes=$_POST['Consulta'];
			if($team_member != $model->team_member){
				if($model->team_member){
					$model->assigned=date('Y-m-d');
					$model->state=1;
				}else{
					$model->assigned=Null;
					$model->state=0;
				}
			}
			if($model->save())
				$this->redirect(array('adminView','id'=>$model->id));
		}

		$team_members = user::model()->findAll(array("condition"=>"is_team_member =  1","order"=>"username"));
		$this->render('assign',array(
			'model'=>$model,
			'team_members'=>$team_members,
		));
	}


	public function actionAdminView($id)
	{
		$this->render('adminView',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->layout='//layouts/column1';
		$model=new Consulta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consulta']))
			$model->attributes=$_GET['Consulta'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Consulta::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='consulta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     