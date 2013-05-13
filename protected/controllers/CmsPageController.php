<?php

class CmsPageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public $defaultAction = 'admin';
	
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
				'actions'=>array('show'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','delete','create','update','view'),
				'expression'=>"Yii::app()->user->isEditor()",
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
	public function actionView($id,$lang)
	{
		$this->layout='//layouts/column1';
		$model = $this->loadModel($id);
		$content=CmsPageContent::model()->findByAttributes(array('page'=>$model->id,'language'=>$lang));

		$items = CmsPage::model()->findAllByAttributes(array('block'=>$model->block), array('order'=>'weight'));
		$this->render('show',array(
			'model'=>$model,
			'items'=>$items,
			'content'=>$content,
		));
	}

	public function actionShow($id,$pageURL)
	{
		$this->layout='//layouts/column1';
		$model = $this->loadModel($id);	
		
		if($model->published == 0 && !Yii::app()->user->isEditor()){
			throw new CHttpException(404,'The requested page does not exist.');
			return $model;
		}
		$content = $model->getContentForModel(Yii::app()->language);
		$items = CmsPage::model()->findAllByAttributes(array('block'=>$model->block), array('order'=>'weight'));
		$this->render('show',array(
			'model'=>$model,
			'items'=>$items,
			'content'=>$content,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		// http://www.yiiframework.com/wiki/19/
		$model=new CmsPage;
		$content =new CmsPageContent;
		$languages=explode(',', Config::model()->findByPk('languages')->value);
		$content->language=$languages[0];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CmsPage'], $_POST['CmsPageContent']))
		{
			$model->attributes=$_POST['CmsPage'];
			$content->attributes=$_POST['CmsPageContent'];
			
			$content->page=0;	// dummy value. should do this with validation rule but it didn't work.
			//$content->setScenario('cms_page_create');
			if($model->validate() && $content->validate()){
				$model->save();
				$content->page=$model->id;
				$content->save();
				$this->redirect(array('view','id'=>$model->id,'lang'=>$content->language));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'content'=>$content,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if(isset($_GET['lang']))
			$lang=$_GET['lang'];
		else{
			$languages=explode(',', Config::model()->findByPk('languages')->value);
			$lang=$languages[0];
		}
		$model=$this->loadModel($id);
		$content=CmsPageContent::model()->findByAttributes(array('page'=>$model->id,'language'=>$lang));
		if(!$content){
			$orig_content=CmsPageContent::model()->find(array('condition'=> 'page = '.$model->id.' AND pageURL IS NOT NULL'));
			$content = new  CmsPageContent;
			$content->language = $lang;
			$content->pageURL = $orig_content->pageURL;
			$content->pageTitle = $orig_content->pageTitle;
			$content->body = $orig_content->body;
			$content->page=$model->id;
			$content->save();
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CmsPage'], $_POST['CmsPageContent']))
		{
			$model->attributes=$_POST['CmsPage'];
			$content->attributes=$_POST['CmsPageContent'];
			
			if($model->validate() && $content->validate()){
				$model->save();
				$content->save();
				$this->redirect(array('view','id'=>$model->id,'lang'=>$content->language));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'content'=>$content,
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('CmsPage');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CmsPage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CmsPage']))
			$model->attributes=$_GET['CmsPage'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CmsPage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CmsPage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CmsPage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cms-page-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}